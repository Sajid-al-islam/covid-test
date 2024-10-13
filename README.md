

# COVID Vaccine Registration System

This project is a COVID-19 vaccine registration system built with Laravel 11. Users can register for a vaccine, select a vaccine center, and get scheduled for vaccination. The system supports rescheduling for users who couldn't be scheduled initially and sends email notifications when a user is scheduled for vaccination.

## Features

- User registration for vaccination
- Scheduling based on first-come-first-serve
- Limit on how many users each vaccine center can serve daily
- Rescheduling of users who couldn't initially be scheduled
- Email notifications for scheduled users
- Search for registration status by NID

## Prerequisites

Before you start, make sure you have the following tools installed on your local machine:

- PHP >= 8.2
- Composer
- MySQL or any other supported database
- Git
- A mail service configured (Mailtrap, SMTP, etc.)

## Step-by-Step Setup Instructions

### Step 1: Clone the repository

```bash
git clone https://github.com/sajid-al-islam/covid-test.git
cd covid-test
```

### Step 2: Install Dependencies

Run the following command to install the Laravel dependencies via Composer:

```bash
composer install
```

### Step 3: Set Up Environment Variables

Copy the `.env.example` file and create a new `.env` file:

```bash
cp .env.example .env
```

Edit the `.env` file to configure your database and mail service. Here’s an example configuration:

```env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=root
DB_PASSWORD=your-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> Make sure to update your database and mail credentials accordingly.

### Step 4: Generate Application Key

Generate the application key for your Laravel project:

```bash
php artisan key:generate
```

### Step 5: Run Migrations and Seed Data

Run the following command to migrate the database tables and seed the vaccine centers with some initial data:

```bash
php artisan migrate --seed
```

> **Note:** The `DatabaseSeeder` is pre-configured to seed 10 vaccine centers into the database.

### Step 7: Serve the Application

Start the Laravel development server:

```bash
php artisan serve
```

Open your browser and go to `http://127.0.0.1:8000` to access the application.

### Step 8: Set Up Cron Job for Rescheduling

To ensure users who couldn't be scheduled initially are rescheduled later, you need to set up a cron job to run the scheduling process daily.

Open your server's crontab file:

```bash
crontab -e
```

Add the following line to schedule the `schedule:run` command to run every minute:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This will automatically handle rescheduling the users who were marked as `Not Scheduled`.

### Step 9: Testing

To test the system, you can:

- **Register a user**: Visit the registration page and complete the form.
- **Search for a user**: Use the search page to find a user by their NID.
- **Simulate email notifications**: Check the configured mail service (Mailtrap, SMTP, etc.) to see email notifications for scheduled users.

## Notes

- **Optimization**: 
  - Index frequently queried fields like `scheduled_date` in the `registrations` table to optimize performance.
  - Use caching for frequently accessed data such as available vaccine centers.
- **Scaling**: 
  - Consider using queue workers for sending emails and notifications asynchronously to improve performance when the number of users grows.
- **SMS Integration**: If required in the future, Laravel provides an easy way to integrate with services like Twilio for SMS notifications. You can extend the notification logic in the `VaccinationScheduled` mailable class and use Laravel's `Notification` system to send both email and SMS.

---

### Future Enhancements

1. **SMS Notifications**: If SMS notifications are needed in the future, we can utilize Laravel's `Notification` system to extend the current notification setup.
2. **Performance Optimizations**: Implement database indexes and caching to improve performance under heavy loads.

---

### License

This project is open-source and licensed under the [MIT license](LICENSE).

---
