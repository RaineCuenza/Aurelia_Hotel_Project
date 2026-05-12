<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## emails and Passwords for user roles 



## Project Analysis Summary 

This summary is a factual extraction from the project's migrations, models, controllers, routes, and seeders. It contains only information explicitly defined in code.

System Summary
- Purpose: a room booking/reservation system with customer booking flows, staff/admin back-office, and JSON API.
- Primary models: `User`, `RoomType`, `Room`, `Reservation`.

User Roles and Permissions
- Roles (DB enum): `admin`, `staff`, `customer`.
- Role enforcement: `App\\Http\\Middleware\\EnsureUserRole` (routes use middleware alias `role`).

Implemented Features
- Authentication: register, login, logout, email verification (`AuthController`).
- Customer booking flow: search, room details, booking form, booking creation, mock payment, confirmation, history (`CustomerController`).
- Reservation management (admin/staff): CRUD, status updates (`ReservationController`).
- Staff operations: check-in, check-out, mark room clean (`StaffController`).
- Admin dashboard metrics (`DashboardController`).
- APIs for rooms and reservations (`app/Http/Controllers/Api`).

Database Structure (tables overview)
- `users`: id, first_name, last_name, email (unique), phone (nullable), role (enum), email_verified_at, password, remember_token, timestamps.
- `room_types`: id, name, slug, description, base_price, timestamps.
- `rooms`: id, room_type_id (FK), room_number (unique), max_guests, status (enum), timestamps.
- `reservations`: id, user_id (nullable FK), room_id (FK), guest_name, guest_email, guest_phone, check_in, check_out, guests, special_requests, status (enum), total_amount, payment fields, timestamps.
- Support tables: `password_reset_tokens`, `sessions`, `cache`, `cache_locks` (see migrations for exact columns).

Relationships
- `RoomType` 1 -> * `Room`.
- `Room` 1 -> * `Reservation`.
- `User` 1 -> * `Reservation`.
- Foreign keys: `rooms.room_type_id` -> `room_types.id`; `reservations.room_id` -> `rooms.id`; `reservations.user_id` -> `users.id` (nullable, nullOnDelete).

Business Logic (explicit rules)
- Room statuses: `available`, `booked`, `occupied`, `cleaning`, `maintenance`.
- Reservation statuses: `pending`, `confirmed`, `checked_in`, `checked_out`, `cancelled`.
- Customer booking: creates `pending` reservation with `payment_status = pending`, `payment_reference` mock, `payment_expires_at = now()+15min`, room -> `booked`.
- Admin/staff-created booking: created as `confirmed` and `paid` with `manual_frontdesk` payment.
- Mock payments: `processMockPayment` sets `paid`/`failed`/`expired` and updates reservation and room accordingly.
- Check-in: allowed only when `status === 'confirmed'` and `payment_status === 'paid'` (reservation -> `checked_in`, room -> `occupied`).
- Check-out: allowed only when `status === 'checked_in'` (reservation -> `checked_out`, room -> `cleaning`).
- Availability checks use date-overlap logic and consider pending payments with `payment_expires_at`.

Key Routes (high-level)
- Customer: `/home`, `/rooms`, `/rooms/{room}`, `/book/{room}`, booking payment/confirmation, `/my-bookings`.
- Staff/Admin: `/staff/*` (dashboard, reservations, checkin/checkout, rooms), `/dashboard` (admin), reservation management under `/reservations`.
- API: `/api/rooms`, `/api/reservations`.

Inconsistencies / Issues (present in code)
- `Reservation` and `User` define `protected function casts(): array` instead of the standard `protected $casts` property; Eloquent may not apply these casts.
- Migration default for `payment_status` (`unpaid`) differs from controller usage (`pending`).
- `EnsureUserRole` middleware class exists but I did not find its alias registration in inspected files (e.g., `app/Http/Kernel.php` was not found in scanned files).

For details see controllers (`app/Http/Controllers`), models (`app/Models`), migrations (`database/migrations`), and seeders (`database/seeders`).




