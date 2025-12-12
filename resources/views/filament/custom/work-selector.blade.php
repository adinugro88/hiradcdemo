<div class="space-y-2">
    @foreach($jobs as $job)
        <button
            class="w-full p-2 text-left border rounded hover:bg-gray-100"
            wire:click="
                {{ $set('job_id', $job->id) }};
                {{ $set('job_name', $job->name) }};
                $dispatch('close-modal', { id: 'pilih_pekerjaan' })
            "
        >
            {{ $job->name }}
        </button>
    @endforeach
</div>
