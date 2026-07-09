<div x-cloak class="fixed inset-0 flex items-center justify-center p-4" :style="`z-index: ${zIndex('confirm')}; display: ${ showConfirmModal ? 'flex' : 'none' }`" style="display:none;">
    <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-[#0E1A2B]/60"></div>

    <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @keydown.escape.window="if (!confirmLoading) { showConfirmModal = false; popModal('confirm') }"
        class="relative w-full max-w-sm bg-[#F9F4EE] shadow-[0_8px_30px_rgba(14,26,43,0.2)] p-6">

        <div class="w-10 h-10 flex items-center justify-center mb-4"
            :class="confirmDanger ? 'bg-[#9b1c1c]/10' : 'bg-[#B89C6E]/15'">
            <i data-lucide="triangle-alert" class="w-4 h-4" :class="confirmDanger ? 'text-[#9b1c1c]' : 'text-[#B89C6E]'"></i>
        </div>

        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B] mb-2" x-text="confirmTitle"></h3>
        <p class="text-[13px] text-[#6b7280] leading-relaxed mb-6" x-text="confirmMessage"></p>

        <div class="flex items-center gap-3">
            <button @click="showConfirmModal = false; popModal('confirm')" :disabled="confirmLoading"
                class="flex-1 text-xs uppercase tracking-wider border border-[#e5ddd3] text-[#0E1A2B] px-4 py-2.5 hover:border-[#B89C6E] transition-colors disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:border-[#e5ddd3]"
                style="letter-spacing:0.08em;">Cancel</button>
            <button @click="runConfirmAction()" :disabled="confirmLoading"
                class="flex-1 flex items-center justify-center gap-2 text-xs uppercase tracking-wider text-white px-4 py-2.5 transition-colors disabled:cursor-not-allowed"
                :class="confirmDanger ? 'bg-[#9b1c1c] hover:bg-[#9b1c1c]/90 disabled:bg-[#9b1c1c]/70' : 'bg-[#0E1A2B] hover:bg-[#0E1A2B]/90 disabled:bg-[#0E1A2B]/70'"
                style="letter-spacing:0.08em;">
                <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin" x-show="confirmLoading" x-cloak></i>
                <span x-show="!confirmLoading">Confirm</span>
                <span x-show="confirmLoading">Please wait…</span>
            </button>
        </div>
    </div>
</div>