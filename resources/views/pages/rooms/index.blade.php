@extends('components.layouts.admin')

@section('title', 'Rooms & Suites')

@section('page-title', 'Rooms & Suites')
@section('page-subtitle', 'Manage your hotel rooms and suites')

@section('admin-content')
    @push('styles')
        <style>
            .suite-filter-btn {
                font-family: 'DM Sans', sans-serif;
                font-size: 12px;
                font-weight: 400;
                color: #888;
                background: transparent;
                border: 1px solid rgba(0, 0, 0, 0.15);
                padding: 6px 16px;
                cursor: pointer;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                transition: all 0.2s;
            }

            .suite-filter-btn:hover {
                border-color: #B89C6E;
                color: #B89C6E;
            }

            .suite-filter-btn.active {
                background: #0E1A2B;
                color: #fff;
                border-color: #0E1A2B;
            }

            .suite-search {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                font-weight: 300;
                border: 1px solid rgba(0, 0, 0, 0.15);
                background: #fff;
                outline: none;
            }

            .suite-search:focus {
                border-color: #B89C6E;
            }
        </style>
    @endpush

    <div x-data="roomsPage()" x-init="init()" class="px-4 sm:px-6 md:px-10 py-6 md:py-8">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8 md:mb-10">
            <div class="bg-white border border-[#e5ddd3] p-4 md:p-5">
                <div class="w-9 h-9 flex items-center justify-center bg-[#B89C6E]/15 mb-4">
                    <i data-lucide="bed-double" class="w-4 h-4 text-[#B89C6E]"></i>
                </div>
                <p class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#0E1A2B]" style="font-weight:600;"
                    x-text="rooms.length">
                </p>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-1" style="letter-spacing:0.12em;">Total
                    Suites</p>
            </div>
            <div class="bg-white border border-[#e5ddd3] p-4 md:p-5">
                <div class="w-9 h-9 flex items-center justify-center bg-[#2d6a4f]/10 mb-4">
                    <i data-lucide="circle-check" class="w-4 h-4 text-[#2d6a4f]"></i>
                </div>
                <p class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#0E1A2B]" style="font-weight:600;"
                    x-text="rooms.filter(r => r.status === 'available').length"></p>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-1" style="letter-spacing:0.12em;">Available
                </p>
            </div>
            <div class="bg-white border border-[#e5ddd3] p-4 md:p-5">
                <div class="w-9 h-9 flex items-center justify-center bg-[#B89C6E]/15 mb-4">
                    <i data-lucide="users" class="w-4 h-4 text-[#B89C6E]"></i>
                </div>
                <p class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#0E1A2B]" style="font-weight:600;"
                    x-text="rooms.filter(r => r.status === 'occupied').length"></p>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-1" style="letter-spacing:0.12em;">Occupied
                </p>
            </div>
            <div class="bg-white border border-[#e5ddd3] p-4 md:p-5">
                <div class="w-9 h-9 flex items-center justify-center bg-[#9b1c1c]/10 mb-4">
                    <i data-lucide="wrench" class="w-4 h-4 text-[#9b1c1c]"></i>
                </div>
                <p class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#0E1A2B]" style="font-weight:600;"
                    x-text="rooms.filter(r => r.status === 'maintenance').length"></p>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mt-1" style="letter-spacing:0.12em;">
                    Maintenance</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 md:gap-6 mb-6">
            <div class="flex items-center gap-2 md:gap-3 flex-wrap">
                <span class="text-xs text-gray-400 mr-1" style="font-weight:300;letter-spacing:0.06em;">Filter by</span>
                <button class="suite-filter-btn" :class="filterView === 'all' ? 'active' : ''"
                    @click="filterView = 'all'">All suites</button>
                <button class="suite-filter-btn" :class="filterView === 'city' ? 'active' : ''"
                    @click="filterView = 'city'">City view</button>
                <button class="suite-filter-btn" :class="filterView === 'pool' ? 'active' : ''"
                    @click="filterView = 'pool'">Pool view</button>
                <button class="suite-filter-btn" :class="filterView === 'garden' ? 'active' : ''"
                    @click="filterView = 'garden'">Garden view</button>
                <button class="suite-filter-btn" :class="filterView === 'panoramic' ? 'active' : ''"
                    @click="filterView = 'panoramic'">Panoramic</button>
            </div>
            <div class="flex items-center gap-3 flex-wrap w-full md:w-auto">
                <div class="relative flex-1 min-w-[160px] md:flex-none">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" x-model="search" placeholder="Search suites..."
                        class="suite-search pl-9 pr-3 py-2.5 w-full md:w-60">
                </div>
                <button @click="openCreate()"
                    class="flex items-center gap-1.5 bg-[#0E1A2B] text-white text-xs uppercase tracking-wider px-4 py-2.5 hover:bg-[#0E1A2B]/90 transition-colors whitespace-nowrap"
                    style="letter-spacing:0.08em;">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Suite
                </button>
            </div>
        </div>

        <p class="text-xs text-gray-400 mb-4" style="font-weight:300;"
            x-text="'Showing ' + filteredRooms.length + ' suite' + (filteredRooms.length !== 1 ? 's' : '')"></p>

        <div class="hidden md:block overflow-x-auto border border-[#e5ddd3] bg-white">
            <table class="w-full text-left border-collapse min-w-[880px]">
                <thead>
                    <tr class="border-b border-[#e5ddd3]">
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Suite</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">View</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Price</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Size</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Guests</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Amenities</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal"
                            style="letter-spacing:0.12em;">Status</th>
                        <th class="px-5 py-3.5 text-[10px] uppercase tracking-widest text-gray-400 font-normal text-right"
                            style="letter-spacing:0.12em;">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e5ddd3]">
                    <template x-for="room in paginatedRooms" :key="room.id">
                        <tr class="hover:bg-[#F9F4EE]/60 transition-colors group">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 overflow-hidden flex-shrink-0 cursor-pointer"
                                        @click="openImage(room)">
                                        <img :src="room.image" :alt="room.name"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-[#B89C6E] mb-0.5"
                                            style="letter-spacing:0.12em;" x-text="room.code"></p>
                                        <p class="font-['Cormorant_Garamond'] text-base text-[#0E1A2B]"
                                            style="font-weight:500;" x-text="room.name"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-[13px] text-gray-500" style="font-weight:300;"
                                x-text="viewLabel(room.view)"></td>
                            <td class="px-5 py-4 font-['Cormorant_Garamond'] text-base text-[#0E1A2B]"
                                style="font-weight:600;" x-text="formatPrice(room.price)"></td>
                            <td class="px-5 py-4 text-[13px] text-gray-500" style="font-weight:300;"
                                x-text="room.size + ' sqm'"></td>
                            <td class="px-5 py-4 text-[13px] text-gray-500" style="font-weight:300;"
                                x-text="room.guests"></td>
                            <td class="px-5 py-4">
                                <div class="flex gap-2.5 text-gray-400">
                                    <template x-for="a in room.amenities.slice(0,4)" :key="a">
                                        <i :data-lucide="a" class="w-3.5 h-3.5"></i>
                                    </template>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-[10px] uppercase tracking-wide px-2 py-1" style="letter-spacing:0.06em;"
                                    :class="statusColor(room.status)" x-text="statusLabel(room.status)"></span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5 text-gray-400">
                                    <button @click="openView(room)" title="View details"
                                        class="hover:text-[#0E1A2B] transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="openEdit(room)" title="Edit"
                                        class="hover:text-[#0E1A2B] transition-colors">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="openDeleteConfirm(room)" title="Delete"
                                        class="hover:text-[#9b1c1c] transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredRooms.length === 0">
                        <td colspan="8" class="px-5 py-20 text-center">
                            <i data-lucide="search-x" class="w-8 h-8 text-gray-300 mx-auto mb-3"></i>
                            <p class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B] italic mb-1"
                                style="font-weight:400;">No suites match your filters</p>
                            <p class="text-xs text-gray-400" style="font-weight:300;">Try adjusting the search or filter
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-3">
            <template x-for="room in paginatedRooms" :key="room.id">
                <div class="bg-white border border-[#e5ddd3] p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-16 h-12 overflow-hidden flex-shrink-0" @click="openImage(room)">
                            <img :src="room.image" :alt="room.name" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase tracking-widest text-[#B89C6E] mb-0.5"
                                style="letter-spacing:0.12em;" x-text="room.code"></p>
                            <p class="font-['Cormorant_Garamond'] text-base text-[#0E1A2B] truncate"
                                style="font-weight:500;" x-text="room.name"></p>
                        </div>
                        <span class="text-[10px] uppercase tracking-wide px-2 py-1 flex-shrink-0"
                            style="letter-spacing:0.06em;" :class="statusColor(room.status)"
                            x-text="statusLabel(room.status)"></span>
                    </div>

                    <div class="flex items-center justify-between text-[13px] text-gray-500 mb-3"
                        style="font-weight:300;">
                        <span x-text="viewLabel(room.view)"></span>
                        <span x-text="room.size + ' sqm · ' + room.guests + ' guests'"></span>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="font-['Cormorant_Garamond'] text-lg text-[#0E1A2B]" style="font-weight:600;"
                            x-text="formatPrice(room.price)"></p>
                        <div class="flex items-center gap-4 text-gray-400">
                            <button @click="openView(room)"><i data-lucide="eye" class="w-4 h-4"></i></button>
                            <button @click="openEdit(room)"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button @click="openDeleteConfirm(room)" class="hover:text-[#9b1c1c]"><i
                                    data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="filteredRooms.length === 0" class="text-center py-16 bg-white border border-[#e5ddd3]">
                <i data-lucide="search-x" class="w-8 h-8 text-gray-300 mx-auto mb-3"></i>
                <p class="font-['Cormorant_Garamond'] text-xl text-[#0E1A2B] italic mb-1" style="font-weight:400;">No
                    suites match your filters</p>
                <p class="text-xs text-gray-400" style="font-weight:300;">Try adjusting the search or filter</p>
            </div>
        </div>

        <div class="flex items-center justify-center md:justify-end gap-1.5 mt-6" x-show="filteredRooms.length > 0">
            <button @click="prevPage()" :disabled="currentPage === 1"
                class="w-8 h-8 flex items-center justify-center border border-[#e5ddd3] text-[#0E1A2B] disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#B89C6E] transition-colors">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
            </button>
            <template x-for="p in totalPages" :key="p">
                <button @click="currentPage = p" class="w-8 h-8 flex items-center justify-center text-xs"
                    :class="currentPage === p ? 'bg-[#0E1A2B] text-white' :
                        'border border-[#e5ddd3] text-[#0E1A2B] hover:border-[#B89C6E]'"
                    x-text="p"></button>
            </template>
            <button @click="nextPage()" :disabled="currentPage === totalPages"
                class="w-8 h-8 flex items-center justify-center border border-[#e5ddd3] text-[#0E1A2B] disabled:opacity-30 disabled:cursor-not-allowed hover:border-[#B89C6E] transition-colors">
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </button>
        </div>

        <x-modals.form />
        <x-modals.confirm />
        <x-modals.view-image />
        <x-modals.sidebar />
        <x-modals.upload />

    </div>

    @push('scripts')
        <script>
            function roomsPage() {
                return {
                    search: '',
                    filterView: 'all',
                    currentPage: 1,
                    perPage: 5,
                    rooms: [{
                            id: 1,
                            code: 'Suite 01',
                            name: 'Deluxe King',
                            view: 'city',
                            price: 65000,
                            size: 40,
                            guests: 2,
                            status: 'available',
                            amenities: ['wifi', 'tv', 'coffee', 'shield-check'],
                            image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=700&q=80',
                            images: ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=700&q=80'],
                            description: 'A refined city-facing suite with a plush king bed, warm oak finishes and a private workspace.'
                        },
                        {
                            id: 2,
                            code: 'Suite 02',
                            name: 'Premier Double',
                            view: 'pool',
                            price: 85000,
                            size: 52,
                            guests: 2,
                            status: 'available',
                            amenities: ['wifi', 'bath', 'coffee', 'wine'],
                            image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=700&q=80',
                            images: ['https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=700&q=80'],
                            description: 'Overlooking the infinity pool, this suite pairs relaxed luxury with a private soaking tub.'
                        },
                        {
                            id: 3,
                            code: 'Suite 03',
                            name: 'Executive Studio',
                            view: 'garden',
                            price: 110000,
                            size: 68,
                            guests: 3,
                            status: 'maintenance',
                            amenities: ['wifi', 'utensils', 'car', 'bath'],
                            image: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=700&q=80',
                            images: ['https://images.unsplash.com/photo-1590490360182-c33d57733427?w=700&q=80'],
                            description: 'A garden-facing studio with a full kitchenette, ideal for extended stays.'
                        },
                        {
                            id: 4,
                            code: 'Suite 04',
                            name: 'Penthouse Royal',
                            view: 'panoramic',
                            price: 250000,
                            size: 120,
                            guests: 4,
                            status: 'available',
                            amenities: ['wifi', 'star', 'wind', 'car'],
                            image: 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=700&q=80',
                            images: ['https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=700&q=80'],
                            description: 'Our signature penthouse with panoramic skyline views, private butler and terrace.'
                        },
                        {
                            id: 5,
                            code: 'Suite 05',
                            name: 'Classic Twin',
                            view: 'city',
                            price: 72000,
                            size: 44,
                            guests: 2,
                            status: 'occupied',
                            amenities: ['wifi', 'bath', 'coffee', 'tv'],
                            image: 'https://images.unsplash.com/photo-1560185007-cde436f6a4d0?w=700&q=80',
                            images: ['https://images.unsplash.com/photo-1560185007-cde436f6a4d0?w=700&q=80'],
                            description: 'Two elegant twin beds in a bright city-view room, perfect for friends or colleagues.'
                        }
                    ],
                    showFormModal: false,
                    formMode: 'create',
                    formData: {},
                    showConfirmModal: false,
                    confirmTitle: '',
                    confirmMessage: '',
                    confirmDanger: false,
                    confirmAction: null,
                    showImageModal: false,
                    activeImage: '',
                    activeImageCaption: '',
                    showDetailsDrawer: false,
                    selectedRoom: null,
                    showUploadModal: false,
                    isDragging: false,
                    uploadQueue: [],
                    mediaLibrary: [],
                    mediaLibraryLoading: false,
                    tempSelectedImages: [],
                    init() {
                        this.$nextTick(() => lucide.createIcons());
                        this.$watch('search', () => this.currentPage = 1);
                        this.$watch('filterView', () => this.currentPage = 1);
                    },
                    get filteredRooms() {
                        return this.rooms.filter(r => {
                            const q = this.search.toLowerCase();
                            const matchesSearch = r.name.toLowerCase().includes(q) || r.code.toLowerCase().includes(
                                q);
                            const matchesFilter = this.filterView === 'all' || r.view === this.filterView;
                            return matchesSearch && matchesFilter;
                        });
                    },
                    get totalPages() {
                        return Math.max(1, Math.ceil(this.filteredRooms.length / this.perPage));
                    },
                    get paginatedRooms() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.filteredRooms.slice(start, start + this.perPage);
                    },
                    prevPage() {
                        if (this.currentPage > 1) this.currentPage--;
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages) this.currentPage++;
                    },
                    formatPrice(v) {
                        return '₦' + Number(v || 0).toLocaleString();
                    },
                    viewLabel(v) {
                        return v.charAt(0).toUpperCase() + v.slice(1) + ' view';
                    },
                    statusLabel(s) {
                        return s.charAt(0).toUpperCase() + s.slice(1);
                    },
                    statusColor(s) {
                        if (s === 'available') return 'bg-[#2d6a4f]/10 text-[#2d6a4f]';
                        if (s === 'occupied') return 'bg-[#B89C6E]/20 text-[#8a6d3f]';
                        return 'bg-[#9b1c1c]/10 text-[#9b1c1c]';
                    },
                    openCreate() {
                        this.formMode = 'create';
                        this.formData = {
                            id: null,
                            code: 'Suite ' + String(this.rooms.length + 1).padStart(2, '0'),
                            name: '',
                            view: 'city',
                            price: '',
                            size: '',
                            guests: 1,
                            status: 'available',
                            images: [],
                            description: '',
                            amenities: []
                        };
                        this.showFormModal = true;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    openEdit(room) {
                        this.formMode = 'edit';
                        this.formData = {
                            ...room,
                            images: room.images ? [...room.images] : (room.image ? [room.image] : []),
                            amenities: [...room.amenities]
                        };
                        this.showFormModal = true;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeForm() {
                        this.showFormModal = false;
                    },
                    toggleAmenity(a) {
                        const i = this.formData.amenities.indexOf(a);
                        if (i > -1) this.formData.amenities.splice(i, 1);
                        else this.formData.amenities.push(a);
                    },
                    removeFormImage(url) {
                        this.formData.images = this.formData.images.filter(i => i !== url);
                    },
                    saveRoom() {
                        if (!this.formData.name || !this.formData.price || !this.formData.size) return;
                        this.formData.image = this.formData.images[0] || '';
                        if (this.formMode === 'create') {
                            this.formData.id = Date.now();
                            this.rooms.push({
                                ...this.formData
                            });
                        } else {
                            const idx = this.rooms.findIndex(r => r.id === this.formData.id);
                            if (idx > -1) this.rooms[idx] = {
                                ...this.formData
                            };
                        }
                        this.showFormModal = false;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    openView(room) {
                        this.selectedRoom = room;
                        this.showDetailsDrawer = true;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeDetails() {
                        this.showDetailsDrawer = false;
                    },
                    openImage(room) {
                        this.activeImage = room.image;
                        this.activeImageCaption = room.code + ' · ' + room.name;
                        this.showImageModal = true;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeImage() {
                        this.showImageModal = false;
                    },
                    openDeleteConfirm(room) {
                        this.confirmTitle = 'Delete this suite?';
                        this.confirmMessage = 'This will permanently remove "' + room.name +
                            '" from your room listings. This action cannot be undone.';
                        this.confirmDanger = true;
                        this.confirmAction = () => {
                            this.rooms = this.rooms.filter(r => r.id !== room.id);
                            if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
                        };
                        this.showConfirmModal = true;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    runConfirmAction() {
                        if (typeof this.confirmAction === 'function') this.confirmAction();
                        this.showConfirmModal = false;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    openUploadModal() {
                        this.tempSelectedImages = [...this.formData.images];
                        this.showUploadModal = true;
                        this.loadMediaLibrary();
                        this.$nextTick(() => lucide.createIcons());
                    },
                    closeUploadModal() {
                        this.showUploadModal = false;
                        this.isDragging = false;
                    },
                    loadMediaLibrary() {
                        this.mediaLibraryLoading = true;
                        fetch("{{ route('uploads.list') }}")
                            .then(r => r.json())
                            .then(data => {
                                this.mediaLibrary = data;
                                this.mediaLibraryLoading = false;
                                this.$nextTick(() => lucide.createIcons());
                            })
                            .catch(() => {
                                this.mediaLibraryLoading = false;
                            });
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
                        Array.from(fileList).forEach(file => this.uploadSingleFile(file));
                    },
                    uploadSingleFile(file) {
                        const id = Date.now() + Math.random();
                        this.uploadQueue.push({
                            id,
                            name: file.name,
                            status: 'uploading'
                        });

                        const payload = new FormData();
                        payload.append('image', file);

                        fetch("{{ route('uploads.store') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: payload
                            })
                            .then(r => r.json())
                            .then(data => {
                                const item = this.uploadQueue.find(u => u.id === id);
                                if (item) item.status = 'done';
                                this.mediaLibrary.unshift(data);
                                this.tempSelectedImages.push(data.url);
                                this.$nextTick(() => lucide.createIcons());
                                setTimeout(() => {
                                    this.uploadQueue = this.uploadQueue.filter(u => u.id !== id);
                                }, 700);
                            })
                            .catch(() => {
                                const item = this.uploadQueue.find(u => u.id === id);
                                if (item) item.status = 'error';
                                this.$nextTick(() => lucide.createIcons());
                            });
                    },
                    toggleSelectImage(url) {
                        const i = this.tempSelectedImages.indexOf(url);
                        if (i > -1) this.tempSelectedImages.splice(i, 1);
                        else this.tempSelectedImages.push(url);
                    },
                    confirmImageSelection() {
                        this.formData.images = [...this.tempSelectedImages];
                        this.showUploadModal = false;
                        this.$nextTick(() => lucide.createIcons());
                    },
                    removeUploadItem(id) {
                        this.uploadQueue = this.uploadQueue.filter(u => u.id !== id);
                    },
                }
            }
        </script>
    @endpush
@endsection
