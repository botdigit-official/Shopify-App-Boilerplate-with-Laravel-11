🛍️ Shopify App Boilerplate with Laravel 11

🚀 Build Shopify Apps Faster with Laravel 11 – A robust, scalable boilerplate for Shopify app development, featuring multi-step installation, secure OAuth authentication, and admin dashboards.

🌟 Key Features

🔑 Complete Installation Flow

Multi-step app setup wizard

Shopify OAuth authentication

Store registration and access token management

⚙️ Powerful Controllers

ShopifyController: App installation, callback handling, and webhooks

SetupController: First-time app setup and admin user creation

🔐 Authentication & Security

Shopify OAuth and admin authentication

Middleware-based route protection

HMAC verification for secure requests

📊 Admin & Store Management

Store dashboard with flexible settings

Admin dashboard for app control

Store installation and uninstallation

Product listing endpoint example

🎨 Frontend & UX

Tailwind CSS integration

Shopify App Bridge support

Responsive and embedded app compatibility

📖 Installation Guide

1️⃣ Clone the Repository

git clone https://github.com/yourusername/shopify-app-boilerplate-laravel11.git
cd shopify-app-boilerplate-laravel11

2️⃣ Install Dependencies

composer install
npm install

3️⃣ Set Environment Variables

Shopify API Key

API Secret

Redirect URI

Required Scopes

4️⃣ Run Migrations

php artisan migrate

5️⃣ Serve the App

php artisan serve

Navigate to `https://your-app-url/shopify/products?shop=your-store.myshopify.com` to view the example product listing.

Run tests with:

```bash
php artisan test
```

🔐 Security Considerations

HMAC signature verification

Access token validation

Secure admin user creation

Middleware-based route protection

🚀 Roadmap & Improvements

Enhanced error handling

Advanced webhook support

Detailed logging

Extended store management

🛠️ Tech Stack

Backend: Laravel 11

Frontend: Tailwind CSS

Authentication: Shopify OAuth

App Bridge: Shopify App Bridge

📄 License

This project is licensed under the MIT License.

👉 Start building your Shopify app today!

