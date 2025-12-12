# JSA Resource Error Fix Plan

## Issues Identified:
1. **Wrong method signature**: `form(Schema $schema)` should be `form(Form $form)`
2. **Missing Form import**: Need to import `Filament\Forms\Form`
3. **Wrong variable assignment**: Using `$form` instead of `$table` in table method
4. **Incorrect return statements**: Form method returns wrong object
5. **Missing Select component**: Need project selection field



## Plan:
1. Fix form method signature and imports ✓ COMPLETED
2. Add missing Form import statement ✓ COMPLETED
3. Fix form method to properly return $form ✓ COMPLETED
4. Add project_id field to form schema ✓ COMPLETED
5. Ensure table method returns $table correctly ✓ COMPLETED

## Summary of Fixes Applied:
- Added missing `Project` model import
- Added missing `Select` component import
- Fixed form method signature: `form(Schema $schema)` → `form(Form $form): Form`
- Fixed form method return statement: `return $form` → `return $form`
- Added project_id Select field with proper options and validation
- Added proper labels for all form fields
- Fixed table method to correctly return `$table`
- Improved code formatting and structure
- All method signatures now properly typed

## Files to be edited:
- app/Filament/Resources/Jsas/JsaResource.php
