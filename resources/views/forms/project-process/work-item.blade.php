<div>

    <style>
        /* === TREE LAYOUT === */
        #riskTree {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            font-size: 14px;
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
            background: #f3f4f6;
            color: #374151;
        }

        .hidden {
            display: none;
        }

        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 12px 0;
        }
    </style>

    @php
        $works = \App\Models\Work::with(['hazards.riskAssessments', 'hazards.controlMeasures'])->get();
    @endphp

    <div id="riskTree">

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
                                        <input type="number" placeholder="Probability"
                                            data-risk-id="{{ $risk->id }}" class="risk-probability">
                                        <input type="number" placeholder="Severity" data-risk-id="{{ $risk->id }}"
                                            class="risk-severity">
                                        <input readonly data-risk-id="{{ $risk->id }}" class="risk-total">
                                        <input readonly data-risk-id="{{ $risk->id }}" class="risk-category">
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
                                        <input type="number" placeholder="Probability"
                                            data-control-id="{{ $c->id }}" class="control-probability">
                                        <input type="number" placeholder="Severity"
                                            data-control-id="{{ $c->id }}" class="control-severity">
                                        <input readonly data-control-id="{{ $c->id }}" class="control-total">
                                        <input readonly data-control-id="{{ $c->id }}" class="control-category">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach

    </div>

    <!-- Hidden input to store collected data -->
    {{-- <input type="hidden" id="riskControlDataInput" name="_risk_control_data"> --}}
    {{-- <input type="hidden" id="riskControlDataInput" wire:model.defer="_risk_control_data" /> --}}
    <input type="hidden" id="riskControlDataInput">

    <script>
        // Store collected data
        let riskControlData = {
            works: {},
            hazards: {},
            risks: {},
            controls: {}
        };

        document.addEventListener('change', e => {

            if (e.target.classList.contains('work')) {
                document.querySelector(`[data-work="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
                const id = e.target.dataset.id;
                riskControlData.works[id] = e.target.checked;
            }

            if (e.target.classList.contains('hazard')) {
                const hazardCheckbox = e.target;
                const hazardId = hazardCheckbox.dataset.id;
                const hazardContainer = document.querySelector(`[data-hazard="${hazardId}"]`);

                // Toggle hazard container visibility
                // hazardContainer.classList.toggle('hidden', !hazardCheckbox.checked);

                // Auto-check/uncheck all risks and controls within this hazard
                hazardContainer.querySelectorAll('.risk, .control').forEach(checkbox => {
                    checkbox.checked = hazardCheckbox.checked;
                    checkbox.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                });

                const id = e.target.dataset.id;
                riskControlData.hazards[id] = e.target.checked;
            }

            // if (e.target.classList.contains('hazard')) {
            //     document.querySelector(`[data-hazard="${e.target.dataset.id}"]`)
            //         .classList.toggle('hidden', !e.target.checked);
            // }

            if (e.target.classList.contains('risk')) {
                document.querySelector(`[data-risk="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
                const id = e.target.dataset.id;
                riskControlData.risks[id] = riskControlData.risks[id] || {};
                riskControlData.risks[id].checked = e.target.checked;
            }

            if (e.target.classList.contains('control')) {
                document.querySelector(`[data-control="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
                const id = e.target.dataset.id;
                riskControlData.controls[id] = riskControlData.controls[id] || {};
                riskControlData.controls[id].checked = e.target.checked;
            }

            // Auto-save data
            updateHiddenInput();
        });

        document.addEventListener('input', e => {
            const row = e.target.closest('[data-risk],[data-control]');
            if (!row) return;

            if (e.target.classList.contains('risk-probability') ||
                e.target.classList.contains('risk-severity')) {

                const riskId = e.target.dataset.riskId;
                const p = row.querySelector('.risk-probability').value || 0;
                const s = row.querySelector('.risk-severity').value || 0;
                const total = p * s;

                row.querySelector('.risk-total').value = total;
                row.querySelector('.risk-category').value = calculateCategory(total);

                // Update data object
                riskControlData.risks[riskId] = {
                    ...(riskControlData.risks[riskId] || {}),
                    probability: parseInt(p) || 0,
                    severity: parseInt(s) || 0,
                    total: total,
                    category: calculateCategory(total)
                };

            }

            if (e.target.classList.contains('control-probability') ||
                e.target.classList.contains('control-severity')) {

                const controlId = e.target.dataset.controlId;
                const p = row.querySelector('.control-probability').value || 0;
                const s = row.querySelector('.control-severity').value || 0;
                const total = p * s;

                row.querySelector('.control-total').value = total;
                row.querySelector('.control-category').value = calculateCategory(total);

                // Update data object
                riskControlData.controls[controlId] = {
                    ...(riskControlData.controls[controlId] || {}),
                    probability: parseInt(p) || 0,
                    severity: parseInt(s) || 0,
                    total: total,
                    category: calculateCategory(total)
                };
            }

            // Update hidden input
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

            // Cari form Filament (pasti ada)
            const form = document.querySelector('form[wire\\:submit]');

            if (!form) return;

            const wireId = form.getAttribute('wire:id');

            Livewire.find(wireId).set('_risk_control_data', json);
        }

        // Initialize hidden input
        document.addEventListener('DOMContentLoaded', updateHiddenInput);
        updateHiddenInput();
    </script>

</div>
