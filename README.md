پروژه مدیریت تسک‌ها (Task Management System)

مقدمه

این پروژه یک سیستم مدیریت تسک حرفه‌ای است که با استفاده از **Laravel 12** در بک‌اند و **Vue 3 + Inertia.js** در فرانت‌اند پیاده‌سازی شده است.

نصب و راه‌اندازی

✅ پیش‌نیازها

| ابزار    | نسخه پیشنهادی |
| -------- | ------------- |
| PHP      | >= 8.2        |
| Composer | آخرین نسخه    |
| Laravel  | ^12.x         |
| Node.js  | >= 18.x       |
| NPM      | نصب شده       |

---

مراحل نصب

1. دریافت سورس کد

<code>
git clone https://github.com/your-username/task-manager.git
cd task-manager
</code>

2. نصب پکیج‌های PHP

<code>
composer install
</code>

 3. تنظیم فایل env

<code>
cp .env.example .env
php artisan key:generate
</code>

ویرایش فایل `.env`:

<code>
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=yourpassword
</code>

#### 4. اجرای مایگریشن و سیدر

<code>
php artisan migrate --seed
</code>

#### 5. نصب پکیج‌های فرانت‌اند

<code>
npm install
</code>

#### 6. کامپایل فایل‌ها

<code>
npm run dev
</code>

#### 7. اجرای پروژه

<code>
php artisan serve
</code>

آدرس: `http://127.0.0.1:8000`


📂 اطلاعات اضافی – راهنمای بخش‌ها

🔐 احراز هویت

* سیستم ثبت‌نام/ورود با Breeze + Inertia.js

مدیریت تسک‌ها

* نمایش لیست
* ویرایش/حذف تسک
* مودال برای CRUD

زیرتسک‌ها

* افزودن/ حذف / ویرایش (برای اضافه کردن زیر تسک بعد نوشتن کلمه Enter را وارد دکنید
* رابط کاربری دوست‌داشتنی

🎨 استایل
* بر پایه Tailwind CSS
* ریسپانسیو

🔔 Toast ها

* پیغام موفقیت و خطا
* استفاده از vue-toastification

تست با Pest
نصب:

<code>
composer require pestphp/pest --dev
</code>

اجرا:

<code>
php artisan test
# یا
./vendor/bin/pest
</code>
🗺️ مسیرهای مهم

| مسیر         | توضیح              |
| ------------ | ------------------ |
| `/tasks`     | صفحه مدیریت تسک‌ها |
| `/dashboard` | داشبورد کاربر      |

---
