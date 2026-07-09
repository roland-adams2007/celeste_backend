@props([
    'duration' => 4500,
])

<div
    x-data="alertComponent()"
    x-init="init()"
    @toast.window="showToast($event.detail.type, $event.detail.title, $event.detail.message)"
    x-show="toasts.length > 0"
    class="fixed top-4 left-0 right-0 flex flex-col items-center gap-2.5 z-[999] px-4 md:top-auto md:bottom-6 md:left-auto md:right-6 md:items-end md:px-0 md:w-[360px]"
    x-cloak
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            class="w-full relative border shadow-lg overflow-hidden"
            :class="[variants[toast.type].border, variants[toast.type].bg, toast.inAnim]"
            x-show="!toast.removed"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="absolute top-0 left-0 bottom-0 w-[3px]" :class="variants[toast.type].accent"></div>

            <div class="flex items-start gap-3 px-4 py-3.5 pl-5">
                <i :data-lucide="variants[toast.type].icon" class="w-[17px] h-[17px] shrink-0 mt-px" :class="variants[toast.type].iconColor"></i>

                <div class="flex-1 min-w-0">
                    <div class="text-[12px] font-semibold text-navy leading-snug" x-text="toast.title"></div>
                    <div class="text-[11px] text-[#6b7280] font-light mt-0.5 leading-relaxed" x-text="toast.message"></div>
                </div>

                <button @click="dismissToast(toast.id)" class="shrink-0 w-5 h-5 flex items-center justify-center text-[#9ca3af] hover:text-ink transition-colors bg-transparent border-0 cursor-pointer mt-px">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="h-[2px] w-full bg-[#f3f0eb]">
                <div :id="toast.id + '-bar'" class="h-full" :class="variants[toast.type].progress" :style="`width: ${toast.progress}%`"></div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    function alertComponent() {
        return {
            toasts: [],
            duration: {{ $duration }},
            variants: {
                success: {
                    icon: 'check-circle',
                    accent: 'bg-green-500',
                    iconColor: 'text-green-600',
                    bg: 'bg-white',
                    border: 'border-green-200',
                    progress: 'bg-green-500',
                },
                error: {
                    icon: 'x-circle',
                    accent: 'bg-red-500',
                    iconColor: 'text-red-600',
                    bg: 'bg-white',
                    border: 'border-red-200',
                    progress: 'bg-red-500',
                },
                warning: {
                    icon: 'alert-triangle',
                    accent: 'bg-amber-400',
                    iconColor: 'text-amber-500',
                    bg: 'bg-white',
                    border: 'border-amber-200',
                    progress: 'bg-amber-400',
                },
                info: {
                    icon: 'info',
                    accent: 'bg-blue-400',
                    iconColor: 'text-blue-500',
                    bg: 'bg-white',
                    border: 'border-blue-200',
                    progress: 'bg-blue-400',
                },
                gold: {
                    icon: 'crown',
                    accent: 'bg-[#B89C6E]',
                    iconColor: 'text-[#B89C6E]',
                    bg: 'bg-[#fdfaf5]',
                    border: 'border-[#e5ddd3]',
                    progress: 'bg-[#B89C6E]',
                },
            },
            isMobile() {
                return window.innerWidth < 768;
            },
            init() {
                lucide.createIcons();

                // Auto-detect toast from page data
                if (window.toastData) {
                    this.showToast(
                        window.toastData.type || 'success',
                        window.toastData.title || 'Success',
                        window.toastData.message || 'Operation completed successfully.'
                    );
                }
            },
            showToast(type, title, message) {
                const id = 'toast-' + Date.now();
                const inAnim = this.isMobile() ? 'animate-slide-in-top' : 'animate-slide-in-right';
                const outAnim = this.isMobile() ? 'animate-slide-out-top' : 'animate-slide-out-right';

                this.toasts.push({
                    id: id,
                    type: type,
                    title: title,
                    message: message,
                    inAnim: inAnim,
                    outAnim: outAnim,
                    progress: 100,
                    removed: false,
                    timer: null,
                });

                const toastIndex = this.toasts.length - 1;

                setTimeout(() => {
                    const bar = document.getElementById(id + '-bar');
                    if (bar) {
                        bar.style.transition = `width ${this.duration}ms linear`;
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                bar.style.width = '0%';
                            });
                        });
                    }
                }, 50);

                this.toasts[toastIndex].timer = setTimeout(() => {
                    this.dismissToast(id);
                }, this.duration);

                lucide.createIcons();
            },
            dismissToast(id) {
                const toast = this.toasts.find(t => t.id === id);
                if (!toast || toast.removed) return;

                clearTimeout(toast.timer);
                toast.removed = true;

                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            },
        };
    }
</script>
@endpush