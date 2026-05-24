# Om Sai Travels — Bus Reservation System

A complete PHP + MySQL bus reservation website with public booking flow and an admin panel for managing routes &amp; fares. Built on Bootstrap 5, designed in your light-blue / red / white palette.

---

## Features

### Public site
- Modern responsive homepage with hero, search form, popular routes, features.
- Route search: filter by origin, destination &amp; date.
- Bus listing with departure / arrival / duration / fare.
- **Dynamic pricing** — fares auto-adjust by time:
  - Weekend (Fri-Sun): **+15%**
  - Late-night (00:00–05:00): **+10%**
  - Off-peak day (10:00–16:00): **-5%**
- Interactive seat-selection (up to 6 seats per booking).
- Passenger details form &amp; instant PNR confirmation (printable ticket).
- "My Booking" — look up bookings by PNR.
- About &amp; Contact pages (contact form saves messages to DB).

### Admin panel (`/admin/login.php`)
- Dashboard with stats (routes, bookings, revenue, messages).
- Routes &amp; Fares — add / edit / delete / activate routes &amp; change base fare anytime.
- Bookings — search by PNR / name / phone, cancel bookings, view tickets.
- Messages — view &amp; delete customer contact-form submissions.

### Default admin credentials
```
Username : admin
Password : admin@123
```

---

## Installation on XAMPP (Windows / macOS / Linux)

### 1. Install XAMPP
Download &amp; install XAMPP from https://www.apachefriends.org . Start **Apache** and **MySQL** from the XAMPP Control Panel.

### 2. Copy the project
Copy this entire `om-sai-travels` folder to your XAMPP `htdocs` directory:

- Windows : `C:\xampp\htdocs\om-sai-travels`
- macOS   : `/Applications/XAMPP/htdocs/om-sai-travels`
- Linux   : `/opt/lampp/htdocs/om-sai-travels`

### 3. Create the database
1. Open http://localhost/phpmyadmin in your browser.
2. Click **Import** (top tab).
3. Choose the file `database.sql` from this project and click **Go**.
   This will create the database `om_sai_travels` with all tables and sample data.

> Alternative: open phpMyAdmin → "SQL" tab → paste contents of `database.sql` → "Go".

### 4. (Optional) Update DB credentials
If your MySQL has a non-empty root password, edit `includes/db.php`:

```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'your_password_here';   // default XAMPP password is empty
$DB_NAME = 'om_sai_travels';
```

### 5. Open the site
- Public site : http://localhost/om-sai-travels/
- Admin login : http://localhost/om-sai-travels/admin/login.php

That's it — you're live!

---

## Project structure

```
om-sai-travels/
├── index.php              ← Home page
├── routes.php             ← Search results / route list
├── booking.php            ← Seat selection &amp; passenger form
├── confirmation.php       ← e-Ticket with PNR
├── my-booking.php         ← PNR lookup
├── about.php
├── contact.php
├── database.sql           ← MySQL schema + seed data
├── includes/
│   ├── db.php             ← Database connection (PDO)
│   ├── functions.php      ← Helpers + dynamic fare logic
│   ├── header.php         ← Shared header / navbar
│   └── footer.php         ← Shared footer
├── admin/
│   ├── login.php          ← Admin login
│   ├── logout.php
│   ├── dashboard.php      ← Stats &amp; recent bookings
│   ├── routes.php         ← CRUD routes + change fares
│   ├── bookings.php       ← Manage bookings (cancel / search)
│   ├── messages.php       ← View contact messages
│   ├── _header.php        ← Admin layout header
│   └── _footer.php
└── assets/
    ├── css/style.css      ← Custom theme (light blue / red / white)
    └── js/main.js         ← Seat selector + date min
```

---

## Customising

| What | Where |
|------|------|
| Brand name / logo  | `includes/header.php` &amp; `includes/footer.php` |
| Colours            | `assets/css/style.css` — `:root` variables at the top |
| Add new routes     | Admin panel → Routes &amp; Fares (or edit `database.sql`) |
| Dynamic pricing %  | `includes/functions.php` → `dynamic_fare()` |
| Default admin pwd  | Use `password_hash('newpass', PASSWORD_BCRYPT)` in PHP and update the `admins` table |
| Contact phone / email | Edit `includes/header.php` (top-bar) &amp; `includes/footer.php` |

---

## Reset / Change admin password

In phpMyAdmin → run:

```sql
UPDATE admins
SET password_hash = '$2y$10$Z3KLH70T03XGYFrySPh.1uIEBYxxMxI/vI4G2q3//qgy2OWpPGJGu'
WHERE username = 'admin';
```
(The hash above is `admin@123`.)

To set a new password, create `gen.php` in your project, visit it once, then delete:
```php
<?php echo password_hash('YourNewPassword', PASSWORD_BCRYPT);
```
Copy the output and use it in the `UPDATE` SQL above.

---

## Troubleshooting

| Problem | Fix |
|---|---|
| "Database connection failed" | Check `includes/db.php` credentials &amp; ensure MySQL is running in XAMPP. |
| "Access denied for user 'root'" | Set the correct password in `includes/db.php`. |
| Booking buttons do nothing | Check browser console; ensure `assets/js/main.js` loads (no 404). |
| Admin can't log in | Re-run the `UPDATE admins` SQL above to reset the password. |
| `mod_rewrite`-style 404s | This project uses plain `.php` URLs, no rewrite needed. |

Built with ❤ in Maharashtra. Enjoy your bus business!
