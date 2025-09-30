# TODO: Modify Profile for Mahasiswa

## Tasks
- [ ] Create migration to add 'semester' (integer) and 'ipk' (decimal) to users table
- [ ] Update User model fillable to include 'semester', 'ipk'
- [ ] Update ProfileUpdateRequest to include validation for 'phone', 'semester', 'ipk' (conditional for mahasiswa)
- [ ] Modify the profile form partial to conditionally display npm (readonly), phone, semester, ipk for mahasiswa users
- [ ] Test the profile edit for mahasiswa role
