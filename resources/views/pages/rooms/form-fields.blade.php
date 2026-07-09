{{-- Suite Type toggle: standard = multiple physical rooms, signature = one-of-a-kind --}}
<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Type</label>
    <div class="flex gap-2">
        <button type="button" @click="formData.type = 'standard'" class="flex-1 py-2.5 text-[12px] border transition-all"
            :class="formData.type === 'standard' ? 'border-[#0E1A2B] bg-[#0E1A2B] text-white' :
                'border-[#e5ddd3] text-[#6b7280] bg-white'">
            Standard
        </button>
        <button type="button" @click="formData.type = 'signature'" class="flex-1 py-2.5 text-[12px] border transition-all"
            :class="formData.type === 'signature' ? 'border-[#B89C6E] bg-[#fdf8f0] text-[#B89C6E]' :
                'border-[#e5ddd3] text-[#6b7280] bg-white'">
            Signature
        </button>
    </div>
    <p class="text-[11px] text-[#9ca3af] mt-1" x-show="formData.type === 'standard'">Has multiple physical rooms sharing
        these details.</p>
    <p class="text-[11px] text-[#9ca3af] mt-1" x-show="formData.type === 'signature'">A single, one-of-a-kind room.</p>
</div>

<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Suite Name</label>
    <input type="text" x-model="formData.name" required placeholder="e.g. Deluxe King"
        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
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
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Bed Type</label>
        <select x-model="formData.bed_type" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option>King</option>
            <option>Queen</option>
            <option>Twin King</option>
            <option>Super King</option>
        </select>
    </div>
</div>

{{-- Description: Quill editor, synced into formData.description via hidden state --}}
{{-- Quill mounts once (the modal stays in the DOM, just hidden), so the initial content sync
     only ever ran on that first mount. syncQuillContent() re-runs it every time the modal opens,
     which is what actually loads the description when editing an existing suite. --}}
<div x-data="{
    quill: null,
    initQuill() {
        this.quill = new Quill(this.$refs.quillEditor, {
            theme: 'snow',
            placeholder: 'Describe the suite...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });
        this.syncQuillContent();
        this.quill.on('text-change', () => {
            formData.description = this.quill.root.innerHTML;
        });
        this.$watch('showFormModal', (isOpen) => {
            if (isOpen) this.syncQuillContent();
        });
    },
    syncQuillContent() {
        this.quill.root.innerHTML = formData.description || '';
    }
}" x-init="initQuill()">
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Description</label>
    <div x-ref="quillEditor" class="bg-white text-sm" style="min-height: 140px;"></div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Size (sqm)</label>
        <input type="number" x-model.number="formData.size" required min="1"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Capacity</label>
        <select x-model.number="formData.capacity" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">View Type</label>
        <select x-model="formData.view_type" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="city">City view</option>
            <option value="pool">Pool view</option>
            <option value="garden">Garden view</option>
            <option value="panoramic">Panoramic</option>
            <option value="ocean">Ocean view</option>
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
        <input type="number" x-model.number="formData.rate_weekend" required min="0"
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
            <input type="checkbox" :checked="(formData.amenities || []).includes(a.slug)" @change="toggleAmenity(a.slug)"
                    class="accent-[#B89C6E]">
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