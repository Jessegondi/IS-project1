# IS Project 1

PHP + MySQL signup/login site with a feedback form, hosted on Apache (Ubuntu).

## Pages
- `signup.php` registers a user (password hashed with `password_hash`)
- `login.php` / `logout.php` handle sessions (`password_verify`)
- `dashboard.php` shows the logged-in user's details
- `feedback.php` is the extra form, stored in the `feedback` table

## Setup
1. `mysql -u root -p < db.sql` (creates the `users` and `feedback` tables in `CAT2`)
2. `cp config.sample.php config.php` and set the real DB password
3. Browse to `http://<server-ip>/IS-project1/`
