<div x-show="showImageModal" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4" style="display:none;">
    <div x-show="showImageModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/85"></div>

    <div x-show="showImageModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @keydown.escape.window="closeImage()"
        class="relative max-w-3xl w-full">
        <button @click="closeImage()" class="absolute -top-10 right-0 text-white/70 hover:text-white transition-colors">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        <div class="bg-white border border-[#e5ddd3] overflow-hidden">
            <img loading="lazy" :src="activeImage" class="w-full max-h-[70vh] object-cover">
            <div class="px-5 py-3 border-t border-[#e5ddd3]">
                <p class="text-[11px] uppercase tracking-widest text-[#B89C6E]" x-text="activeImageCaption"></p>
            </div>
        </div>
    </div>
</div>