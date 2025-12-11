-- --------------------------------------------------------
--  PROJECTS
-- --------------------------------------------------------
INSERT INTO projects (id, department, name, document_number, form_code, revision, page_info, created_at, updated_at)
VALUES
(1, 'OPERATION', 'AMANKILA PROJECT', '0143', 'FM/HSE-1/11', '01', 'Hal: 1 Dari 1', DATETIME('now'), DATETIME('now'));


-- --------------------------------------------------------
--  PROJECT PROCESSES
-- --------------------------------------------------------
INSERT INTO project_processes (id, project_id, process, created_at, updated_at)
VALUES
(1, 1, 'Pekerjaan Kelistrikan Temporary', DATETIME('now'), DATETIME('now'));


-- --------------------------------------------------------
--  WORKS
-- --------------------------------------------------------
INSERT INTO works (id, name, description, created_at, updated_at)
VALUES
(1, 'Instalasi kabel (wiring installation)', 'Kegiatan instalasi kabel listrik.', DATETIME('now'), DATETIME('now')),
(2, 'Perbaikan listrik (electricity repair)', 'Kegiatan perbaikan listrik.', DATETIME('now'), DATETIME('now'));


-- --------------------------------------------------------
--  HAZARDS
-- --------------------------------------------------------
INSERT INTO hazards (id, work_id, name, created_at, updated_at)
VALUES
(1, 1, 'Tersetrum (electrocuted)', DATETIME('now'), DATETIME('now')),
(2, 2, 'Tersetrum (electrocuted)', DATETIME('now'), DATETIME('now'));


-- --------------------------------------------------------
--  REGULATIONS
-- --------------------------------------------------------
INSERT INTO regulations (id, hazard_id, title, reference_number, description, created_at, updated_at)
VALUES
(1, 1, 'Permenaker No 12 Tahun 2015', '12/2015', 'Regulasi keselamatan kelistrikan.', DATETIME('now'), DATETIME('now')),
(2, 2, 'Permenaker No 12 Tahun 2015', '12/2015', 'Regulasi keselamatan kelistrikan.', DATETIME('now'), DATETIME('now'));


-- --------------------------------------------------------
--  RISK ASSESSMENTS
-- --------------------------------------------------------
INSERT INTO risk_assessments (
    id, hazard_id, description,
    probability_before, severity_before, total_before, category_before,
    probability_after, severity_after, total_after, category_after,
    created_at, updated_at
) VALUES
(
    1, 1,
    'Risiko tersetrum dapat menyebabkan fatality.',
    3, 5, 15, 'Tinggi',
    2, 2, 4, 'Kecil',
    DATETIME('now'), DATETIME('now')
),
(
    2, 2,
    'Risiko tersetrum dapat menyebabkan fatality.',
    3, 5, 15, 'Tinggi',
    2, 2, 4, 'Kecil',
    DATETIME('now'), DATETIME('now')
);


-- --------------------------------------------------------
--  CONTROL MEASURES
-- --------------------------------------------------------
INSERT INTO control_measures (
    id, hazard_id, basic_measure, opportunity_measure, advanced_measure, control_hierarchy,
    created_at, updated_at
) VALUES
(
    1, 1,
    'Teknisi bersertifikat; Pelatihan K3 kelistrikan; Penggunaan APD khusus listrik.',
    'Pelatihan K3 lanjutan.',
    'Implementasi LOTO; APD khusus listrik; Pembuatan Prosedur dan JSA kelistrikan.',
    'Administrative Control, PPE',
    DATETIME('now'), DATETIME('now')
),
(
    2, 2,
    'Teknisi bersertifikat; Pelatihan K3 kelistrikan; Penggunaan APD khusus listrik.',
    'Pelatihan K3 lanjutan.',
    'Implementasi LOTO; APD khusus listrik; Pembuatan Prosedur dan JSA kelistrikan.',
    'Administrative Control, PPE',
    DATETIME('now'), DATETIME('now')
);
