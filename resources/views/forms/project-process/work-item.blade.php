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

                            <div class="tree-sub hidden" data-hazard="{{ $hazard->id }}">

                                {{-- RISKS --}}
                                <div class="section-title">Risks</div>

                                @foreach ($hazard->riskAssessments as $risk)
                                    <label class="tree-label">
                                        <input type="checkbox" class="risk" data-id="{{ $risk->id }}">
                                        {{ $risk->description }}
                                    </label>

                                    <div class="form-row hidden" data-risk="{{ $risk->id }}">
                                        <input type="number" placeholder="Probability"
                                            name="risks[{{ $risk->id }}][p]">
                                        <input type="number" placeholder="Severity"
                                            name="risks[{{ $risk->id }}][s]">
                                        <input readonly name="risks[{{ $risk->id }}][total]">
                                        <input readonly name="risks[{{ $risk->id }}][cat]">
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
                                            name="controls[{{ $c->id }}][p]">
                                        <input type="number" placeholder="Severity"
                                            name="controls[{{ $c->id }}][s]">
                                        <input readonly name="controls[{{ $c->id }}][total]">
                                        <input readonly name="controls[{{ $c->id }}][cat]">
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
        document.addEventListener('change', e => {

            if (e.target.classList.contains('work')) {
                document.querySelector(`[data-work="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
            }

            if (e.target.classList.contains('hazard')) {
                document.querySelector(`[data-hazard="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
            }

            if (e.target.classList.contains('risk')) {
                document.querySelector(`[data-risk="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
            }

            if (e.target.classList.contains('control')) {
                document.querySelector(`[data-control="${e.target.dataset.id}"]`)
                    .classList.toggle('hidden', !e.target.checked);
            }
        });

        document.addEventListener('input', e => {
            if (!e.target.closest('[data-risk],[data-control]')) return;

            const row = e.target.closest('[data-risk],[data-control]');
            const p = row.querySelector('[placeholder="Probability"]').value || 0;
            const s = row.querySelector('[placeholder="Severity"]').value || 0;
            const total = p * s;

            row.querySelector('[name$="[total]"]').value = total;
            row.querySelector('[name$="[cat]"]').value =
                total <= 1 ? 'Very Low' :
                total <= 5 ? 'Low' :
                total <= 10 ? 'Medium' :
                total <= 15 ? 'High' : 'Extreme';
        });
    </script>

</div>
