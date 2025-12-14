@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<style>
    .jsa-table-wrapper {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        overflow: hidden;
        background: white;
    }

    .jsa-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .jsa-table thead {
        background: linear-gradient(to bottom, #1f2937, #374151);
        color: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .jsa-table thead th {
        padding: 12px 14px;
        text-align: left;
        font-weight: 600;
        border-right: 1px solid #4b5563;
        white-space: nowrap;
    }

    .jsa-table thead th:last-child {
        border-right: none;
    }

    .jsa-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.15s ease-in-out;
    }

    .jsa-table tbody tr:hover {
        background-color: #f3f4f6;
    }

    .jsa-table tbody tr:last-child {
        border-bottom: none;
    }

    .jsa-table tbody td {
        padding: 12px 14px;
        border-right: 1px solid #e5e7eb;
        vertical-align: top;
    }

    .jsa-table tbody td:last-child {
        border-right: none;
    }

    .jsa-table tbody td.no-col {
        text-align: center;
        font-weight: 500;
        color: #1f2937;
        width: 50px;
    }

    .jsa-table tbody td.text-muted {
        color: #9ca3af;
        font-style: italic;
    }

    .jsa-table .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .jsa-table .badge-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .jsa-table .badge-success {
        background-color: #dcfce7;
        color: #166534;
    }

    .jsa-table-actions {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .jsa-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        white-space: nowrap;
    }

    .jsa-btn-edit {
        background-color: #24a5fb;
        color: white;
    }

    .jsa-btn-edit:hover {
        background-color: #108adb;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.4);
    }

    .jsa-btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .jsa-btn-delete:hover {
        background-color: #dc2626;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.4);
    }

    .jsa-empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #9ca3af;
    }

    .jsa-empty-state p {
        margin: 0;
        font-style: italic;
    }

    .jsa-table-scroll {
        max-height: 600px;
        overflow-y: auto;
    }

    .jsa-input-field {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease-in-out;
    }

    .jsa-input-field:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .jsa-input-field::placeholder {
        color: #d1d5db;
    }

    /* Custom Modal Styles */
    .jsa-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .jsa-modal-overlay.active {
        display: flex;
    }

    .jsa-modal {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .jsa-modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .jsa-modal-header h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }

    .jsa-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        transition: color 0.2s;
    }

    .jsa-modal-close:hover {
        color: #1f2937;
    }

    .jsa-modal-body {
        padding: 20px;
    }

    .jsa-form-group {
        margin-bottom: 20px;
    }

    .jsa-form-group:last-child {
        margin-bottom: 0;
    }

    .jsa-form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #1f2937;
        font-size: 0.875rem;
    }

    .jsa-form-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-family: inherit;
    }

    .jsa-form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .jsa-form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .jsa-modal-footer {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .jsa-modal-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .jsa-modal-btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .jsa-modal-btn-primary:hover {
        background-color: #2563eb;
    }

    .jsa-modal-btn-secondary {
        background-color: #e5e7eb;
        color: #1f2937;
    }

    .jsa-modal-btn-secondary:hover {
        background-color: #d1d5db;
    }
</style>

<div class="jsa-table-wrapper">
    <div class="jsa-table-scroll">
        <table class="jsa-table">
            <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 200px;">Jenis Pekerjaan</th>
                <th style="width: 280px;">Hazard - Risk</th>
                <th style="width: 280px;">Control Measure</th>
                <th style="width: 120px;">PIC</th>
                <th style="width: 120px;">Target</th>
                <th style="width: 130px; text-align: center;">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($steps as $i => $step)
                <tr>
                    <td class="no-col">{{ $i + 1 }}</td>
                    <td>
                        <span class="text-gray-800">{{ $step['work_sequence'] ?? '' }}</span>
                    </td>
                    <td>
                        <span class="text-gray-700 whitespace-pre-line block">{{ $step['risk_analysis'] ?? '' }}</span>
                    </td>
                    <td>
                        <span class="text-gray-700 whitespace-pre-line block">{{ $step['risk_control'] ?? '' }}</span>
                    </td>
                    <td>
                        <input
                            type="text"
                            class="jsa-input-field"
                            wire:model.blur="data.steps.{{ $i }}.pic"
                            placeholder="Masukkan PIC"
                        />
                    </td>
                    <td>
                        <input
                            type="date"
                            class="jsa-input-field"
                            wire:model.blur="data.steps.{{ $i }}.target_date"
                            style="cursor: pointer;"
                        />
                    </td>
                    <td>
                        <div class="jsa-table-actions">
                            <button
                                type="button"
                                class="jsa-btn jsa-btn-edit"
                                onclick="openEditModal({{ $i }}, {{ json_encode($step) }})"
                                title="Edit Pekerjaan"
                            >
                                 Edit
                            </button>
                            <button
                                type="button"
                                class="jsa-btn jsa-btn-delete"
                                onclick="confirmDelete({{ $i }})"
                                title="Hapus baris"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="jsa-empty-state">
                            <p>📋 Belum ada risiko pekerjaan. Gunakan tombol di atas untuk menambah data.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Custom Edit Modal -->
<div class="jsa-modal-overlay" id="editModalOverlay">
    <div class="jsa-modal">
        <div class="jsa-modal-header">
            <h3>Edit Data Pekerjaan</h3>
            <button class="jsa-modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="jsa-modal-body">
            <div class="jsa-form-group">
                <label class="jsa-form-label">Jenis Pekerjaan</label>
                <input
                    type="text"
                    id="workSequenceInput"
                    class="jsa-form-input"
                    placeholder="Masukkan jenis pekerjaan"
                />
            </div>
            <div class="jsa-form-group">
                <label class="jsa-form-label">Hazard - Risk</label>
                <textarea
                    id="riskAnalysisInput"
                    class="jsa-form-input jsa-form-textarea"
                    placeholder="Masukkan hazard dan risk"
                ></textarea>
            </div>
            <div class="jsa-form-group">
                <label class="jsa-form-label">Control Measure</label>
                <textarea
                    id="riskControlInput"
                    class="jsa-form-input jsa-form-textarea"
                    placeholder="Masukkan control measure"
                ></textarea>
            </div>
        </div>
        <div class="jsa-modal-footer">
            <button class="jsa-modal-btn jsa-modal-btn-secondary" onclick="closeEditModal()">
                Batal
            </button>
            <button class="jsa-modal-btn jsa-modal-btn-primary" onclick="saveEditModal()">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentEditIndex = null;

    function openEditModal(index, stepData) {
        currentEditIndex = index;
        document.getElementById('workSequenceInput').value = stepData.work_sequence || '';
        document.getElementById('riskAnalysisInput').value = stepData.risk_analysis || '';
        document.getElementById('riskControlInput').value = stepData.risk_control || '';
        document.getElementById('editModalOverlay').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModalOverlay').classList.remove('active');
        currentEditIndex = null;
    }

    function saveEditModal() {
        if (currentEditIndex === null) {
            return;
        }

        const workSequence = document.getElementById('workSequenceInput').value.trim();
        const riskAnalysis = document.getElementById('riskAnalysisInput').value.trim();
        const riskControl = document.getElementById('riskControlInput').value.trim();

        if (!workSequence || !riskAnalysis || !riskControl) {
            Swal.fire({
                title: 'Error',
                text: 'Semua field harus diisi!',
                icon: 'error',
                confirmButtonColor: '#3b82f6',
            });
            return;
        }

        // Update data via Livewire
        @this.updateStepData(currentEditIndex, {
            work_sequence: workSequence,
            risk_analysis: riskAnalysis,
            risk_control: riskControl,
        });

        closeEditModal();
    }

    function confirmDelete(index) {
        Swal.fire({
            title: 'Hapus Baris?',
            text: 'Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteStep(index);
            }
        });
    }

    // Close modal saat klik outside
    document.getElementById('editModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endpush
