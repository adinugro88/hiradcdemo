-- FM_HSE / Lifting Plan Dummy Data Dump
-- Assumptions:
-- 1) Tables already created:
--    lifting_equipment, lifting_gears, inspections,
--    lifting_plans, lifting_plan_equipments, lifting_plan_loads,
--    lifting_plan_technical_data, lifting_plan_gears,
--    lifting_plan_safety, lifting_plan_approvals
-- 2) Referenced parent records already exist:
--    projects.id = 1
--    users.id IN (1,2,3,4)

SET FOREIGN_KEY_CHECKS=0;

-- =========================
-- 1) MASTER LIFTING EQUIPMENT
-- =========================
INSERT INTO lifting_equipment
(id, equipment_code, equipment_name, equipment_type, brand, model, serial_number, year,
 max_capacity_ton, boom_length_min_m, boom_length_max_m, owner_company, status, created_at, updated_at)
VALUES
(1, 'CRN-025', 'Truck Crane 25 Ton', 'mobile_crane', 'TADANO', 'GR-250N', 'TDN250-8891', 2021,
 25.00, 10.00, 32.00, 'PT Angkat Jaya', 'active', NOW(), NOW()),
(2, 'CRN-050', 'Crawler Crane 50 Ton', 'crane', 'KOBELCO', 'CKE500', 'KBC500-1234', 2019,
 50.00, 12.00, 42.00, 'PT Angkat Jaya', 'active', NOW(), NOW());

-- =========================
-- 2) MASTER LIFTING GEAR
-- =========================
INSERT INTO lifting_gears
(id, gear_code, gear_type, description, size_spec, swl_ton, manufacturer, serial_number, status, created_at, updated_at)
VALUES
(1, 'SL-W-10T', 'sling_wire', 'Wire Sling 10 Ton', 'Ø22mm x 6m', 10.00, 'Gunnebo', 'WS-1001', 'active', NOW(), NOW()),
(2, 'SH-12T', 'shackle', 'Bow Shackle 12 Ton', '1 inch', 12.00, 'Crosby', 'SH-1201', 'active', NOW(), NOW()),
(3, 'HK-15T', 'hook', 'Crane Hook 15 Ton', '15 Ton', 15.00, 'Elephant', 'HK-1501', 'active', NOW(), NOW());

-- =========================
-- 3) INSPECTIONS (CEK ALAT & GEAR) + VALIDITY + LAST CHECK
-- =========================
-- Equipment inspections
INSERT INTO inspections
(id, inspectable_type, inspectable_id, inspection_type, inspection_date,
 validity_days, valid_until, result, findings, corrective_action,
 inspector_user_id, inspector_name, inspector_company,
 certificate_number, certificate_file, next_due_date, created_at, updated_at)
VALUES
(1, 'equipment', 1, 'yearly', '2024-01-15',
 365, '2025-01-14', 'pass', NULL, NULL,
 NULL, 'Budi Santoso', 'PT Sucofindo',
 'CERT-CRN-025-2024', NULL, '2025-01-14', NOW(), NOW()),
(2, 'equipment', 2, 'yearly', '2024-02-10',
 365, '2025-02-09', 'pass', NULL, NULL,
 NULL, 'Andi Wijaya', 'PT Surveyor Indonesia',
 'CERT-CRN-050-2024', NULL, '2025-02-09', NOW(), NOW());

-- Gear inspections
INSERT INTO inspections
(id, inspectable_type, inspectable_id, inspection_type, inspection_date,
 validity_days, valid_until, result, findings, corrective_action,
 inspector_user_id, inspector_name, inspector_company,
 certificate_number, certificate_file, next_due_date, created_at, updated_at)
VALUES
(3, 'gear', 1, 'monthly', '2024-06-01',
 30, '2024-06-30', 'pass', NULL, NULL,
 NULL, 'HSE Inspector', 'Internal',
 'CERT-SLING-06-2024', NULL, '2024-07-01', NOW(), NOW()),
(4, 'gear', 2, 'monthly', '2024-06-01',
 30, '2024-06-30', 'pass', NULL, NULL,
 NULL, 'HSE Inspector', 'Internal',
 'CERT-SHACKLE-06-2024', NULL, '2024-07-01', NOW(), NOW()),
(5, 'gear', 3, 'monthly', '2024-06-01',
 30, '2024-06-30', 'pass', NULL, NULL,
 NULL, 'HSE Inspector', 'Internal',
 'CERT-HOOK-06-2024', NULL, '2024-07-01', NOW(), NOW());

-- =========================
-- 4) LIFTING PLAN HEADER (FM/HSE-1/20)
-- =========================
INSERT INTO lifting_plans
(id, document_number, revision, form_code, project_id, location,
 plan_date, material_type, maximum_load_ton, crane_type, lifting_type,
 communication_method, status, created_by, created_at, updated_at)
VALUES
(1, 'LP-001/HSE/2024', '0', 'FM/HSE-1/20',
 1, 'Area Workshop',
 '2024-06-10', 'Steel Structure', 8.50,
 'Truck Crane 25 Ton', 'routine',
 'Handy Talky & Hand Signal', 'approved', 1, NOW(), NOW());

-- =========================
-- 5) EQUIPMENT USED IN PLAN
-- =========================
INSERT INTO lifting_plan_equipments
(id, lifting_plan_id, equipment_id, role, notes)
VALUES
(1, 1, 1, 'main', 'Main lifting crane');

-- =========================
-- 6) LOAD BREAKDOWN
-- =========================
INSERT INTO lifting_plan_loads
(id, lifting_plan_id, weight_material_ton, weight_shackle_ton,
 weight_hook_ton, weight_sling_ton, total_weight_ton, created_at, updated_at)
VALUES
(1, 1, 7.50, 0.30, 0.40, 0.30, 8.50, NOW(), NOW());

-- =========================
-- 7) TECHNICAL DATA
-- =========================
INSERT INTO lifting_plan_technical_data
(id, lifting_plan_id, equipment_id, max_equipment_capacity_ton,
 main_boom_length_m, working_radius_m, lifting_angle_deg,
 outrigger_condition, lifting_capacity_ton, load_chart_source, created_at, updated_at)
VALUES
(1, 1, 1, 25.00,
 28.00, 10.00, 75.00,
 'full', 12.00, 'Load Chart Tadano GR-250N', NOW(), NOW());

-- =========================
-- 8) GEARS USED IN PLAN
-- =========================
INSERT INTO lifting_plan_gears
(id, lifting_plan_id, gear_id, used_quantity, size_used, swl_used_ton, created_at, updated_at)
VALUES
(1, 1, 1, 2, 'Ø22mm x 6m', 10.00, NOW(), NOW()),
(2, 1, 2, 2, '1 inch', 12.00, NOW(), NOW()),
(3, 1, 3, 1, '15 Ton', 15.00, NOW(), NOW());

-- =========================
-- 9) SAFETY FACTOR
-- =========================
INSERT INTO lifting_plan_safety
(id, lifting_plan_id, total_load_ton, lifting_capacity_ton,
 safety_factor, safety_status, rule_note, created_at, updated_at)
VALUES
(1, 1, 8.50, 12.00,
 1.4118, 'safe', 'SF > 1.2 (AMAN)', NOW(), NOW());

-- =========================
-- 10) APPROVALS (Dibuat/Diperiksa/Disetujui/Diketahui)
-- =========================
INSERT INTO lifting_plan_approvals
(id, lifting_plan_id, user_id, role, signed_at, note, created_at, updated_at)
VALUES
(1, 1, 1, 'dibuat',    '2024-06-05 09:00:00', 'Disiapkan oleh HSE',           NOW(), NOW()),
(2, 1, 2, 'diperiksa', '2024-06-06 10:30:00', 'Sudah sesuai prosedur',        NOW(), NOW()),
(3, 1, 3, 'disetujui', '2024-06-07 14:00:00', 'Disetujui untuk pelaksanaan', NOW(), NOW()),
(4, 1, 4, 'diketahui', '2024-06-07 15:00:00', 'Diketahui Site Manager',       NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
