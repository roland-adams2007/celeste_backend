@props([
    'entityLabel' => 'Suite',
    'show' => 'showFormModal',
    'mode' => 'formMode',
    'error' => 'formError',
    'saving' => 'savingRoom',
    'onSubmit' => 'saveRoom()',
    'onClose' => 'closeForm()',
])

<div x-show="{{ $show }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-0 sm:p-4" style="display:none;">
    <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/60"></div>

    <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white border-0 sm:border border-[#e5ddd3] w-full h-screen sm:h-auto sm:max-h-[90vh] sm:max-w-4xl sm:rounded-none overflow-y-auto shadow-none sm:shadow-[0_8px_30px_rgba(14,26,43,0.15)]">

        <div class="flex items-center justify-between px-4 py-4 sm:px-8 sm:py-5 bg-[#0E1A2B] sticky top-0 z-10">
            <h3 class="font-['Cormorant_Garamond'] text-xl sm:text-2xl text-white"
                x-text="{{ $mode }} === 'create' ? 'Add New {{ $entityLabel }}' : 'Edit {{ $entityLabel }}'"></h3>
            <button type="button" @click="{{ $onClose }}" class="text-white/60 hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form @submit.prevent="{{ $onSubmit }}" class="px-4 py-5 space-y-4 sm:px-8 sm:py-6 sm:space-y-5">
            <div x-show="{{ $error }}" x-cloak
                class="text-[12px] text-[#9b1c1c] bg-[#9b1c1c]/5 border border-[#9b1c1c]/20 px-4 py-3"
                x-text="{{ $error }}"></div>

            {{ $slot }}

            <div class="flex items-center justify-end gap-3 pt-4 sm:gap-4 sm:pt-4 border-t border-[#e5ddd3]">
                <button type="button" @click="{{ $onClose }}" :disabled="{{ $saving }}"
                    class="text-xs sm:text-sm uppercase tracking-wider text-[#6b7280] hover:text-[#0E1A2B] px-4 py-2 sm:px-5 sm:py-2.5 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Cancel</button>
                <button type="submit" :disabled="{{ $saving }}"
                    class="text-xs sm:text-sm uppercase tracking-wider bg-[#0E1A2B] text-white px-5 py-2 sm:px-6 sm:py-2.5 hover:bg-[#0E1A2B]/90 transition-colors disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2 min-w-[120px]">
                    <span x-show="{{ $saving }}"
                        class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span
                        x-text="{{ $saving }} ? 'Saving...' : ({{ $mode }} === 'create' ? 'Add {{ $entityLabel }}' : 'Save Changes')"></span>
                </button>
            </div>
        </form>
    </div>
</div>