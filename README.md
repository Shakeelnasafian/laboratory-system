# Laboratory Information System (LIS)

A multi-tenant Laboratory Information System built for small diagnostic labs in Pakistan. Manages patients, test orders, results, billing, and PDF report generation.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Livewire 4, Alpine.js (via Livewire), Tailwind CSS
- **Database:** MySQL
- **Packages:** Spatie Laravel Permission, barryvdh/laravel-dompdf, Laravel Breeze

## Features

### Super Admin

- Manage multiple labs (create, edit, activate/deactivate)
- Each lab gets its own isolated data via `lab_id` multi-tenancy

### Lab Admin

- Full access to all modules below
- Staff user management (create, edit, toggle active, assign roles)
- Test category and test catalog management
- Lab settings (name, contact info, report header/footer)

### Lab Staff

- **Receptionist / Lab Incharge:** Register patients, create orders, manage invoices
- **Technician:** Enter and verify test results
- **Lab Incharge:** All of the above

### Core Modules

- **Patients** — Register, search, filter by gender/status, view history
- **Orders** — Step-by-step order creation (select patient → add tests → set payment), status workflow (pending → sample collected → processing → completed)
- **Results** — Enter test results with value, unit, flag (normal/high/low/critical), verify results
- **Billing / Invoices** — Invoice summary with paid/outstanding amounts
- **PDF Reports** — Professional lab report with header, patient info, results table, doctor signatures, footer

## Roles

| Role          | Access                                    |
| ------------- | ----------------------------------------- |
| `superadmin`  | Admin panel — manage all labs             |
| `lab_admin`   | Full lab access including settings/staff  |
| `lab_incharge`| Patients, orders, results, billing        |
| `receptionist`| Patients, orders, billing                 |
| `technician`  | Results entry and verification            |

## Setup

### Requirements

- PHP 8.2+
- MySQL
- Node.js + npm
- Composer

### Installation

```bash
git clone <repo-url>
cd Laboratory

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configure your `.env`:

```env
DB_DATABASE=lab_system
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seed:

```bash
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Build assets:

```bash
npm run build
```

Start the server:

```bash
php artisan serve
```

### Development (hot reload)

```bash
npm run dev
# In a separate terminal:
php artisan serve
```

## Default Credentials

| Role        | Email               | Password    |
| ----------- | ------------------- | ----------- |
| Super Admin | admin@labsystem.pk  | admin@12345 |

After login as super admin, create a lab and assign a `lab_admin` user via `/admin/labs/create`.

## URL Structure

```text
/login                          Login page
/admin/dashboard                Super admin dashboard
/admin/labs                     Labs list
/admin/labs/create              Create new lab

/lab/dashboard                  Lab dashboard
/lab/patients                   Patient list
/lab/patients/create            Register patient
/lab/orders                     Orders list
/lab/orders/create              New order
/lab/orders/{order}             Order detail + status update
/lab/orders/{order}/report      PDF report (opens in new tab)
/lab/results                    Results entry
/lab/invoices                   Billing summary
/lab/test-categories            Test categories (lab_admin)
/lab/tests                      Test catalog (lab_admin)
/lab/users                      Staff management (lab_admin)
/lab/settings                   Lab settings (lab_admin)
```

## Multi-tenancy

Login-based multi-tenancy (no subdomains). Every model uses a `lab_id` column. The `BelongsToLab` trait applies a global Eloquent scope that automatically filters all queries to the authenticated user's lab. There is no way for one lab's users to see another lab's data.

## Project Structure

```text
app/
  Http/Controllers/ReportController.php   PDF generation
  Livewire/Admin/                         Super admin components
  Livewire/Lab/                           Lab user components
  Models/                                 Eloquent models
  Traits/BelongsToLab.php                 Multi-tenancy scope trait
database/
  migrations/                             All table migrations
  seeders/RolesAndPermissionsSeeder.php   Roles + super admin user
resources/
  views/layouts/lab.blade.php             Lab sidebar layout
  views/layouts/admin.blade.php           Admin sidebar layout
  views/livewire/                         All Livewire blade views
  views/reports/order.blade.php           PDF report template
routes/web.php                            All application routes
```

## License

MIT
