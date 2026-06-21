@props(['message'])

<div id="toast-alert" role="alert"
    class="fixed bottom-6 right-6 z-50 p-4 text-sm text-fg-success-strong rounded-base bg-success-soft shadow-lg">
    <span class="font-medium">Success!</span> {{ $message }}
</div>

<script>
    setTimeout(() => document.getElementById('toast-alert')?.remove(), 3000);
</script>
