MUSTAFA TRAVELS PROFESSIONAL WEBSITE V1.3 — RENDER READY

WHAT IS NEW
- Primary WhatsApp changed to +34 611 473 217.
- All WhatsApp buttons now open this number.
- Offer enquiry buttons keep automatic pre-filled messages.
- Render-ready Dockerfile included.
- render.yaml included.
- SQLite supports a Render Persistent Disk.
- Uploaded offer/certificate images can also live on the persistent disk.
- image.php serves uploaded images from persistent storage.

RECOMMENDED RENDER SETUP
1. Push this folder contents to your GitHub repository root.
2. On Render, connect the repository as a Docker Web Service.
3. Add a Persistent Disk:
   Mount path: /var/data
   Size: 1 GB is enough to begin with.
4. Environment variables:
   MUSTAFA_DATA_DIR=/var/data
   MUSTAFA_UPLOAD_DIR=/var/data/uploads
5. Deploy.
6. Keep your custom domain mustafatravels.org connected to this Render service.

IMPORTANT
- Without a Persistent Disk, SQLite database changes and uploaded images can be lost on redeploy/restart.
- The current default admin login is:
  admin
  ChangeMe123!
  Change ADMIN_PASS in config.php before or immediately after deployment.
- This website is an enquiry/admin-offer website, not a live GDS booking engine.

LOCAL XAMPP
The same package also runs locally:
http://localhost:8081/MustafaTravels_Website_V1_3/
Admin:
http://localhost:8081/MustafaTravels_Website_V1_3/admin.php
