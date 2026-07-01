<div x-show="showDetailsDrawer" x-cloak class="fixed inset-0 z-[65]" style="display:none;">
    <div x-show="showDetailsDrawer" x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/60"></div>

    <div x-show="showDetailsDrawer" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-250 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute top-0 right-0 bottom-0 w-full max-w-md bg-[#F9F4EE] overflow-y-auto shadow-[-8px_0_30px_rgba(14,26,43,0.15)]">
        <template x-if="selectedRoom">
            <div>
                <div class="relative aspect-[16/10]">
                    <img :src="selectedRoom.image" class="w-full h-full object-cover">
                    <button @click="closeDetails()"
                        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-white/90 hover:bg-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4 text-[#0E1A2B]"></i>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-[11px] uppercase tracking-widest text-[#B89C6E] mb-1" x-text="selectedRoom.code"></p>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h2 class="font-['Cormorant_Garamond'] text-2xl text-[#0E1A2B]" x-text="selectedRoom.name"></h2>
                        <span class="text-[10px] uppercase tracking-wide px-2 py-1"
                            :class="statusColor(selectedRoom.status)" x-text="statusLabel(selectedRoom.status)"></span>
                    </div>
                    <p class="text-[12px] text-[#6b7280] mb-5"
                        x-text="selectedRoom.size + ' sqm · ' + viewLabel(selectedRoom.view) + ' · ' + selectedRoom.guests + ' guests'">
                    </p>

                    <div class="mb-6">
                        <p class="font-['Cormorant_Garamond'] text-2xl text-[#0E1A2B]" x-text="formatPrice(selectedRoom.price)"></p>
                        <p class="text-[11px] text-[#6b7280]">per night</p>
                    </div>

                    <div class="mb-6">
                        <p class="text-[11px] uppercase tracking-wider text-[#6b7280] mb-2">Description</p>
                        <p class="text-[13px] text-[#1a1a1a] leading-relaxed" x-text="selectedRoom.description"></p>
                    </div>

                    <div class="mb-8">
                        <p class="text-[11px] uppercase tracking-wider text-[#6b7280] mb-3">Amenities</p>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="a in selectedRoom.amenities" :key="a">
                                <div class="flex items-center gap-2 text-[12px] text-[#1a1a1a] capitalize">
                                    <i :data-lucide="a" class="w-3.5 h-3.5 text-[#B89C6E]"></i>
                                    <span x-text="a.replace('-', ' ')"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button @click="closeDetails(); openEdit(selectedRoom)"
                            class="flex-1 text-xs uppercase tracking-wider bg-[#0E1A2B] text-white px-4 py-2.5 hover:bg-[#0E1A2B]/90 transition-colors">Edit
                            Suite</button>
                        <button @click="openImage(selectedRoom)"
                            class="w-10 h-10 flex items-center justify-center border border-[#e5ddd3] hover:border-[#B89C6E] transition-colors">
                            <i data-lucide="expand" class="w-4 h-4 text-[#0E1A2B]"></i>
                        </button>
                        <button @click="closeDetails(); openDeleteConfirm(selectedRoom)"
                            class="w-10 h-10 flex items-center justify-center border border-[#e5ddd3] hover:border-[#9b1c1c] text-[#6b7280] hover:text-[#9b1c1c] transition-colors">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>