# TODO: Implementasi Sistem Generate Sertifikat Pengawas Lapangan

## Progress
- [x] Analyze existing code (Model, Controller, Migration)
- [x] Create plan and get user approval
- [x] Add routes for SertifikatPengawasController in web.php
- [x] Create CRUD views in resources/views/superadmin/sertifikat-pengawas/
  - [x] index.blade.php
  - [x] create.blade.php
  - [x] show.blade.php
  - [x] edit.blade.php
- [x] Create PDF template in resources/views/templates/sertifikat-pengawas.blade.php
- [x] Add menu "Sertifikat" to superadmin sidebar
- [x] Update PDF template to use custom image background
- [x] Install and configure DomPDF package
- [x] Fix template image path to use asset() helper
- [x] Fix PDF storage to use public disk
- [x] Fix storage path issue in generate method (save to public disk)
- [x] Simplify download method to use Storage facade
- [x] Reset is_generated flag for existing records to allow regeneration
- [x] Fix text positioning in certificate template (prevent overlapping, adjust positions)
- [x] Improve PDF quality (increase DPI to 300, remove margins)
- [x] Add overflow:hidden to prevent layout issues
- [x] Increase font sizes and adjust z-index for better visibility
- [x] Change PDF orientation to A4 landscape
- [x] Adjust template dimensions for landscape layout
- [x] Reposition text elements for landscape layout (no overlapping)
- [ ] Test generate and download functionality
- [ ] Validate access permissions
