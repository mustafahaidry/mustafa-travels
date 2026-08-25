MUSTAFA TRAVELS PROFESSIONAL WEBSITE V1.4 — SUPABASE ADMIN

- Admin Panel now uses Supabase.
- Daily Offers are persistent.
- Umrah Packages module added.
- Hotel Offers module added.
- Certificates and website inquiries use Supabase.
- Website Settings section added.
- No Render persistent disk required.
- Public Offers / Umrah / Hotels / Certificates pages read live data from Supabase.
- WhatsApp +34 611 473 217 retained.

ADMIN:
admin / ChangeMe123!
Change ADMIN_PASS in config.php before/after deploy.

SECURITY:
Only the publishable Supabase key is in code.
Never upload service_role or secret key to GitHub.

IMAGES:
For this version, images up to 2 MB are stored as data URLs in Supabase rows.
A later version can use Supabase Storage buckets.


REQUIRED RENDER ENVIRONMENT VARIABLE FOR ADMIN
Set this in Render -> Environment:
SUPABASE_SECRET_KEY = your current Supabase Secret Key

Do NOT put that secret in GitHub or config.php.
The public website continues using the publishable key.
The secret key is used only after the Admin login session, for protected CRUD operations and reading inquiries.
