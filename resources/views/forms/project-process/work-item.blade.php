<div id="project-process-work-item-container" wire:ignore>
    <style>
        /* === TABLE LAYOUT === */
        .hiradc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
        }

        .hiradc-table th,
        .hiradc-table td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            vertical-align: top;
        }

        .hiradc-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }

        .hiradc-table input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 12px;
        }

        .hiradc-table input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .hiradc-table input[readonly] {
            background-color: #e5e7eb;
            color: #374151;
            cursor: not-allowed;
        }

        .w-small {
            width: 40px !important;
            text-align: center;
        }
        
        .w-medium {
             width: 80px !important;
             text-align: center;
        }

        .text-center {
            text-align: center;
        }
        
        .hidden-row {
            display: none;
        }
        
        /* === TREE LAYOUT === */
        #riskTree {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            font-size: 14px;
            margin-top: 30px;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .tree-work {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .tree-hazard {
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .tree-group {
            margin-left: 24px;
            padding-left: 12px;
            border-left: 3px solid #f59e0b;
        }

        .tree-sub {
            margin-left: 28px;
            margin-top: 8px;
        }

        .tree-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .tree-label input {
            width: 16px;
            height: 16px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin: 6px 0 12px 28px;
        }

        .form-row input {
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
        }
        
        .form-row input[readonly] {
            background-color: #e5e7eb;
            color: #374151;
            cursor: not-allowed;
        }
        
        .hidden {
            display: none;
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 12px 0;
        }
        
        .view-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #374151;
        }
        
        .process-header {
            font-size: 20px;
            font-weight: 700;
            padding: 12px 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 8px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    @php
        $works = \App\Models\Work::with(['hazards.riskAssessments', 'hazards.controlMeasures'])->get();
        
        // Get current record's process name if in edit mode
        $processName = null;
        if (isset($this) && method_exists($this, 'getRecord')) {
            $record = $this->getRecord();
            if ($record) {
                $processName = $record->process;
            }
        }
    @endphp

    {{-- TABLE VIEW --}}
    <div class="view-title">Table View (Preview & Edit)</div>
    <div class="overflow-x-auto">
        <table class="hiradc-table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kegiatan Pekerjaan</th>
                    <th rowspan="2">Bahaya</th>
                    <th colspan="5">Risiko Awal</th>
                    <th colspan="5">Risiko Sisa (Pengendalian)</th>
                </tr>
                <tr>
                    <th>Deskripsi Risiko</th>
                    <th class="w-small">P</th>
                    <th class="w-small">S</th>
                    <th class="w-small">V</th>
                    <th class="w-medium">C</th>
                    
                    
                    <th>Deskripsi Pengendalian</th>
                    <th class="w-small">P</th>
                    <th class="w-small">S</th>
                    <th class="w-small">V</th>
                    <th class="w-medium">C</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($works as $workIndex => $work)
                    @php
                        // Pre-calculate rows for this work
                        $workRows = [];
                        foreach ($work->hazards as $hazard) {
                            $risks = $hazard->riskAssessments;
                            $controls = $hazard->controlMeasures;
                            
                            // Only create rows if there's at least one risk OR one control
                            // Don't create empty placeholder rows
                            if ($risks->count() > 0 || $controls->count() > 0) {
                                $maxRows = max($risks->count(), $controls->count());
                                
                                for ($i = 0; $i < $maxRows; $i++) {
                                    $risk = $risks[$i] ?? null;
                                    $control = $controls[$i] ?? null;
                                    
                                    // Only add row if there's risk data OR control data
                                    if ($risk || $control) {
                                        $workRows[] = [
                                            'hazard' => $hazard,
                                            'risk' => $risk,
                                            'control' => $control,
                                            'isFirstForHazard' => $i === 0,
                                            'rowSpanHazard' => $maxRows
                                        ];
                                    }
                                }
                            }
                        }
                    @endphp

                    {{-- Loop through calculated rows --}}
                    @foreach ($workRows as $rowIndex => $row)
                        <tr class="work-row" data-work-id="{{ $work->id }}">
                            
                            {{-- WORK COLUMN (Rowspan for all work rows) --}}
                            @if ($rowIndex === 0)
                                <td rowspan="{{ count($workRows) }}" class="text-center">
                                    {{ $loop->parent->iteration }}
                                </td>
                                <td rowspan="{{ count($workRows) }}">
                                    {{ $work->name }}
                                </td>
                            @endif

                            {{-- HAZARD COLUMN (Rowspan per hazard) --}}
                            @if ($row['isFirstForHazard'])
                                <td rowspan="{{ $row['rowSpanHazard'] }}">
                                    {{ $row['hazard']->name }}
                                </td>
                            @endif

                            {{-- RISK COLUMNS --}}
                            @if ($row['risk'])
                                <td class="{{ $row['risk']->id }}">
                                    {{ $row['risk']->description }}
                                </td>
                                <td>
                                    <input type="number" class="risk-prob-table w-small" data-risk-id="{{ $row['risk']->id }}">
                                </td>
                                <td>
                                    <input type="number" class="risk-sev-table w-small" data-risk-id="{{ $row['risk']->id }}">
                                </td>
                                <td>
                                    <input readonly class="risk-tot-table w-small" data-risk-id="{{ $row['risk']->id }}">
                                </td>
                                <td>
                                    <input readonly class="risk-cat-table w-medium" data-risk-id="{{ $row['risk']->id }}">
                                </td>
                            @else
                                <td colspan="5" style="background:#f9f9f9;">-</td>
                            @endif

                            {{-- CONTROL COLUMNS --}}
                            @if ($row['control'])
                                <td>
                                    {{ $row['control']->basic_measure }}
                                </td>
                                <td>
                                    <input type="number" class="control-prob-table w-small" data-control-id="{{ $row['control']->id }}">
                                </td>
                                <td>
                                    <input type="number" class="control-sev-table w-small" data-control-id="{{ $row['control']->id }}">
                                </td>
                                <td>
                                    <input readonly class="control-tot-table w-small" data-control-id="{{ $row['control']->id }}">
                                </td>
                                <td>
                                    <input readonly class="control-cat-table w-medium" data-control-id="{{ $row['control']->id }}">
                                </td>
                            @else
                                <td colspan="5" style="background:#f9f9f9;">-</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TREE VIEW --}}
    <div id="riskTree">
        <div class="view-title">Checklist Input</div>
        
        @if($processName)
            <div class="process-header">
                📋 Process: {{ $processName }}
            </div>
        @endif

        @foreach ($works as $work)
            <div class="card">

                {{-- WORK --}}
                <label class="tree-label tree-work">
                    <input type="checkbox" class="work" data-id="{{ $work->id }}">
                    {{ $work->name }}
                </label>

                <div class="tree-group hidden" data-work="{{ $work->id }}">

                    @foreach ($work->hazards as $hazard)
                        <div class="tree-hazard">

                            {{-- HAZARD --}}
                            <label class="tree-label">
                                <input type="checkbox" class="hazard" data-id="{{ $hazard->id }}">
                                {{ $hazard->name }}
                            </label>

                            <div class="tree-sub" data-hazard="{{ $hazard->id }}">

                                {{-- RISKS --}}
                                <div class="section-title">Risks</div>

                                @foreach ($hazard->riskAssessments as $risk)
                                    <label class="tree-label">
                                        <input type="checkbox" class="risk" data-id="{{ $risk->id }}">
                                        {{ $risk->description }}
                                    </label>

                                    <div class="form-row hidden" data-risk="{{ $risk->id }}">
                                        <input type="number" placeholder="Prob"
                                            data-risk-id="{{ $risk->id }}" class="risk-prob-tree">
                                        <input type="number" placeholder="Sev" data-risk-id="{{ $risk->id }}"
                                            class="risk-sev-tree">
                                        <input style="" readonly data-risk-id="{{ $risk->id }}" class="risk-tot-tree">
                                        <input readonly data-risk-id="{{ $risk->id }}" class="risk-cat-tree">
                                    </div>
                                @endforeach

                                <hr>

                                {{-- CONTROL --}}
                                <div class="section-title">Control Measures</div>

                                @foreach ($hazard->controlMeasures as $c)
                                    <label class="tree-label">
                                        <input type="checkbox" class="control" data-id="{{ $c->id }}">
                                        {{ $c->basic_measure }}
                                    </label>

                                    <div class="form-row hidden" data-control="{{ $c->id }}">
                                        <input type="number" placeholder="Prob"
                                            data-control-id="{{ $c->id }}" class="control-prob-tree">
                                        <input type="number" placeholder="Sev"
                                            data-control-id="{{ $c->id }}" class="control-sev-tree">
                                        <input readonly data-control-id="{{ $c->id }}" class="control-tot-tree">
                                        <input readonly data-control-id="{{ $c->id }}" class="control-cat-tree">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach

    </div>

    <script>
        // Store collected data
        @php
            $initialData = $get('_risk_control_data');
            if (is_string($initialData)) {
                $initialData = json_decode($initialData, true);
            }
            if (!is_array($initialData)) {
                $initialData = [
                    'works' => [],
                    'hazards' => [],
                    'risks' => [],
                    'controls' => []
                ];
            }
        @endphp

        let riskControlData = @json($initialData);

        // Ensure proper structure
        riskControlData.works = riskControlData.works || {};
        riskControlData.hazards = riskControlData.hazards || {};
        riskControlData.risks = riskControlData.risks || {};
        riskControlData.controls = riskControlData.controls || {};

        function populateForm() {
            // SYNC TABLE & TREE FROM DATA
            
            // 1. WORKS & UI VISIBILITY (Based on Tree Checkboxes)
            // Table Logic: Show rows if work is checked
            document.querySelectorAll('.work-row').forEach(row => {
                 const workId = row.dataset.workId;
                 if (riskControlData.works[workId]) {
                     row.classList.remove('hidden-row');
                 } else {
                     row.classList.add('hidden-row');
                 }
            });

            // Tree Logic: Check checkboxes and show groups
            Object.keys(riskControlData.works).forEach(id => {
                if (riskControlData.works[id]) {
                    const el = document.querySelector(`.work[data-id="${id}"]`);
                    if (el) el.checked = true;
                    
                    const group = document.querySelector(`[data-work="${id}"]`);
                    if (group) group.classList.remove('hidden');
                }
            });
            
            // Hazards visibility
             Object.keys(riskControlData.hazards).forEach(id => {
                if (riskControlData.hazards[id]) {
                    const el = document.querySelector(`.hazard[data-id="${id}"]`);
                    if (el) el.checked = true;
                }
            });

            // 2. RISKS VALUES & CHECKBOXES
            Object.keys(riskControlData.risks).forEach(id => {
                const data = riskControlData.risks[id];
                if (data && data.checked) {
                    // Tree Checkbox
                    const cbTree = document.querySelector(`.risk[data-id="${id}"]`);
                    if (cbTree) {
                        cbTree.checked = true;
                        const row = document.querySelector(`[data-risk="${id}"]`);
                        if (row) row.classList.remove('hidden');
                    }

                    // Update ALL inputs (Table + Tree)
                    updateInputs('risk', id, data);
                }
            });

            // 3. CONTROLS VALUES & CHECKBOXES
            Object.keys(riskControlData.controls).forEach(id => {
                const data = riskControlData.controls[id];
                if (data && data.checked) {
                    const cbTree = document.querySelector(`.control[data-id="${id}"]`);
                    if (cbTree) {
                        cbTree.checked = true;
                        const row = document.querySelector(`[data-control="${id}"]`);
                        if (row) row.classList.remove('hidden');
                    }

                    updateInputs('control', id, data);
                }
            });
        }
        
        function updateInputs(type, id, data) {
            // Helper to update both Tree and Table inputs for a given ID
            const selectors = type === 'risk' 
                ? [`.risk-prob-table`, `.risk-sev-table`, `.risk-tot-table`, `.risk-cat-table`, `.risk-prob-tree`, `.risk-sev-tree`, `.risk-tot-tree`, `.risk-cat-tree`]
                : [`.control-prob-table`, `.control-sev-table`, `.control-tot-table`, `.control-cat-table`, `.control-prob-tree`, `.control-sev-tree`, `.control-tot-tree`, `.control-cat-tree`];

            // Mapping data keys to selector indices
            // 0/4: prob, 1/5: sev, 2/6: total, 3/7: cat
            
            const els = document.querySelectorAll(`[data-${type}-id="${id}"]`);
            els.forEach(el => {
                if(el.classList.contains(`${type}-prob-table`) || el.classList.contains(`${type}-prob-tree`)) el.value = data.probability;
                if(el.classList.contains(`${type}-sev-table`) || el.classList.contains(`${type}-sev-tree`)) el.value = data.severity;
                if(el.classList.contains(`${type}-tot-table`) || el.classList.contains(`${type}-tot-tree`)) el.value = data.total;
                if(el.classList.contains(`${type}-cat-table`) || el.classList.contains(`${type}-cat-tree`)) el.value = data.category;
            });
        }

        // --- EVENT LISTENERS ---

        // Checkbox Changes (Tree View primarily controls visibility)
        document.addEventListener('change', e => {
            if (e.target.type !== 'checkbox') return;

            if (e.target.classList.contains('work')) {
                const id = e.target.dataset.id;
                const checked = e.target.checked;
                riskControlData.works[id] = checked;
                
                // Toggle Tree Visibility
                document.querySelector(`[data-work="${id}"]`).classList.toggle('hidden', !checked);
                
                // Toggle Table Visibility
                document.querySelectorAll(`.work-row[data-work-id="${id}"]`).forEach(row => {
                    if (checked) row.classList.remove('hidden-row');
                    else row.classList.add('hidden-row');
                });
            }

            if (e.target.classList.contains('hazard')) {
                const id = e.target.dataset.id;
                const checked = e.target.checked;
                riskControlData.hazards[id] = checked;

                // Propagate to child risks/controls in Tree
                const container = document.querySelector(`[data-hazard="${id}"]`);
                if (container) {
                    container.querySelectorAll('.risk, .control').forEach(cb => {
                         cb.checked = checked;
                         cb.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                }
            }

            if (e.target.classList.contains('risk')) {
                const id = e.target.dataset.id;
                const checked = e.target.checked;
                
                // Init data object if missing
                if (!riskControlData.risks[id]) riskControlData.risks[id] = {};
                riskControlData.risks[id].checked = checked;

                // Toggle Tree Inputs
                document.querySelector(`[data-risk="${id}"]`).classList.toggle('hidden', !checked);
            }

            if (e.target.classList.contains('control')) {
                const id = e.target.dataset.id;
                const checked = e.target.checked;
                
                if (!riskControlData.controls[id]) riskControlData.controls[id] = {};
                riskControlData.controls[id].checked = checked;

                // Toggle Tree Inputs
                document.querySelector(`[data-control="${id}"]`).classList.toggle('hidden', !checked);
            }

            updateHiddenInput();
        });

        // Input Changes (Values)
        document.addEventListener('input', e => {
            const el = e.target;
            // Identify if it's a risk or control input
            let type = null;
            let id = null;

            if (el.dataset.riskId) {
                type = 'risk';
                id = el.dataset.riskId;
            } else if (el.dataset.controlId) {
                type = 'control';
                id = el.dataset.controlId;
            }

            if (!type || !id) return;

            // Recalculate
            // Note: We need to find the "partner" p/s value. 
            // Since we could be editing in Table or Tree, we look for the set of inputs in the document for this ID.
            // We'll take the value from the *event target* as the source of truth for its field (prob or sev), 
            // and find the *other* field anywhere.
            
            // Simplest way: Get values from data if exists, overwrite with current target value, then recalc.
            let currentData = type === 'risk' ? (riskControlData.risks[id] || {}) : (riskControlData.controls[id] || {});
            
            let p = parseInt(currentData.probability) || 0;
            let s = parseInt(currentData.severity) || 0;

            if (el.classList.contains(`${type}-prob-table`) || el.classList.contains(`${type}-prob-tree`)) {
                p = parseInt(el.value) || 0;
            }
            if (el.classList.contains(`${type}-sev-table`) || el.classList.contains(`${type}-sev-tree`)) {
                s = parseInt(el.value) || 0;
            }

            const total = p * s;
            const category = calculateCategory(total);

            // Update Data
            const newData = {
                checked: true, // Auto-check if editing
                probability: p,
                severity: s,
                total: total,
                category: category
            };

            if (type === 'risk') riskControlData.risks[id] = newData;
            else riskControlData.controls[id] = newData;

            // Update UI (All inputs for this ID)
            updateInputs(type, id, newData);

            // Update Hidden Input
            updateHiddenInput();
        });

        function calculateCategory(total) {
            if (total <= 1) return 'Very Low';
            if (total <= 5) return 'Low';
            if (total <= 10) return 'Medium';
            if (total <= 15) return 'High';
            return 'Extreme';
        }

        function updateHiddenInput() {
            const json = JSON.stringify(riskControlData);
            
            // Dispatch event to update the specific Livewire property
            // We find the container first to get the closest Livewire component
            const container = document.getElementById('project-process-work-item-container');
            if (container) {
                const element = container.closest('[wire\\:id]');
                if (element) {
                    const componentId = element.getAttribute('wire:id');
                    const component = Livewire.find(componentId);
                    
                    if (component) {
                        // Start of Selection
                        // We need to use $wire.set() strictly for the property name
                        // But since we are outside the Alpine context, we use the component object
                        // Note: 'data._risk_control_data' matches the state path in Filament
                        component.set('data._risk_control_data', json);
                    }
                }
            }
        }

        window.addEventListener('risk-data-updated', event => {
            let newData = event.detail.data;
            if (typeof newData === 'string') {
                newData = JSON.parse(newData);
            }
            if (!newData) return;

            // Reset UI
            document.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);
            document.querySelectorAll('input[type="number"]').forEach(el => el.value = '');
            document.querySelectorAll('input[readonly]').forEach(el => el.value = '');
            
            document.querySelectorAll('.tree-group, .form-row').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.work-row').forEach(el => el.classList.add('hidden-row'));

            // Update data
            riskControlData = newData;
            
            // Ensure structure
            if (!riskControlData.works) riskControlData.works = {};
            if (!riskControlData.hazards) riskControlData.hazards = {};
            if (!riskControlData.risks) riskControlData.risks = {};
            if (!riskControlData.controls) riskControlData.controls = {};

            populateForm();
            updateHiddenInput();
        });

        document.addEventListener('DOMContentLoaded', () => {
             populateForm();
        });
        
        document.addEventListener('livewire:navigated', () => {
             populateForm();
        });

        // Run immediately in case events already fired or we are just injected
        populateForm();
    </script>
</div>
