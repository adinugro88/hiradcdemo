-- --------------------------------------------------------
--  PROJECTS
-- --------------------------------------------------------
INSERT INTO projects (
    id, department, name, document_number, form_code, revision, page_info, created_at, updated_at
)
VALUES
(1, 'OPERATION', 'AMANKILA PROJECT', '0143', 'FM/HSE-1/11', '01', 'Hal: 1 Dari 1', NOW(), NOW());


-- --------------------------------------------------------
--  PROJECT PROCESSES
-- --------------------------------------------------------
INSERT INTO project_processes (id, project_id, process, created_at, updated_at)
VALUES
(1, 1, 'Pekerjaan Kelistrikan Temporary', NOW(), NOW());


-- --------------------------------------------------------
--  WORKS
-- --------------------------------------------------------
INSERT INTO works (id, name, description, created_at, updated_at)
VALUES
(1, 'Instalasi kabel (wiring installation)', 'Kegiatan instalasi kabel listrik.', NOW(), NOW()),
(2, 'Perbaikan listrik (electricity repair)', 'Kegiatan perbaikan listrik.', NOW(), NOW());


-- --------------------------------------------------------
--  HAZARDS
-- --------------------------------------------------------
INSERT INTO hazards (id, work_id, name, control_hierarchy, created_at, updated_at)
VALUES
(1, 1, 'Tersetrum (electrocuted)', 'Administrative control, PPE', NOW(), NOW()),
(2, 2, 'Tersetrum (electrocuted)', 'Administrative control, PPE', NOW(), NOW());


-- --------------------------------------------------------
--  REGULATIONS
-- --------------------------------------------------------
INSERT INTO regulations (id, hazard_id, title, reference_number, description, created_at, updated_at)
VALUES
(1, 1, 'Permenaker No 12 Tahun 2015', '12/2015', 'Regulasi keselamatan kelistrikan.', NOW(), NOW()),
(2, 2, 'Permenaker No 12 Tahun 2015', '12/2015', 'Regulasi keselamatan kelistrikan.', NOW(), NOW());


-- --------------------------------------------------------
--  RISK ASSESSMENTS
-- --------------------------------------------------------
INSERT INTO risk_assessments (
    id, hazard_id, description,
    probability_before, severity_before, total_before, category_before,
    probability_after, severity_after, total_after, category_after,
    created_at, updated_at
)
VALUES
(
    1, 1,
    'Risiko tersetrum dapat menyebabkan fatality.',
    3, 5, 15, 'Tinggi',
    2, 2, 4, 'Kecil',
    NOW(), NOW()
),
(
    2, 2,
    'Risiko tersetrum dapat menyebabkan fatality.',
    3, 5, 15, 'Tinggi',
    2, 2, 4, 'Kecil',
    NOW(), NOW()
);

INSERT INTO opportunities (id, name, description, created_at, updated_at)
VALUES
(1, 'Pelatihan K3 Listrik Lanjutan', 'Pelatihan lanjutan untuk meningkatkan kesadaran dan keterampilan K3 listrik.', NOW(), NOW());


INSERT INTO control_measures (id, hazard_id, basic_measure, opportunity_id, advanced_measure, created_at, updated_at)
VALUES
(1, 1, 'Teknisi bersertifikat', 1, NULL, NOW(), NOW()),
(2, 1, 'Pelatihan K3 kelistrikan', 1, NULL, NOW(), NOW()),
(3, 1, 'Penggunaan APD khusus listrik.', 1, NULL, NOW(), NOW()),
(4, 1, 'Implementasi LOTO', NULL, 'Pembuatan Prosedur dan JSA kelistrikan.', NOW(), NOW()),
(5, 2, 'Teknisi bersertifikat', 1, NULL, NOW(), NOW()),
(6, 2, 'Pelatihan K3 kelistrikan', 1, NULL, NOW(), NOW()),
(7, 2, 'Penggunaan APD khusus listrik.', 1, NULL, NOW(), NOW()),
(8, 2, 'Implementasi LOTO', NULL, 'Pembuatan Prosedur dan JSA kelistrikan.', NOW(), NOW());

-- --------------------------------------------------------
--  WORK PROCESSES
-- --------------------------------------------------------
INSERT INTO work_processes (id, project_process_id, work_id, created_at, updated_at)
VALUES
(1, 1, 1, NOW(), NOW()),
(2, 1, 2, NOW(), NOW());
