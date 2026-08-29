<div x-data="toastState()"
     x-show="visible"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-4"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-4"
     class="fixed top-4 right-4 z-[60] w-full max-w-sm"
     style="display: none;"
     role="status"
     aria-live="polite">
    <div :class="type === 'error' ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800'"
         class="flex items-start gap-3 rounded-xl border px-4 py-3 shadow-lg">
        <div class="mt-0.5 shrink-0">
            <i :class="type === 'error' ? 'bi bi-exclamation-circle-fill text-rose-500' : 'bi bi-check-circle-fill text-emerald-500'" class="text-lg"></i>
        </div>
        <p class="flex-1 text-sm leading-snug" x-text="message"></p>
        <button type="button" @click="hide()" class="shrink-0 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    </div>
</div>

<script>
function toastState() {
    const status = @json(session('status') ?? session('success') ?? null);
    const error = @json($errors->any() ? $errors->first() : null);

    return {
        visible: !!(status || error),
        message: error ?? status,
        type: error ? 'error' : 'success',
        timer: null,
        init() {
            if (this.visible) {
                this.timer = setTimeout(() => this.hide(), 4000);
            }
        },
        hide() {
            this.visible = false;
            if (this.timer) clearTimeout(this.timer);
        }
    };
}
</script>
