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

        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e5ddd3] sticky top-0 bg-white z-10">
            <h3 class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B]"
                x-text="formMode === 'create' ? 'Add New Suite' : 'Edit Suite'"></h3>
            <button @click="closeForm()" class="text-[#6b7280] hover:text-[#0E1A2B] transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form @submit.prevent="saveRoom()" class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Code</label>
                <input type="text" x-model="formData.code" readonly
                    class="w-full border border-[#e5ddd3] bg-[#F9F4EE] px-3 py-2 text-sm text-[#6b7280] focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Name</label>
                <input type="text" x-model="formData.name" required placeholder="e.g. Deluxe King"
                    class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">View Type</label>
                    <select x-model="formData.view"
                        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
                        <option value="city">City view</option>
                        <option value="pool">Pool view</option>
                        <option value="garden">Garden view</option>
                        <option value="panoramic">Panoramic</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Status</label>
                    <select x-model="formData.status"
                        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Price / Night</label>
                    <input type="number" x-model.number="formData.price" required min="0"
                        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Size (sqm)</label>
                    <input type="number" x-model.number="formData.size" required min="0"
                        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Guests</label>
                    <input type="number" x-model.number="formData.guests" required min="1"
                        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
                </div>
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Images</label>
                <div class="grid grid-cols-4 gap-2 mb-2" x-show="formData.images && formData.images.length">
                    <template x-for="img in formData.images" :key="img">
                        <div class="relative aspect-square overflow-hidden border border-[#e5ddd3] group">
                            <img :src="img" class="w-full h-full object-cover">
                            <button type="button" @click="removeFormImage(img)"
                                class="absolute top-1 right-1 w-5 h-5 flex items-center justify-center bg-[#0E1A2B]/70 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="openUploadModal()"
                    class="w-full border border-dashed border-[#e5ddd3] hover:border-[#B89C6E] py-6 flex flex-col items-center justify-center gap-2 text-[#6b7280] hover:text-[#B89C6E] transition-colors">
                    <i data-lucide="image-plus" class="w-5 h-5"></i>
                    <span class="text-[11px] uppercase tracking-wider"
                        x-text="formData.images && formData.images.length ? 'Add More Images' : 'Add Images'"></span>
                </button>
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Description</label>
                <textarea x-model="formData.description" rows="3"
                    class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E] resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-2">Amenities</label>
                <div class="grid grid-cols-2 gap-2">
                    <template
                        x-for="a in [{key:'wifi',label:'Free Wi-Fi'},{key:'tv',label:'Smart TV'},{key:'coffee',label:'Breakfast'},{key:'bath',label:'Soaking Tub'},{key:'wine',label:'Minibar'},{key:'utensils',label:'Kitchenette'},{key:'car',label:'Parking'},{key:'wind',label:'Air Conditioning'},{key:'shield-check',label:'In-room Safe'},{key:'star',label:'Premium Service'}]"
                        :key="a.key">
                        <label class="flex items-center gap-2 text-[12px] text-[#1a1a1a] cursor-pointer">
                            <input type="checkbox" :checked="formData.amenities.includes(a.key)"
                                @change="toggleAmenity(a.key)" class="accent-[#B89C6E]">
                            <span x-text="a.label"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-[#e5ddd3]">
                <button type="button" @click="closeForm()"
                    class="text-xs uppercase tracking-wider text-[#6b7280] hover:text-[#0E1A2B] px-4 py-2 transition-colors">Cancel</button>
                <button type="submit"
                    class="text-xs uppercase tracking-wider bg-[#0E1A2B] text-white px-5 py-2 hover:bg-[#0E1A2B]/90 transition-colors"
                    x-text="formMode === 'create' ? 'Add Suite' : 'Save Changes'"></button>
            </div>
        </form>
    </div>
</div>