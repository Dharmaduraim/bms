# Invoice & Budget Management System (CodeIgniter 3)

A responsive admin system for managing sales invoices, purchase invoices, customers, suppliers, products/services, budgets, expenses, payments, reports, and live currency exchange rates.

## Technology

- PHP 8.1+
- CodeIgniter 3
- MySQL / MariaDB
- Bootstrap 5
- jQuery
- Chart.js for report visualization
- Frankfurter public REST API for currency exchange rates

> The supplied starter project is CodeIgniter 3. This completed version keeps that architecture and extends the existing code rather than replacing it with Laravel.

## Main Features

- Admin login and session protection
- Responsive financial dashboard
- Customer and supplier management
- Product / service management with unit price and tax rate
- Sales invoice CRUD with multiple invoice items
- Purchase invoice CRUD with multiple invoice items
- Automatic invoice subtotal, item tax, discount and grand total calculations
- Server-side recalculation before saving invoice totals
- Invoice details page
- Payment recording and payment history
- Automatic invoice status: Pending, Partially Paid, Paid, Overdue
- Monthly / yearly budget management
- Expense tracking against selected budgets
- Budget utilization and remaining amount calculation
- Financial reports and 6-month chart
- Live currency exchange-rate API integration
- CSRF protection and output escaping
- Legacy demo password is supported and upgraded to a secure password hash on successful login

## Setup

1. Copy the project folder to your web root, for example `C:/xampp/htdocs/bms`.
2. Start Apache and MySQL in XAMPP.
3. Create a database named `invoice_budget_db`.
4. Import `database/invoice_budget_db.sql` using phpMyAdmin or MySQL CLI.
5. Check `application/config/database.php` and update the MySQL username/password if required.
6. Check `application/config/config.php` and update `base_url` if the folder name or host is different.
7. Open `http://localhost/bms/`.

## Demo Login

- Email: `admin@example.com`
- Password: `password`

## Invoice Calculation

For each invoice item:

`base amount = quantity × unit price`

`item tax = base amount × tax rate / 100`

Invoice totals:

`subtotal = sum of all base amounts`

`tax = sum of item taxes`

`grand total = subtotal + tax - discount`

All totals are recalculated on the server before saving, so browser-side values are not trusted.

## Payment Status Logic

- **Paid**: total payments are equal to or greater than invoice total
- **Partially Paid**: at least one payment exists but balance remains
- **Overdue**: no payment, balance remains and due date has passed
- **Pending**: otherwise

## Currency API

The **Currency API** page calls the public Frankfurter API and displays current reference rates for INR, USD, EUR, GBP, SGD, and AUD. The application shows a friendly error if the external service is unavailable.

## Important Production Notes

- Change the encryption key in `application/config/config.php`.
- Configure HTTPS and secure cookies on production hosting.
- Remove demo credentials from this README before deploying publicly.
- Configure environment-specific database credentials rather than committing production secrets.
