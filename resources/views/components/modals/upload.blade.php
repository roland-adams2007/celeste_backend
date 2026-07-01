<div x-show="showUploadModal" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    style="display:none;">
    <div x-show="showUploadModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/70"></div>

    <div x-show="showUploadModal" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white border border-[#e5ddd3] w-full max-w-2xl max-h-[85vh] overflow-y-auto shadow-[0_8px_30px_rgba(14,26,43,0.2)]">

        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e5ddd3] sticky top-0 bg-white z-10">
            <h3 class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B]">Suite Images</h3>
            <button @click="closeUploadModal()" class="text-[#6b7280] hover:text-[#0E1A2B] transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="px-6 py-5">
            <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                @drop.prevent="handleDrop($event)" @click="$refs.fileInput.click()"
                class="border border-dashed px-6 py-10 flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors"
                :class="isDragging ? 'border-[#B89C6E] bg-[#B89C6E]/5' : 'border-[#e5ddd3] hover:border-[#B89C6E]'">
                <i data-lucide="upload-cloud" class="w-6 h-6 text-[#B89C6E]"></i>
                <p class="text-[13px] text-[#1a1a1a]">Drag and drop images here, or click to browse</p>
                <p class="text-[11px] text-[#6b7280]">PNG, JPG up to 5MB each</p>
                <input type="file" x-ref="fileInput" @change="handleFileSelect($event)" multiple accept="image/*"
                    class="hidden">
            </div>

            <div class="grid grid-cols-4 gap-3 mt-4" x-show="uploadQueue.length">
                <template x-for="item in uploadQueue" :key="item.id">
                    <div
                        class="relative aspect-square border border-[#e5ddd3] flex items-center justify-center bg-[#F9F4EE]">
                        <button type="button" @click.stop="removeUploadItem(item.id)"
                            class="absolute top-1 right-1 z-10 w-5 h-5 rounded-full bg-[#0E1A2B]/70 hover:bg-[#0E1A2B] flex items-center justify-center transition-colors">
                            <i data-lucide="x" class="w-3 h-3 text-white"></i>
                        </button>
                        <div x-show="item.status === 'uploading'"
                            class="w-5 h-5 border-2 border-[#B89C6E] border-t-transparent rounded-full animate-spin">
                        </div>
                        <i x-show="item.status === 'error'" data-lucide="triangle-alert"
                            class="w-4 h-4 text-[#9b1c1c]"></i>
                        <p class="absolute bottom-1 left-1 right-1 text-[9px] text-[#6b7280] truncate"
                            x-text="item.name">
                        </p>
                    </div>
                </template>
            </div>

            <div class="mt-6">
                <p class="text-[11px] uppercase tracking-wider text-[#6b7280] mb-3">Media Library</p>

                <div x-show="mediaLibraryLoading" class="grid grid-cols-4 gap-3">
                    <template x-for="n in 8" :key="n">
                        <div class="aspect-square bg-[#F9F4EE] animate-pulse"></div>
                    </template>
                </div>

                <div x-show="!mediaLibraryLoading && mediaLibrary.length" class="grid grid-cols-4 gap-3">
                    <template x-for="item in mediaLibrary" :key="item.id">
                        <div @click="toggleSelectImage(item.url)"
                            class="relative aspect-square overflow-hidden border cursor-pointer"
                            :class="tempSelectedImages.includes(item.url) ? 'border-[#B89C6E]' : 'border-[#e5ddd3]'">
                            <img :src="item.url" class="w-full h-full object-cover">
                            <div x-show="tempSelectedImages.includes(item.url)"
                                class="absolute inset-0 bg-[#0E1A2B]/40 flex items-center justify-center">
                                <div class="w-6 h-6 rounded-full bg-[#B89C6E] flex items-center justify-center">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <p x-show="!mediaLibraryLoading && !mediaLibrary.length" class="text-[12px] text-[#6b7280]">
                    No images uploaded yet.</p>
            </div>
        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t border-[#e5ddd3] sticky bottom-0 bg-white">
            <p class="text-[12px] text-[#6b7280]" x-text="tempSelectedImages.length + ' selected'"></p>
            <div class="flex items-center gap-3">
                <button type="button" @click="closeUploadModal()"
                    class="text-xs uppercase tracking-wider text-[#6b7280] hover:text-[#0E1A2B] px-4 py-2 transition-colors">Cancel</button>
                <button type="button" @click="confirmImageSelection()"
                    class="text-xs uppercase tracking-wider bg-[#0E1A2B] text-white px-5 py-2 hover:bg-[#0E1A2B]/90 transition-colors">Use
                    Selected</button>
            </div>
        </div>
    </div>
</div>
