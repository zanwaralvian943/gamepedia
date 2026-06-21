import 'highlight.js/styles/github-dark.css';
import hljs from 'highlight.js';
import { marked } from 'marked';

marked.setOptions({
    breaks: true,
    highlight: (code, lang) =>
        lang && hljs.getLanguage(lang)
            ? hljs.highlight(code, { language: lang }).value
            : hljs.highlightAuto(code).value,
});
const container = document.getElementById('chat-container');
const input = document.getElementById('prompt-input');
const sendBtn = document.getElementById('send-btn');
const sendIcon = document.getElementById('send-icon');
const sessionEl = document.getElementById('session-id');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('mobile-overlay');
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

const escapeHtml = t => t.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
const nowTime = () => new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
const scrollBottom = () => requestAnimationFrame(() => { container.scrollTop = container.scrollHeight; });

document.querySelectorAll('.ai-bubble[data-msg-id]').forEach(el => {
    const raw = _msgData[el.dataset.msgId] || '';
    if (!raw) return;
    el.innerHTML = marked.parse(raw);
    el.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
});
scrollBottom();

function appendUserBubble(text) {
    container.insertAdjacentHTML('beforeend', `
        <div class="flex justify-end gap-3 md:gap-4">
            <div class="max-w-[90%] md:max-w-[85%] bg-purple-600 text-white rounded-2xl p-4 shadow-sm relative">
                <p class="text-sm leading-relaxed pb-3">${escapeHtml(text)}</p>
                <span class="absolute bottom-1.5 right-4 text-[9px] text-purple-200">${nowTime()}</span>
            </div>
        </div>`);
    scrollBottom();
}

function appendTypingIndicator() {
    document.getElementById('typing-indicator')?.remove();
    container.insertAdjacentHTML('beforeend', `
        <div class="flex justify-start gap-3 md:gap-4" id="typing-indicator">
            <div class="w-8 h-8 rounded-full bg-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
                <i class="fas fa-headset text-xs"></i>
            </div>
            <div class="max-w-[90%] md:max-w-[85%] bg-white border border-gray-200 rounded-2xl p-4 md:p-5 shadow-sm flex items-center gap-1.5 h-12">
                <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
            </div>
        </div>`);
    scrollBottom();
}

function replaceTypingWithAIBubble() {
    document.getElementById('typing-indicator')?.remove();
    container.insertAdjacentHTML('beforeend', `
        <div class="flex justify-start gap-3 md:gap-4" id="streaming-bubble">
            <div class="w-8 h-8 rounded-full bg-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
                <i class="fas fa-headset text-xs"></i>
            </div>
            <div class="max-w-[90%] md:max-w-[85%] bg-white border border-gray-200 text-gray-800 rounded-2xl p-4 md:p-5 shadow-sm relative">
                <div class="ai-bubble text-sm leading-relaxed pb-3" id="streaming-text"></div>
                <span class="absolute bottom-1.5 left-4 text-[9px] text-gray-400">${nowTime()}</span>
            </div>
        </div>`);
    return document.getElementById('streaming-text');
}

function setLoading(on) {
    sendBtn.disabled = input.disabled = on;
    sendIcon.className = on ? 'fas fa-spinner fa-spin' : 'fas fa-paper-plane';
    sendBtn.classList.toggle('opacity-70', on);
    sendBtn.classList.toggle('cursor-not-allowed', on);
}

async function sendMessage() {
    const prompt = input.value.trim();
    if (!prompt) return;

    input.value = '';
    setLoading(true);
    appendUserBubble(prompt);
    appendTypingIndicator();

    try {
        const res = await fetch(_streamUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'text/event-stream' },
            body: JSON.stringify({ prompt, chat_session_id: sessionEl.value }),
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        let textEl = null, fullText = '', newSessionId = null;
        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;

            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop();

            for (const line of lines) {
                if (!line.startsWith('data: ')) continue;
                let json;
                try { json = JSON.parse(line.slice(6)); } catch { continue; }

                if (json.session_id) {
                    newSessionId = json.session_id;
                    sessionEl.value = newSessionId;
                }

                if (json.chunk !== undefined) {
                    textEl ??= replaceTypingWithAIBubble();
                    fullText += json.chunk;
                    textEl.innerText = fullText;
                    const nearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 150;
                    if (nearBottom) scrollBottom();
                }

                if (json.done) {
                    textEl ??= replaceTypingWithAIBubble();
                    if (textEl && fullText) {
                        textEl.innerHTML = marked.parse(fullText);
                        textEl.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
                    }
                    document.getElementById('streaming-bubble')?.removeAttribute('id');
                    textEl?.removeAttribute('id');
                    setLoading(false);
                    requestAnimationFrame(() => requestAnimationFrame(scrollBottom));

                    if (newSessionId) {
                        window.history.replaceState({}, '', `/chat/${newSessionId}`);
                        refreshSidebar(newSessionId, prompt);
                    }
                }
            }
        }
    } catch (err) {
        console.error(err);
        document.getElementById('typing-indicator')?.remove();
        setLoading(false);
        container.insertAdjacentHTML('beforeend', `
            <div class="flex justify-start gap-3 md:gap-4">
                <div class="w-8 h-8 rounded-full bg-red-500 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
                    <i class="fas fa-exclamation text-xs"></i>
                </div>
                <div class="max-w-[90%] bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm">
                    Gagal terhubung ke server. Coba lagi ya!
                </div>
            </div>`);
        scrollBottom();
    }
}
function toggleSidebar() {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

document.getElementById('open-sidebar').addEventListener('click', toggleSidebar);
document.getElementById('close-sidebar').addEventListener('click', toggleSidebar);
overlay.addEventListener('click', toggleSidebar);

function refreshSidebar(sessionId, prompt) {
    if (document.querySelector(`a[href*="/chat/${sessionId}"]`)) return;
    const title = prompt.length > 30 ? prompt.slice(0, 30) + '…' : prompt;
    const li = document.createElement('li');
    li.className = 'group relative';
    li.dataset.sessionId = sessionId;
    li.innerHTML = `
        <a href="/chat/${sessionId}" class="session-link flex items-center gap-3 px-3 py-2.5 pr-16 rounded-lg text-sm transition-colors bg-purple-50 text-purple-700 font-bold border-l-4 border-purple-600">
            <span class="text-sm flex-shrink-0 text-purple-600"><i class="fas fa-comment-dots"></i></span>
            <span class="session-title truncate flex-1">${escapeHtml(title)}</span>
        </a>
        <div class="absolute right-2 top-1/2 -translate-y-1/2 hidden group-hover:flex items-center gap-1">
            <button onclick="Chat.startRename(this, ${sessionId})" title="Rename" class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                <i class="fas fa-pencil text-[10px]"></i>
            </button>
            <button onclick="Chat.deleteSession(this, ${sessionId})" title="Hapus" class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                <i class="fas fa-trash text-[10px]"></i>
            </button>
        </div>`;
    document.getElementById('session-list').prepend(li);
}

// ─── Delete & rename session ──────────────────────────────────────────────────
async function deleteSession(btn, sessionId) {
    if (!confirm('Hapus sesi chat ini? Semua pesan akan hilang permanen.')) return;
    const res = await fetch(`/chat/session/${sessionId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    }).then(r => r.json()).catch(() => null);

    if (!res?.success) return alert('Gagal menghapus sesi.');
    btn.closest('li').remove();
    if (window.location.pathname.includes(`/chat/${sessionId}`)) window.location.href = '/chat';
}

function startRename(btn, sessionId) {
    const li = btn.closest('li');
    const titleEl = li.querySelector('.session-title');
    const actionsEl = li.querySelector('.group-hover\\:flex');
    const oldTitle = titleEl.textContent.trim();

    const inp = Object.assign(document.createElement('input'), {
        type: 'text', value: oldTitle,
        className: 'flex-1 bg-transparent border-b border-purple-400 text-sm outline-none text-purple-700 font-semibold min-w-0',
    });

    titleEl.replaceWith(inp);
    actionsEl?.classList.add('hidden');
    inp.focus(); inp.select();

    const restore = text => {
        const span = Object.assign(document.createElement('span'), {
            className: 'session-title truncate flex-1', textContent: text,
        });
        inp.replaceWith(span);
        actionsEl?.classList.remove('hidden');
    };

    async function commit() {
        const newTitle = inp.value.trim();
        if (!newTitle || newTitle === oldTitle) return restore(oldTitle);
        const data = await fetch(`/chat/session/${sessionId}`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ title: newTitle }),
        }).then(r => r.json()).catch(() => null);
        restore(data?.success ? data.title : oldTitle);
    }

    inp.addEventListener('blur', commit);
    inp.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); inp.blur(); }
        if (e.key === 'Escape') { inp.value = oldTitle; inp.blur(); }
    });
}

sendBtn.addEventListener('click', sendMessage);
input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});
window.Chat = { deleteSession, startRename };