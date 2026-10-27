# Consumer Rights Portal

A website that helps consumers understand their rights, avoid scams, report fraud, track complaints, and access consumer protection information.

## Requirements
- PHP 8.0 or newer
- MySQL 8 or MariaDB 10.5 or newer
- A web host that supports PHP (for example, cPanel shared hosting)

## Project structure
```
consumer-portal/
├── index.php                 Homepage
├── config.php                Shared settings, database connection, security helpers
├── config.local.example.php  Template for your secret settings (safe to commit)
├── config.local.php          YOUR secrets (created locally, never committed)
├── database/
│   └── schema.sql            Database tables
├── assets/
│   └── style.css             Site styles
├── .gitignore                Files Git must ignore
└── README.md                 This file
```

## First-time setup on your computer
1. Copy `config.local.example.php` to `config.local.php`.
2. Fill in your database name, user, password, and domain.
3. Import `database/schema.sql` into your MySQL database.
4. Test locally, or upload to your host for testing.

## Working with GitHub
```bash
git init
git add .
git commit -m "Initial portal foundation"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/consumer-portal.git
git push -u origin main
```

Before your first commit, check that `config.local.php` is not in the list:
```bash
git status
```

If it appears, `.gitignore` is not working. Stop and fix it before pushing. Once a secret reaches GitHub, change the password immediately.

## Security rules
- Never commit `config.local.php`, uploaded files, or passwords.
- Keep the GitHub repository **private** while the site is in development.
- Set `display_errors` to off on the live site (already set in `config.php`).
- Use HTTPS on your domain.

## Deployment options
- **cPanel shared hosting:** Upload with FTP or File Manager, or use GitHub Actions to deploy automatically on each push.
- **VPS:** Pull the repository with `git pull` on the server.

## Roadmap
- [ ] Login and registration
- [ ] User dashboard
- [ ] File a complaint form with secure uploads
- [ ] Track My Complaint
- [ ] Admin dashboard
- [ ] Know Your Rights and Get Help content
- [ ] Deployment
