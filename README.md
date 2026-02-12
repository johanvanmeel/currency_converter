# Laravel Application with DDEV

This project is a Laravel-based application configured to run with [DDEV](https://ddev.readthedocs.io/).

## Installation

Follow these steps to set up the application for the first time after pulling from Git:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/johanvanmeel/currency_converter.git && 
    cd currency_converter
    ```

2.  **Start DDEV:**
    Make sure you have DDEV installed and running on your machine.
    ```bash
    ddev start
    ```

3.  **Install PHP dependencies:**
    ```bash
    ddev composer install
    ```

4.  **Install Node dependencies and build assets:**
    ```bash
    ddev npm install && 
    ddev npm run build
    ```
    *Note: Running `ddev npm run build` is required to generate the Vite manifest. If you encounter a `ViteManifestNotFoundException`, ensure this step completed successfully.*

5.  **Generate application key:**
    ```bash
    ddev artisan key:generate
    ```

## Database Setup

To install or reset the database schema, run the following command:

```bash
ddev artisan migrate:fresh
```

This will drop all existing tables and run all migrations from scratch.

## User Management

You can add a new user to the application using Laravel Tinker:

1.  **Open Tinker:**
    ```bash
    ddev artisan tinker
    ```

2.  **Create a user:**
    ```php
    \App\Models\User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);
    ```

## IP Restriction Management

The application includes a middleware to restrict access based on IP addresses.

### Adding an Allowed IP or Range

You can add an allowed IP address or a CIDR address range (e.g., `192.168.1.0/24`) using Artisan Tinker:

1.  **Open Tinker:**
    ```bash
    ddev artisan tinker
    ```

2.  **Add an allowed IP:**
    ```php
    \App\Models\AllowedIp::create([
        'ip_address' => '127.0.0.1',
        'description' => 'Local development'
    ]);
    ```

3.  **Add an IP range:**
    ```php
    \App\Models\AllowedIp::create([
        'ip_address' => '192.168.1.0/24',
        'description' => 'Internal network'
    ]);
    ```

## Fetching Exchange Rates

The application includes a command to fetch the latest exchange rates from FloatRates. To run it, use:

```bash
ddev artisan app:fetch-exchange-rates
```

A cron job is configured to run this command every day at 06:00.
See the console routes file (`routes/console.php`) for more information.

## Middleware: RestrictIpAddress

The `RestrictIpAddress` middleware ensures that only requests from authorized IP addresses can access certain parts of the application.

### How it works:
- It retrieves all allowed IP addresses and ranges from the `allowed_ips` database table.
- It uses `Symfony\Component\HttpFoundation\IpUtils::checkIp` to validate the incoming request's IP address against the stored list.
- **Single IPs:** Matches exact IP addresses (e.g., `127.0.0.1`).
- **IP Ranges:** Supports CIDR notation (e.g., `192.168.1.0/24`), allowing you to authorize entire subnets.
- If the `allowed_ips` table is empty, access is granted to everyone by default.
- If there are entries in the table and the requester's IP does not match any of them, a `403 Forbidden` response is returned.

### Additional information
- The application is running at https://currencyconverter.ddev.site
- The dashboard is available at https://currencyconverter.ddev.site/admin.
- To log in as a user, use the credentials from the `users` table.
- Test can be run using `ddev artisan test`.
