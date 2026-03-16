# TechnoBitz

> A dark-themed e-commerce web app for tech and gaming products — built for the modern Filipino gamer and tech enthusiast.

TechnoBitz is a full-stack e-commerce platform inspired by stores like Datablitz and GameStop. It features a sleek dark UI, a fully functional storefront, and a complete admin panel for managing the store.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 |
| Frontend | Vue 3 + TypeScript |
| Routing | Inertia.js |
| Styling | Tailwind CSS |
| Auth | Laravel Breeze |
| Build Tool | Vite |
| Database | MySQL |

---

## Features

### Storefront
- Product catalog with search, category, brand, condition, and price range filters
- Product detail pages with related product suggestions
- Persistent shopping cart with quantity controls and stock validation
- Checkout with Cash on Delivery (COD) support
- Order history with per-order detail and status tracking
- Customer order cancellation for pending and confirmed orders

### Admin Panel
- Dashboard with store stats — total orders, revenue, products, and categories
- Low stock alerts and recent order overview
- Full CRUD for products — with image upload, brand, condition, featured, and active toggles
- Full CRUD for categories — with image upload and product count
- Order management — view all orders, filter by status, and update order status

### General
- Role-based access control (admin / customer)
- Flash messages for user feedback
- Fully responsive — works on mobile and desktop
- Dark theme throughout

---

## Payment

TechnoBitz currently supports **Cash on Delivery (COD)** only. Payment gateway integration (e.g. PayMongo) is planned for a future release.

---

## Admin Access

To promote a user to admin via Laravel Tinker:

```bash
php artisan tinker
>>> \App\Models\User::where('email', 'your@email.com')->update(['role' => 'admin']);
```