<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About Laravel Import & Sale Management System

An import & Sale Management System based on Laravel 8

## How to install

- composer install
- npm install
- npm run dev
- php artisan key:generate
- php artisan migrate --seed

## Issues while deploying
- If you get error ('Server Error') while listing the datatables then add the following SQL before that query
- DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

## TODO

- sales return (Pending) - or Edit Sales Return amount adjustment issue handling
- filters in listing page
- datatables button for Copy, Print, PDF
- Customer Sales Alert based on due date
- Urdu translation
- Auto adjust amount of customer, if entered more than actual sales order price.
- Amount should be shown in urdu too
- There should be Bank/Cheque options in Purchase Orders and Sales Orders
- Unsaved changes alert on the form (Leave?)
- Sales Form Check if the quantities of the products are correct?
- Sales Edit Form
- Save foreign amount only - do not convert to local, AND currency conversion should be done at query level
- image uploading in products, users etc.
- Receipts & payments -> Cross entry -> Add Customers/Suppliers (Other Party) and show their respective balances when dropdown selected.
- Godowns tracking
- customizeShift needs fixation - put both quantity and units in form -
- Damage Logic fixation
- Link the invoices wherever they are
- Payment / Receipts Confirmation page
- Staff Ledger
- Purchase should be editable until sold
- Expense Rows in Sales Order (like in Purchase Orders), so that the account should be maintained too
- Reports
    - Profit/Loss (All Period, Specific Purchase, Specific Sales)
	- Cash in hand
    - Account Ledgers --- Customer, Suppliers, Staff, Godowns, Transport, Bank
## Conversion of exchange rate calculation

## Questions

## Suggestions TODO
