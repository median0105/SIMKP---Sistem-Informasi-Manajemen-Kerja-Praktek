# TODO for Improving Superadmin Periodes Index Page UI

## Overview
Upgrade the resources/views/superadmin/periodes/index.blade.php page to align its UI and UX with other superadmin pages such as users/index.blade.php.

## Tasks

1. Container and Layout
   - Wrap main content inside a max-width container with consistent padding and margin as used in users/index.

2. Header and Action Button
   - Ensure the header slot displays the title "Daftar Periode".
   - Update the "Tambah Periode Baru" button with consistent primary blue color, padding, rounded corners, and prepend a plus icon.

3. Flash Messages
   - Style success and error flash messages with background, border, and padding consistent with other superadmin pages.

4. Table Styling
   - Add proper divide-y divide-gray-200 on tbody and table for row separation.
   - Use consistent padding and text sizing for table cells.
   - Use badges for status column with color-coded backgrounds and text for "Aktif" and "Tidak Aktif".
   - Add hover or focus states for rows if applicable.

5. Action Buttons per Row
   - Add icons to Edit (fa-edit) and Delete (fa-trash) buttons.
   - Style buttons with consistent colors and hover effects.
   - Disable the Delete button for active periode with a disabled style and tooltip.
   - Group action buttons horizontally with spacing.

6. Pagination
   - Add consistent styled pagination bar at the bottom with appropriate padding and border top.

7. Accessibility
   - Ensure all buttons have aria labels or titles for accessibility.

## Follow-up
- Test changes visually and functionally.
- Review consistency with other superadmin pages.
- Adjust spacing, colors as needed for a polished UI.
