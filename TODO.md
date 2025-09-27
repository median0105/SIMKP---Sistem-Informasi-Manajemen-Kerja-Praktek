# TODO: Implement Rejected KP Deletion Flow with Auto-Refresh

## Step 1: Add Delete Functionality in SuperAdmin
- [ ] Add `destroyKP` method in `app/Http/Controllers/SuperAdmin/UserController.php`
- [ ] Implement file cleanup (file_krs, file_laporan) and related records deletion
- [ ] Add delete button in `resources/views/superadmin/users/show.blade.php` for ditolak KPs

## Step 2: Implement Auto-Refresh on Mahasiswa Page
- [ ] Add `checkLatestKP` method in `app/Http/Controllers/Mahasiswa/KerjaPraktekController.php`
- [ ] Add JavaScript polling in `resources/views/mahasiswa/kerja-praktek/index.blade.php`

## Step 3: Update Routes and Policies
- [ ] Add routes in `routes/web.php` for destroyKP and checkLatestKP
- [ ] Update `app/Policies/KerjaPraktekPolicy.php` for superadmin delete permission

## Step 4: Testing and Edge Cases
- [ ] Test full flow: apply -> reject -> delete -> auto-refresh -> reapply
- [ ] Handle edge cases (non-ditolak delete attempt, file safety)
- [ ] Optional: Add notification on deletion

## Step 5: Final Verification
- [ ] Run artisan commands and verify no breaking changes
