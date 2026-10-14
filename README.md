# Christian Fundal — Full-Stack Portfolio

Responsive dark blue / black portfolio with PHP 8.1+, MySQL 8+ / MariaDB, vanilla JavaScript and CSS. Includes project detail pages, administrator CRUD for projects, screenshots, contact form stored in the admin inbox, mobile navigation, reveal animations, CSRF tokens, escaping, prepared SQL statements, password hashing, and basic spam protection.

## Getting started (XAMPP / Laragon)

1. Put this folder in your server's public directory, e.g. `htdocs/christian-portfolio`.
2. Start Apache and MySQL. In phpMyAdmin, import `database/schema.sql`. **It creates and selects the `christian_portfolio` database and inserts three draft project profiles.**
3. Copy `config.example.php` to `config.php`. Enter your MySQL database details. Alternatively set environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` (they take precedence).
4. Create an admin using the **command line**, not a public URL:
   ```bash
   cd /path/to/christian-portfolio
   php setup-admin.php your-admin-email@example.com 'your-strong-unique-password'
   ```
   Password must be 12+ characters. If `php` isn't on your PATH, call your XAMPP PHP binary (e.g. `C:\xampp\php\php.exe`).
5. Visit `http://localhost/christian-portfolio/`. Admin login: `/admin/login.php` using the account you just created.
6. In the dashboard, edit your project statuses and descriptions, upload **actual** project screenshots, and add authentic demo/GitHub links.

## Portfolio content

- Name: Christian Calvario Fundal
- Professional focus: Full-Stack Web Developer (aspiring; currently studying)
- Education: Bachelor of Science in Information Technology, Siargao Island Institute of Technology
- Location: General Luna, Surigao del Norte, Philippines
- Skills: HTML, CSS, JavaScript, PHP, MySQL
- Featured starter entries: DatscoGo, GasGo, TailorTrack
- Public links: GitHub `Christ102po`, LinkedIn `christianfundal`
- Contact email: christianfundalofficial@email.com

**Important:** Seed projects are deliberately labeled *In development* and described conservatively. This does **not** mean they were checked and found incomplete: their completion, ownership, features, and availability have not been independently verified. Update the status only when you confirm it. No fake screenshots, repositories or demos are included.

## Contact messages

Messages are saved to MySQL and visible under Admin → Messages. They are **not emailed** automatically. Contact submission is validated, CSRF-checked, protected with a honeypot field, and rate-limited per session. For significant public traffic, add CAPTCHA, IP-based throttling at your reverse proxy, and a privacy/data-retention policy. Users also have a direct email link.

## Hosting and security

- Use HTTPS in production, change database credentials, and create a dedicated least-privilege database account.
- Apache `.htaccess` protects config/schema and disables PHP execution in `/uploads`. **For Nginx, configure equivalent deny rules** for `/config.php`, `/setup-admin.php`, `/database/`, and executable scripts in `/uploads/`.
- Place `config.php` outside the web root if your deployment setup supports it; alternatively block HTTP access strictly.
- Ensure PHP extensions `pdo_mysql`, `fileinfo`, `mbstring`, and sessions are enabled; uploads need write permissions on `/uploads`.
- Backup the database and screenshots. Keep PHP and dependencies updated.
- Admin access starts with CLI-only account creation, no default credentials.
- Upload checks MIME, image content and size (3 MB), and saves random filenames. Consider re-encoding images and virus scanning at scale.
- Do not expose your personal phone number unless you want it public. This starter does not display it.
- Public pages use Google Fonts if online; local fallbacks render offline.

## Structure

- `index.php` — portfolio home, profile, skills, selected projects and contact
- `project.php` — dynamic project case study
- `contact.php` — validates and stores form submissions
- `admin/` — login, dashboard, projects CRUD, message inbox
- `includes/` — reusable DB connection, security utilities, and layouts
- `database/schema.sql` — schema and starter project records
- `assets/css/style.css`, `assets/js/main.js` — responsive design and interactions
- `uploads/` — verified screenshots
- `setup-admin.php` — CLI-only administrator setup


## Add or change your own profile photo
1. Log in at `/admin/login.php`.
2. Choose **Profile photo** in the admin navigation (or use the dashboard shortcut).
3. Select a JPG, PNG, or WebP image (max 3 MB), preview it, then click **Save profile photo**.
4. Refresh the homepage. The initials in the animated hero frame will be replaced by your photo. You can replace or remove it later.

**For existing databases:** import `database/upgrade-profile-photo.sql` in phpMyAdmin **once** before uploading a photo. New installations already include this table in `database/schema.sql`. This upgrade does not delete any existing projects or messages.


### Skills & technologies (updated)
The homepage toolkit section now includes HTML/CSS, JavaScript, TypeScript, PHP, MySQL, Firebase, Expo, ChatGPT, OpenAI, GitHub, Railway, Render, Canva, and Adobe Photoshop. The skills cards adapt from 3 columns on desktop to 2 on tablet and 1 on narrow mobile screens. Edit the toolkit content in `index.php`. No database migration is required for this update.


### Cursor and scrolling effects
- Blue cursor glow and a smooth trailing ring on desktop mouse/trackpad devices.
- Subtle pointer-following highlight on skill and project cards.
- Thin blue scroll progress indicator at the top of public pages.
- Existing scroll-reveal animations remain enabled.
- Mobile/touch users do not see the cursor effects, and reduced-motion settings are respected.
- The system mouse pointer stays visible for accessibility.

Skill cards now smoothly zoom out to 94% size when hovered (mouse/trackpad only).
