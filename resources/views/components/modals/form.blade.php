@props([
    'entityLabel' => 'Suite',   // shown in header/footer text, e.g. "Add New Suite"
])

<div x-show="showFormModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
    <div x-show="showFormModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/60"></div>

    <div x-show="showFormModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white border border-[#e5ddd3] w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-[0_8px_30px_rgba(14,26,43,0.15)]">

        <div class="flex items-center justify-between px-6 py-4 bg-[#0E1A2B] sticky top-0 z-10">
            <h3 class="font-['Cormorant_Garamond'] text-xl text-white"
                x-text="formMode === 'create' ? 'Add New {{ $entityLabel }}' : 'Edit {{ $entityLabel }}'"></h3>
            <button type="button" @click="closeForm()" class="text-white/60 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form @submit.prevent="saveRoom()" class="px-6 py-5 space-y-4">
            {{ $slot }}

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-[#e5ddd3]">
                <button type="button" @click="closeForm()"
                    class="text-xs uppercase tracking-wider text-[#6b7280] hover:text-[#0E1A2B] px-4 py-2 transition-colors">Cancel</button>
                <button type="submit"
                    class="text-xs uppercase tracking-wider bg-[#0E1A2B] text-white px-5 py-2 hover:bg-[#0E1A2B]/90 transition-colors"
                    x-text="formMode === 'create' ? 'Add {{ $entityLabel }}' : 'Save Changes'"></button>
            </div>
        </form>
    </div>
</div>