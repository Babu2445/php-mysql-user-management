# PeopleDesk — User Management System

A beginner-friendly user registration and management application built with Core PHP, PDO, MySQL, Bootstrap 5, CSS, and JavaScript. It includes a responsive dashboard and directory with search, pagination, and CRUD actions.

## Features

- Account registration with server-side validation, duplicate email protection, and `password_hash()`.
- Sign in with `password_verify()`, session ID regeneration, and POST-only sign out.
- Protected dashboard and user directory.
- Create, list, edit, search, paginate, and delete directory records.
- PDO prepared statements, HTML output escaping, CSRF tokens on state-changing forms, and self-delete protection.
- Responsive SaaS-style navigation, dashboard cards, alerts, and empty states.

Administrator-created directory entries receive an unusable random password hash because invitations and password resets are outside this sample's scope. Account holders can register their own login. The registration page can be used to create the initial account.

## Technologies

- PHP 8.1+ (PDO MySQL, sessions, and mbstring extensions enabled)
- MySQL 5.7+ or MariaDB 10.4+
- HTML5, CSS3, Bootstrap 5.3 (CDN), and vanilla JavaScript
- XAMPP for local development

## XAMPP installation and setup

1. Install XAMPP from [apachefriends.org](https://www.apachefriends.org/) if it is not already installed.
2. Copy the entire `user-management-system` folder into your XAMPP `htdocs` folder. For a typical Windows installation, the resulting path is `C:\xampp\htdocs\user-management-system`.
3. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. Open `http://localhost/phpmyadmin/` in your browser.
5. In phpMyAdmin, choose **Import**, choose this project's `database.sql`, keep the format as SQL, and select **Import**. The script creates and selects the `user_management` database and creates the `users` table. Alternatively, paste the file contents into phpMyAdmin's SQL tab and run them.
6. Open `config/database.php`. XAMPP defaults are host `127.0.0.1`, database `user_management`, username `root`, and an empty password. If your local MySQL credentials differ, edit those defaults. For a hosted server, set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` environment variables instead of committing credentials.
7. Visit `http://localhost/user-management-system/`. Create your first account on the registration page, then sign in.

## Try the app

1. Register with a name, unique email, and password of at least 8 characters.
2. Sign in and review the overview and user count.
3. Add another directory user, edit their name/email, search by name or email, and try pagination after adding more than 10 records.
4. Delete a different user and confirm the browser prompt. The currently signed-in account cannot delete itself.
5. Sign out using the sidebar and verify that visiting `dashboard.php` redirects to sign in.

No demo account is pre-created. The first account must be registered through the app.

## Configuration and project map

```text
user-management-system/
├── assets/css/style.css
├── assets/js/script.js
├── config/database.php
├── includes/auth.php
├── includes/bootstrap.php
├── includes/csrf.php
├── includes/footer.php
├── includes/header.php
├── create_user.php
├── dashboard.php
├── database.sql
├── delete_user.php
├── edit_user.php
├── index.php
├── login.php
├── logout.php
├── register.php
├── users.php
├── .gitignore
└── README.md
```

Bootstrap and Google Fonts are loaded from CDNs, so those visual assets need internet access in the browser. Core functionality is local.

## Troubleshooting

- **Could not connect to database:** Ensure MySQL is running; confirm database name and credentials in `config/database.php`; import `database.sql`.
- **Unknown database/table:** Re-import `database.sql` through phpMyAdmin.
- **PDO driver not found:** In XAMPP's `php.ini`, enable `extension=pdo_mysql`, restart Apache, and check `phpinfo()` if needed.
- **Blank page or HTTP 500:** Check Apache's `error.log` and PHP error log. This app logs database connection details server-side while showing a generic message to visitors.
- **Changes do not appear:** Hard-refresh the browser and confirm that you are editing the copy inside `htdocs`.
- **Search/pagination:** Search matches partial names and email addresses; the directory displays 10 records per page.

## Put the project on GitHub

1. Create an empty GitHub repository.
2. From this folder, run `git init`, `git add .`, `git commit -m "Build PeopleDesk user management app"`, and `git branch -M main`.
3. Add your repository as `origin` with `git remote add origin <repository-url>` and push with `git push -u origin main`.
4. Keep production credentials out of Git. The provided `.gitignore` excludes `.env` files; use environment variables on deployment.

## Verification status

The source files and request flows were reviewed for consistency during creation. PHP/MySQL runtime checks were not executed in this environment; run the local steps above with XAMPP to verify database-backed behavior.
