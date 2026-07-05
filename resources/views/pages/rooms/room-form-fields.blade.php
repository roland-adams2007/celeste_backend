<div>
    <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Room Number</label>
    <input type="text" x-model="roomFormData.room_number" required placeholder="e.g. 204"
        class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Floor</label>
        <input type="text" x-model="roomFormData.floor" required placeholder="e.g. 2"
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
    </div>
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-[#6b7280] mb-1.5">Status</label>
        <select x-model="roomFormData.status" required
            class="w-full border border-[#e5ddd3] px-3 py-2 text-sm focus:outline-none focus:border-[#B89C6E]">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="reserved">Reserved</option>
            <option value="cleaning">Cleaning</option>
            <option value="maintenance">Maintenance</option>
        </select>
    </div>
</div>