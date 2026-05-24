# Getting Started with Om Sai Travels
 This project is a custom travel booking management system built with HTML, CSS, JavaScript, PHP, and MySQL.

## Available Setup Steps
In your local server environment (e.g., XAMPP), you should configure:

## Database Import
Initializes the required database tables.

Open your MySQL manager (like phpMyAdmin) and import the database.sql file into a new database.

The application requires this database to store routes, bookings, and messages.

You will see connection errors if your credentials in includes/db.php are not updated to match your local setup.

Local Server Launch
Runs the app in the local development mode.

Open http://localhost/om-sai-travels/index.php to view the customer-facing site in your browser.

The page will immediately reflect any edits you make to the source files.

You can check your Apache/Nginx console for any backend PHP errors.

## Admin Access
Launches the secure administrative management portal.

Navigate to http://localhost/om-sai-travels/admin/login.php to oversee bookings, edit travel routes, and manage customer messages.

## Learn More
You can view the core platform functions, database connections, and header/footer components within the includes/ directory.

To learn about customizing the interface, check out the frontend assets in assets/css/style.css and assets/js/main.js.

## Route Management
The active route browsing and administrative configuration logic can be found here: routes.php and admin/routes.php

## Booking System
The customer reservation and ticketing logic can be found here: booking.php and confirmation.php

## User Dashboard
The personalized user booking history can be found here: my-booking.php

Advanced Configuration
Database connection settings and core PHP functions are located here: includes/db.php and includes/functions.php

Contact Integration
The customer support form and administrative messaging inbox can be found here: contact.php and admin/messages.php
