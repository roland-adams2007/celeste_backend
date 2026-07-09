@extends('components.layouts.admin')

@section('title', 'Media Library')

@section('page-title', 'Media Library')
@section('page-subtitle', 'Manage your uploaded images')

@section('admin-content')
    @push('styles')
        <style>
            .upload-drop-zone {
                border: 2px dashed #e5ddd3;
                transition: all 0.3s ease;
                background: #faf8f6;
            }

            .upload-drop-zone.dragover {
                border-color: #B89C6E;
                background: #f5f0ea;
            }

            .media-grid-item {
                position: relative;
                aspect-ratio: 1;
                overflow: hidden;
                background: #f9f4ee;
                border: 1px solid #e5ddd3;
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .media-grid-item:hover {
                border-color: #B89C6E;
            }

            .media-grid-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .media-grid-item .overlay {
                position: absolute;
                inset: 0;
                background: rgba(14, 26, 43, 0.6);
                opacity: 0;
                transition: opacity 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }

            .media-grid-item:hover .overlay {
                opacity: 1;
            }

            .media-grid-item .overlay button {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                border: none;
                background: white;
                color: #0E1A2B;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .media-grid-item .overlay button:hover {
                background: #B89C6E;
                color: white;
            }

            .media-grid-item .overlay button.danger:hover {
                background: #9b1c1c;
                color: white;
            }

            .upload-queue-item {
                position: relative;
                aspect-ratio: 1;
                border: 1px solid #e5ddd3;
                background: #f9f4ee;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .upload-queue-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .upload-queue-item .status-overlay {
                position: absolute;
                inset: 0;
                background: rgba(14, 26, 43, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .upload-queue-item .remove-btn {
                position: absolute;
                top: 4px;
                right: 4px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: none;
                background: rgba(14, 26, 43, 0.7);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.2s ease;
                z-index: 2;
            }

            .upload-queue-item .remove-btn:hover {
                background: #9b1c1c;
            }

            .empty-state {
                padding: 60px 20px;
                text-align: center;
            }

            .empty-state i {
                color: #d5cdc4;
                margin-bottom: 16px;
            }

            .empty-state p {
                color: #6b7280;
                font-size: 14px;
                font-weight: 300;
            }

            .empty-state .sub {
                font-size: 12px;
                color: #a0a0a0;
                margin-top: 4px;
            }
        </style>
    @endpush

    <div x-data="mediaLibraryPage()" x-init="init()"
        x-effect="document.body.classList.toggle('overflow-hidden', showImageModal || showUploadModal || showConfirmModal)"
        class="px-4 sm:px-6 md:px-10 py-6 md:py-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400" style="font-weight:300;letter-spacing:0.06em;">
                    <span x-text="totalImages"></span> images
                </span>
                <button @click="loadMediaLibrary(currentPage)" class="text-gray-400 hover:text-[#0E1A2B] transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4" :class="{ 'animate-spin': loading }"></i>
                </button>
            </div>
            <button @click="openUploadModal()"
                class="flex items-center gap-1.5 bg-[#0E1A2B] text-white text-xs uppercase tracking-wider px-4 py-2.5 hover:bg-[#0E1A2B]/90 transition-colors whitespace-nowrap"
                style="letter-spacing:0.08em;">
                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Upload Images
            </button>
        </div>

        <div x-show="loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            <template x-for="n in 10" :key="n">
                <div class="aspect-square bg-[#f9f4ee] animate-pulse border border-[#e5ddd3]"></div>
            </template>
        </div>

        <div x-show="!loading && mediaLibrary.length === 0" class="border border-[#e5ddd3] bg-white">
            <div class="empty-state">
                <i data-lucide="image" class="w-12 h-12 mx-auto"></i>
                <p>No images uploaded yet</p>
                <p class="sub">Upload your first image using the button above</p>
            </div>
        </div>

        <div x-show="!loading && mediaLibrary.length > 0"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            <template x-for="item in mediaLibrary" :key="item.id">
                <div class="media-grid-item group">
                    <img loading="lazy" :src="item.url" :alt="item.name" @click="openImage(item)">
                    <div class="overlay">
                        <button @click.stop="openImage(item)" title="View">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>
                        <button @click.stop="copyUrl(item.url)" title="Copy URL" class="relative">
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            <span x-show="copiedId === item.id" x-transition.opacity.duration.200ms
                                class="absolute -top-8 left-1/2 -translate-x-1/2 text-[9px] bg-[#0E1A2B] text-white px-2 py-0.5 whitespace-nowrap rounded">
                                Copied!
                            </span>
                        </button>
                        <button @click.stop="openDeleteConfirm(item)" class="danger" title="Delete">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                    <div class="absolute bottom-1 left-1 right-1">
                        <p class="text-[9px] text-white/80 truncate drop-shadow" x-text="item.name"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-center gap-1.5 mt-6" x-show="mediaLibrary.length > 0">
            <button @click="prevPage()" :disabled="currentPage === 1"
                class="w-8 h-8 flex items-center justify-center border border-[#e5ddd3] text-[#0E1A2B] disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#B89C6E] transition-colors">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
            </button>
            <template x-for="p in totalPages" :key="p">
                <button @click="loadMediaLibrary(p)" class="w-8 h-8 flex items-center justify-center text-xs"
                    :class="currentPage === p ? 'bg-[#0E1A2B] text-white' :
                        'border border-[#e5ddd3] text-[#0E1A2B] hover:border-[#B89C6E]'"
                    x-text="p"></button>
            </template>
            <button @click="nextPage()" :disabled="currentPage === totalPages"
                class="w-8 h-8 flex items-center justify-center border border-[#e5ddd3] text-[#0E1A2B] disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#B89C6E] transition-colors">
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </button>
        </div>

        <x-modals.confirm />
        <x-modals.view-image />

        <div x-cloak class="fixed inset-0 flex items-center justify-center p-4"
            :style="`z-index: ${zIndex('upload')}; display: ${ showUploadModal ? 'flex' : 'none' }`" style="display:none;">
            <div x-show="showUploadModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#0E1A2B]/70"></div>

            <div x-show="showUploadModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white border border-[#e5ddd3] w-full max-w-2xl max-h-[85vh] overflow-y-auto shadow-[0_8px_30px_rgba(14,26,43,0.2)]">

                <div
                    class="flex items-center justify-between px-6 py-4 border-b border-[#e5ddd3] sticky top-0 bg-white z-10">
                    <h3 class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B]">Upload Images</h3>
                    <button @click="closeUploadModal()" class="text-[#6b7280] hover:text-[#0E1A2B] transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)" @click="$refs.fileInput.click()"
                        class="upload-drop-zone px-6 py-10 flex flex-col items-center justify-center gap-2 cursor-pointer transition-colors"
                        :class="isDragging ? 'dragover' : ''">
                        <i data-lucide="upload-cloud" class="w-8 h-8 text-[#B89C6E]"></i>
                        <p class="text-[13px] text-[#1a1a1a]">Drag and drop images here, or click to browse</p>
                        <p class="text-[11px] text-[#6b7280]">PNG, JPG, WEBP up to 5MB each</p>
                        <input type="file" x-ref="fileInput" @change="handleFileSelect($event)" multiple
                            accept="image/*" class="hidden">
                    </div>

                    <div class="grid grid-cols-4 gap-3 mt-4" x-show="uploadQueue.length">
                        <template x-for="item in uploadQueue" :key="item.id">
                            <div class="upload-queue-item">
                                <img x-show="item.preview" :src="item.preview" alt="">
                                <div class="status-overlay" x-show="item.status === 'uploading'">
                                    <div
                                        class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin">
                                    </div>
                                </div>
                                <div class="status-overlay" x-show="item.status === 'error'">
                                    <i data-lucide="triangle-alert" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="status-overlay" x-show="item.status === 'done'">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                                </div>
                                <button type="button" @click.stop="removeUploadItem(item.id)" class="remove-btn">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                                <p class="absolute bottom-1 left-1 right-1 text-[8px] text-white/80 truncate drop-shadow"
                                    x-text="item.name"></p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                        <span
                            x-text="'Uploading ' + uploadQueue.filter(u => u.status === 'done').length + ' of ' + uploadQueue.length"></span>
                        <span x-show="uploadErrors.length > 0" class="text-[#9b1c1c]"
                            x-text="uploadErrors.length + ' error(s)'"></span>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between px-6 py-4 border-t border-[#e5ddd3] sticky bottom-0 bg-white">
                    <p class="text-[12px] text-[#6b7280]">
                        <span x-text="totalImages"></span> images in library
                    </p>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="closeUploadModal()"
                            class="text-xs uppercase tracking-wider text-[#6b7280] hover:text-[#0E1A2B] px-4 py-2 transition-colors">Close</button>
                        <button type="button" @click="closeUploadModal(); loadMediaLibrary(currentPage);"
                            class="text-xs uppercase tracking-wider bg-[#0E1A2B] text-white px-5 py-2 hover:bg-[#0E1A2B]/90 transition-colors">
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function mediaLibraryPage() {
                return {
                    mediaLibrary: [],
                    totalImages: 0,
                    loading: false,
                    currentPage: 1,
                    totalPages: 1,
                    perPage: 20,
                    showUploadModal: false,
                    showImageModal: false,
                    showConfirmModal: false,
                    isDragging: false,
                    uploadQueue: [],
                    uploadErrors: [],
                    activeImage: '',
                    activeImageCaption: '',
                    confirmTitle: '',
                    confirmMessage: '',
                    confirmDanger: false,
                    confirmAction: null,
                    confirmLoading: false,
                    copiedId: null,
                    modalStack: [],

                    pushModal(name) {
                        this.modalStack = this.modalStack.filter(m => m !== name);
                        this.modalStack.push(name);
                    },
                    popModal(name) {
                        this.modalStack = this.modalStack.filter(m => m !== name);
                    },
                    zIndex(name) {
                        const idx = this.modalStack.indexOf(name);
                        return idx === -1 ? 50 : 50 + (idx + 1) * 10;
                    },

                    init() {
                        this.$nextTick(() => lucide.createIcons());
                        this.loadMediaLibrary();
                    },

                    prevPage() {
                        if (this.currentPage > 1) this.loadMediaLibrary(this.currentPage - 1);
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages) this.loadMediaLibrary(this.currentPage + 1);
                    },

                    loadMediaLibrary(page = 1) {
                        this.loading = true;
                        fetch("{{ route('uploads.list') }}?page=" + page + "&per_page=" + this.perPage)
                            .then(r => r.json())
                            .then(data => {
                                this.mediaLibrary = data.data;
                                this.currentPage = data.current_page;
                                this.totalPages = data.last_page;
                                this.totalImages = data.total;
                                this.loading = false;
                                this.$nextTick(() => lucide.createIcons());
                            })
                            .catch(() => {
                                this.loading = false;
                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'error',
                                        title: 'Error',
                                        message: 'Failed to load media library.'
                                    }
                                }));
                            });
                    },

                    openUploadModal() {
                        this.showUploadModal = true;
                        this.pushModal('upload');
                        this.uploadQueue = [];
                        this.uploadErrors = [];
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeUploadModal() {
                        this.showUploadModal = false;
                        this.popModal('upload');
                        this.isDragging = false;
                    },

                    handleDrop(e) {
                        this.isDragging = false;
                        this.uploadFiles(e.dataTransfer.files);
                    },
                    handleFileSelect(e) {
                        this.uploadFiles(e.target.files);
                        e.target.value = '';
                    },

                    uploadFiles(fileList) {
                        Array.from(fileList).forEach(file => {
                            if (!file.type.startsWith('image/')) {
                                this.uploadErrors.push(file.name + ' is not an image');
                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'error',
                                        title: 'Invalid File',
                                        message: file.name + ' is not an image.'
                                    }
                                }));
                                return;
                            }
                            if (file.size > 5 * 1024 * 1024) {
                                this.uploadErrors.push(file.name + ' exceeds 5MB');
                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'error',
                                        title: 'File Too Large',
                                        message: file.name + ' exceeds 5MB limit.'
                                    }
                                }));
                                return;
                            }
                            this.uploadSingleFile(file);
                        });
                    },

                    uploadSingleFile(file) {
                        const tempId = Date.now() + Math.random();
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.uploadQueue.push({
                                id: tempId,
                                name: file.name,
                                preview: e.target.result,
                                status: 'uploading'
                            });
                            this.$nextTick(() => lucide.createIcons());

                            const payload = new FormData();
                            payload.append('image', file);
                            fetch("{{ route('uploads.store') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: payload
                                })
                                .then(r => r.json())
                                .then(data => {
                                    const item = this.uploadQueue.find(u => u.id === tempId);
                                    if (item) {
                                        item.status = 'done';
                                        this.totalImages += 1;
                                        if (this.currentPage === 1) {
                                            this.mediaLibrary.unshift({
                                                id: data.id,
                                                url: data.url,
                                                name: data.name
                                            });
                                            if (this.mediaLibrary.length > this.perPage) {
                                                this.mediaLibrary.pop();
                                            }
                                        }
                                    }
                                    this.$nextTick(() => lucide.createIcons());
                                    window.dispatchEvent(new CustomEvent('toast', {
                                        detail: {
                                            type: 'success',
                                            title: 'Upload Complete',
                                            message: file.name + ' uploaded successfully.'
                                        }
                                    }));
                                    setTimeout(() => {
                                        this.uploadQueue = this.uploadQueue.filter(u => u.id !== tempId);
                                    }, 600);
                                })
                                .catch(() => {
                                    const item = this.uploadQueue.find(u => u.id === tempId);
                                    if (item) item.status = 'error';
                                    this.uploadErrors.push(file.name + ' upload failed');
                                    window.dispatchEvent(new CustomEvent('toast', {
                                        detail: {
                                            type: 'error',
                                            title: 'Upload Failed',
                                            message: 'Failed to upload ' + file.name + '.'
                                        }
                                    }));
                                    this.$nextTick(() => lucide.createIcons());
                                });
                        };
                        reader.readAsDataURL(file);
                    },

                    removeUploadItem(id) {
                        const item = this.uploadQueue.find(u => u.id === id);
                        if (item && item.status === 'uploading') {
                        }
                        this.uploadQueue = this.uploadQueue.filter(u => u.id !== id);
                    },

                    openImage(item) {
                        this.activeImage = item.url;
                        this.activeImageCaption = item.name;
                        this.showImageModal = true;
                        this.pushModal('image');
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeImage() {
                        this.showImageModal = false;
                        this.popModal('image');
                    },

                    copyUrl(url) {
                        navigator.clipboard.writeText(url).then(() => {
                            this.copiedId = this.mediaLibrary.find(i => i.url === url)?.id;
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'success',
                                    title: 'Copied',
                                    message: 'URL copied to clipboard.'
                                }
                            }));
                            setTimeout(() => this.copiedId = null, 2000);
                        }).catch(() => {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'error',
                                    title: 'Copy Failed',
                                    message: 'Failed to copy URL.'
                                }
                            }));
                        });
                    },

                    openDeleteConfirm(item) {
                        this.confirmTitle = 'Delete this image?';
                        this.confirmMessage = 'This will permanently remove "' + item.name +
                            '" from your media library. This action cannot be undone.';
                        this.confirmDanger = true;
                        this.confirmLoading = false;
                        this.confirmAction = () => {
                            return fetch("{{ route('uploads.destroy', ':id') }}".replace(':id', item.id), {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(r => {
                                    if (!r.ok) throw new Error('delete failed');
                                    this.totalImages = Math.max(0, this.totalImages - 1);
                                    const newTotalPages = Math.max(1, Math.ceil(this.totalImages / this.perPage));
                                    const targetPage = Math.min(this.currentPage, newTotalPages);
                                    window.dispatchEvent(new CustomEvent('toast', {
                                        detail: {
                                            type: 'success',
                                            title: 'Deleted',
                                            message: 'Image deleted successfully.'
                                        }
                                    }));
                                    return this.loadMediaLibrary(targetPage);
                                })
                                .catch(() => {
                                    window.dispatchEvent(new CustomEvent('toast', {
                                        detail: {
                                            type: 'error',
                                            title: 'Delete Failed',
                                            message: 'Failed to delete image.'
                                        }
                                    }));
                                });
                        };
                        this.showConfirmModal = true;
                        this.pushModal('confirm');
                        this.$nextTick(() => lucide.createIcons());
                    },

                    runConfirmAction() {
                        if (typeof this.confirmAction !== 'function') {
                            this.showConfirmModal = false;
                            this.popModal('confirm');
                            return;
                        }

                        this.confirmLoading = true;
                        Promise.resolve(this.confirmAction())
                            .finally(() => {
                                this.confirmLoading = false;
                                this.showConfirmModal = false;
                                this.popModal('confirm');
                                this.$nextTick(() => lucide.createIcons());
                            });
                    }
                }
            }
        </script>
    @endpush
@endsection