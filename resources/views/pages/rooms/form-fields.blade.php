{{-- Suite Type toggle: standard = multiple physical rooms, signature = one-of-a-kind --}}
<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Type</label>
    <div class="flex gap-2">
        <button type="button" @click="formData.type = 'standard'"
            class="flex-1 py-2.5 text-[12px] border transition-all"
            :class="formData.type === 'standard' ? 'border-[#0E1A2B] bg-[#0E1A2B] text-white' : 'border-[#e5ddd3] text-[#6b7280] bg-white'">
            Standard
        </button>
        <button type="button" @click="formData.type = 'signature'"
            class="flex-1 py-2.5 text-[12px] border transition-all"
            :class="formData.type === 'signature' ? 'border-[#B89C6E] bg-[#fdf8f0] text-[#B89C6E]' : 'border-[#e5ddd3] text-[#6b7280] bg-white'">
            Signature
        </button>
    </div>
    <p class="text-[11px] text-[#9ca3af] mt-1" x-show="formData.type === 'standard'">Has multiple physical rooms sharing these details.</p>
    <p class="text-[11px] text-[#9ca3af] mt-1" x-show="formData.type === 'signature'">A single, one-of-a-kind room.</p>
</div>

{{-- Room number(s): standard suites get a tag input (one `rooms` row per number), signature gets a single number --}}
<div x-show="formData.type === 'standard'">
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">
        Room Numbers <span class="normal-case tracking-normal text-[#9ca3af]">(press Enter or comma to add)</span>
    </label>
    <div @click="$refs.roomNumberInput.focus()"
        class="flex flex-wrap gap-1.5 min-h-[42px] border border-[#e5ddd3] px-2.5 py-2 cursor-text">
        <template x-for="num in formData.room_numbers" :key="num">
            <span class="inline-flex items-center gap-1 bg-[#0E1A2B] text-white text-[11px] font-mono px-2 py-0.5">
                <span x-text="num"></span>
                <button type="button" @click="removeRoomNumber(num)" class="text-white/60 hover:text-white leading-none">&times;</button>
            </span>
        </template>
        <input x-ref="roomNumberInput" type="text" x-model="formData.room_number_input"
            @keydown="handleRoomNumberKey($event)" placeholder="e.g. 101"
            class="flex-1 min-w-[60px] border-0 outline-none text-[13px] p-0">
    </div>
    <p class="text-[11px] text-[#9ca3af] mt-1" x-text="formData.room_numbers.length + ' room' + (formData.room_numbers.length !== 1 ? 's' : '') + ' added'"></p>
</div>

<div x-show="formData.type === 'signature'">
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Room Number</label>
    <input type="text" x-model="formData.room_numbers[0]" placeholder="e.g. PH-01"
        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Name</label>
        <input type="text" x-model="formData.name" required placeholder="e.g. Deluxe King"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Code</label>
        <input type="text" x-model="formData.code" readonly
            class="w-full border border-[#e5ddd3] bg-[#F9F4EE] px-3 py-2 text-sm text-[#6b7280] focus:outline-none">
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Category</label>
        <select x-model="formData.category" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="">Select...</option>
            <option>Deluxe</option>
            <option>Executive</option>
            <option>Signature</option>
            <option>Penthouse</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Floor</label>
        <select x-model="formData.floor" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="">Select...</option>
            <option>Ground Floor</option>
            <option>1st Floor</option>
            <option>2nd Floor</option>
            <option>3rd Floor</option>
            <option>4th Floor</option>
            <option>5th Floor</option>
            <option>Penthouse Level</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Size (sqm)</label>
        <input type="number" x-model.number="formData.size" required min="0"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Capacity</label>
        <select x-model.number="formData.capacity"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Bed Type</label>
        <select x-model="formData.bed_type"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option>King</option>
            <option>Queen</option>
            <option>Twin King</option>
            <option>Super King</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">View Type</label>
        <select x-model="formData.view_type"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="city">City view</option>
            <option value="pool">Pool view</option>
            <option value="garden">Garden view</option>
            <option value="panoramic">Panoramic</option>
            <option value="ocean">Ocean view</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Status</label>
        <select x-model="formData.status"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Rate / Night (₦)</label>
        <input type="number" x-model.number="formData.rate" required min="0"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Weekend Rate (₦)</label>
        <input type="number" x-model.number="formData.rate_weekend" min="0"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
</div>

{{-- Images: unchanged, still backed by the upload modal --}}
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

{{-- Amenities: loaded from the amenities table (see AmenityController@list), not hardcoded --}}
<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-2">Amenities</label>
    <div class="grid grid-cols-2 gap-2" x-show="amenitiesList.length">
        <template x-for="a in amenitiesList" :key="a.slug">
            <label class="flex items-center gap-2 text-[12px] text-[#1a1a1a] cursor-pointer">
                <input type="checkbox" :checked="formData.amenities.includes(a.slug)"
                    @change="toggleAmenity(a.slug)" class="accent-[#B89C6E]">
                <span x-text="a.name"></span>
            </label>
        </template>
    </div>
    <p class="text-[11px] text-[#9ca3af]" x-show="!amenitiesList.length">No amenities configured yet.</p>
</div>

<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Notes</label>
    <textarea x-model="formData.notes" rows="3"
        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E] resize-none"></textarea>
</div>