<div class="flex gap-2 justify-center">
    {{-- tombol Edit --}}
    <button
        type="button"
        class="text-primary-600 hover:text-primary-800"
        x-on:click.stop="
            // index baris sekarang pada $parent.$index (di konteks repeater)
            $wire.call('openEditStepModal', $parent.__index)
        "
        title="Edit"
    >
        ✏️
    </button>

    {{-- tombol Delete (hapus baris) --}}
    <button
        type="button"
        class="text-danger-600 hover:text-danger-800"
        x-on:click.stop="
            $wire.call('deleteStep', $parent.__index)
        "
        title="Hapus"
    >
        🗑
    </button>
</div>
