# 📰 Blog Management System  
**A scalable backend-driven blogging platform with AI-powered articles, user subscriptions, and queued email notifications.**  

---

## 📌 Project Overview  
This project is designed to handle **content creation, AI-generated articles, user subscriptions, and email notifications efficiently**. It follows a **scalable, maintainable backend architecture** using **queues, scheduled jobs, and API-based authentication**.  

> 🔹 **Why This Project?**  
> - Demonstrates **scalable backend architectures**.  
> - Uses **queues & workers** to efficiently process large-scale tasks.  
> - Implements **RBAC (Role-Based Access Control)** for secure access.  
> - Supports **REST API authentication with Laravel Sanctum**.  

---

## ⚙️ Features Implemented  

| Feature | Backend Concept |
|---------|---------------|
| **Blog CRUD (Admin Panel)** | **MVC Architecture, Eloquent Relationships** |
| **Rich Text Editor (Trix) with Image Uploads** | **File Handling & Storage Strategies** |
| **Search & Filtering (Category, Tags, Title)** | **Indexing & Query Optimization** |
| **View Count Tracking** | **Database Optimization** |
| **Subscription-Based AI Article Limits** | **Role-Based Access, Monthly Reset Jobs** |
| **Role-Based Access Control (RBAC)** | **Policies & Middleware** |
| **Queued Email Notifications** | **Queue Workers & Job Processing** |
| **Stripe Subscription Integration** | **API Payments (Without Webhooks)** |
| **Scheduled Jobs for Subscription Resets** | **Laravel Task Scheduling, Cron Jobs** |

---

## 📂 Directory Structure  

```
📦 Blog Management System
 |-- 📂 app
 |   |-- 📂 Console (Scheduled Jobs)
 |   |-- 📂 Http
 |   |   |-- 📂 Controllers
 |   |   |-- 📂 Middleware
 |   |   |-- 📂 Requests (Form Validation)
 |   |-- 📂 Jobs (Queue-Based Processing)
 |   |-- 📂 Mail (Custom Email Templates)
 |   |-- 📂 Models
 |-- 📂 database
 |   |-- 📂 migrations (DB Schema)
 |   |-- 📂 seeders (Demo Data)
 |-- 📂 resources
 |   |-- 📂 views (Admin Panel UI)
 |   |-- 📂 emails (Newsletter Templates)
 |-- 📂 routes
 |   |-- web.php (Admin & Public)
 |   |-- api.php (REST APIs)
 |   |-- console.php (Task Scheduling)
 |-- 📂 storage
 |   |-- 📂 logs (Application Logs)
 |-- 📜 .env (Environment Config)
 |-- 📜 composer.json (Dependencies)
 |-- 📜 README.md (Project Documentation)

```

---

## 🚀 Backend Concepts & Implementation  

### 1️⃣ **Queued Email Notifications**  

#### **Problem Statement**  
- Sending **emails to thousands of subscribers** synchronously would slow down the system.  

#### **Solution**  
- Implemented **Laravel Queues** to **offload email sending to background workers**.  
- Job is triggered **only after the blog is fully stored**.  

#### **Workflow**  

1️⃣ **Admin Publishes Blog** →  
2️⃣ **`DispatchBlogNotificationJob` triggers** →  
3️⃣ **Fetches subscribers in chunks (1000 at a time)** →  
4️⃣ **`SendNewBlogNotificationJob` queues individual emails**  
5️⃣ **Queue workers handle email sending asynchronously**  

```php
public function store(BlogRequest $request)
{
    $blog = Blog::create($request->validated());
    DispatchBlogNotificationJob::dispatch($blog->id);
}
```

### 2️⃣ **Monthly Subscription Reset via Scheduled Job**
#### **Problem Statement**
- Users should get new AI article limits every month based on their subscription plan.
#### **Solution**
- Laravel Task Scheduling runs on the last day of every month.
- Free Users → Reset article count to 0.
- Subscribed Users → Reset to plan-specific limits.

```php
Schedule::command('articles:reset')
    ->when(fn() => now()->endOfMonth()->isToday())
    ->dailyAt('00:00');
```
### 3️⃣ **Role-Based Access Control (RBAC)**
#### **Problem Statement**
- Only Admins should manage categories & tags.
- Authors should only manage their own blogs.
- Guests should only read blogs.
#### **Solution**
- Implemented Policies & Middleware to enforce permissions.

```php
public function viewAny(User $user): bool
{
    return $user->role === 'admin';
}
```
### 4️⃣ **AI-Generated Blog Management**
#### **Problem Statement**
- Users should be able to generate AI blogs based on their subscription (limits applied).
#### **Solution**
- Used REST API with Laravel Sanctum for authentication.
- Enforced usage limits via middleware.

```php
if (!auth()->user()->canGenerateArticle()) {
    return response()->json(['message' => 'AI quota exceeded'], 403);
}
```

## 🔑 **Key Learnings**
- **Queues →** Handling large-scale email sending efficiently.
- **Task Scheduling →** Automating recurring backend tasks.
- **Role-Based Authorization →** Restricting admin/author access.
- **Database Optimization →** Handling large-scale blogs & subscribers efficiently.

----
## 🛠️ **Setup & Installation**
### 🔹 Prerequisites
- PHP 8.x
- Laravel 11
- Stripe API keys (for payments)
### 🔹 Installation Steps
```shell
git clone https://github.com/your-repo/blog-management.git
cd blog-management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan queue:work
php artisan serve
```
----
## ✅ Final Thoughts
This project demonstrates scalable backend architecture with modern Laravel practices. It highlights the importance of background job processing, API security, task scheduling, and subscription management in a real-world SaaS application.

