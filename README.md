# 🎂 BMAMS - Birth Month Association Management System

> *"Because even your birthday deserves better project management than your last sprint."*

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
[![CodeIgniter 4](https://img.shields.io/badge/CodeIgniter-4.7.3-red.svg)](https://codeigniter.com)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](http://makeapullrequest.com)

---

## 📖 Table of Contents

- [About The Project](#-about-the-project)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Configuration](#-configuration)
- [Default Credentials](#-default-credentials)
- [Project Structure](#-project-structure)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 About The Project

**BMAMS** (pronounced *"bee-mams"*) is a fully configurable, retro/vintage-themed association management system designed specifically for birth month organizations. Whether you're the **September Born Association of Liberia**, the **October Born Club**, or any other month-based group, BMAMS adapts to your needs without a single line of code change.

### 🎭 The Origin Story

Born out of the need to manage the **September Born Association of Liberia**'s growing membership, BMAMS evolved into a robust, multi-tenant-ready platform. It's built with love (and a few late-night debugging sessions) by developers who believe that even legacy systems deserve a touch of vintage charm.

> *"In the world of software, we're all born in some month. Some of us just debug better than others."*

---

## ✨ Features

### 🏠 Frontend Features

| Feature | Description |
|---------|-------------|
| 🎨 **Dynamic Hero Section** | Automatically displays association name and motto from settings |
| 🎂 **Today's Birthday Stars** | Shows members celebrating their birthday today |
| 📅 **Birthday Calendar** | Interactive calendar with month/week/day views |
| 📊 **Birth Month Progress Tracker** | Animated progress bar showing month completion |
| 🧱 **Birthday Wall** | Member-submitted birthday wishes with admin approval |
| ⭐ **Member Spotlight** | Featured member slider using Swiper.js |
| 📜 **Association Timeline** | Interactive history of the association |
| 👔 **Leadership Showcase** | Executive committee members with bios |
| 📰 **News & Announcements** | Full CMS for association news |
| 🖼️ **Gallery** | Photo albums with vintage polaroid-style display |
| 📇 **Member Directory** | Searchable directory with filters |

### 🔧 Admin Features

| Feature | Description |
|---------|-------------|
| 📊 **Dashboard** | Statistics cards and charts with Chart.js |
| ⚙️ **Website Settings** | Association name, month, colors, logos, SMTP, SEO |
| 🎨 **Theme Builder** | Live preview of primary/secondary/accent colors |
| 👥 **Member Management** | Full CRUD, approval, export, email |
| 👔 **Executive Management** | CRUD with drag-and-drop reorder |
| 📅 **Event Management** | CRUD, RSVP tracking, countdown timers |
| 📰 **News Management** | CRUD with scheduled publishing |
| 🖼️ **Gallery Management** | Album and photo upload |
| 💬 **Birthday Wishes** | Approve/delete member wishes |
| 📄 **Page Management** | Manage static pages |
| 🖼️ **Banner Management** | Homepage slider control |
| 📢 **Announcements** | Dismissible site-wide announcements |
| 💌 **Messages** | View contact form submissions |
| 📋 **Audit Logs** | Track admin actions |
| 👤 **Roles & Permissions** | Super Admin, Admin, Editor, Member |
| 📊 **Reports** | Export members, birthdays, events |

### 🧠 Smart Birthday Engine

| Feature | Description |
|---------|-------------|
| 🎯 **Daily Detection** | Automatically shows today's birthdays |
| 📆 **Monthly Detection** | Shows all birthdays for the configured month |
| ✅ **Registration Validation** | Only allows registration if birth month matches configured month |
| 🔓 **Admin Override** | Admin can bypass month validation |
| 🎉 **Birthday Confetti** | CSS/JS animation on member's birthday |

---

## 🛠️ Tech Stack

### Backend
- **PHP** 7.4+
- **CodeIgniter 4** - MVC Framework
- **MySQL** 5.7+
- **Session** - File-based (configurable)

### Frontend
- **Tailwind CSS** - Utility-first CSS
- **Playfair Display** - Elegant serif font
- **Merriweather** - Readable serif font
- **Courier Prime** - Monospace for labels
- **Google Material Symbols** - Icon library
- **Chart.js** - Charts and graphs
- **Swiper.js** - Sliders and carousels

### Design Philosophy
The UI follows a **retro/vintage archival aesthetic** with:
- 📄 Paper textures and grain overlays
- 🖼️ Polaroid-style image frames
- 📎 Stamp and letterpress effects
- 📑 Index card / ledger line backgrounds
- 🔄 Subtle card rotations
- 🌓 Dark/light mode toggle

---

## 📦 Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/bmams.git
cd bmams
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

Copy the example environment file:

```bash
cp env .env
```

Update the `.env` file with your database credentials:

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### Step 4: Generate Encryption Key

```bash
php spark key:generate
```

### Step 5: Run Migrations

```bash
php spark migrate
```

### Step 6: Seed the Database

```bash
php spark db:seed DatabaseSeeder
```

### Step 7: Set File Permissions

```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### Step 8: Run the Development Server

```bash
php spark serve
```

The application will be available at: `http://localhost:8080`

---

## 🗄️ Database Setup

### Creating the Database

```sql
CREATE DATABASE bmams CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Running Migrations

```bash
php spark migrate
```

### Rolling Back (if needed)

```bash
php spark migrate:rollback
```

### Seeding Data

```bash
php spark db:seed DatabaseSeeder
```

---

## ⚙️ Configuration

### Association Settings

After installation, log in as admin and navigate to **Settings** > **General** to configure:

| Setting | Description | Example |
|---------|-------------|---------|
| Association Name | Name of your organization | September Born Association of Liberia |
| Birth Month | Configured birth month | September |
| Birth Month Number | Month number (1-12) | 9 |
| Motto | Association motto | Unity Through Birth |
| Vision | Association vision statement | A united Liberia... |
| Mission | Association mission statement | To unite individuals... |

### Theme Settings

Navigate to **Settings** > **Branding** to customize:

| Setting | Description | Example |
|---------|-------------|---------|
| Primary Color | Main brand color | #1a365d |
| Secondary Color | Accent color | #c79a3d |
| Logo | Association logo | upload image |
| Favicon | Browser favicon | upload image |

---

## 🔐 Default Credentials

### Admin Access

| Field | Value |
|-------|-------|
| **Email** | `admin@bmams.org` |
| **Password** | `admin123` |
| **Role** | Super Admin |

### Member Access

| Field | Value |
|-------|-------|
| **Email** | Any member email from seeder |
| **Password** | `member123` |
| **Role** | Member |

---

## 📁 Project Structure

```
bmams/
├── app/
│   ├── Config/
│   │   ├── Autoload.php
│   │   ├── Database.php
│   │   ├── Routes.php
│   │   └── ...
│   ├── Controllers/
│   │   ├── Home.php
│   │   ├── Auth.php
│   │   ├── Members.php
│   │   ├── Events.php
│   │   ├── News.php
│   │   ├── Gallery.php
│   │   ├── Birthday.php
│   │   ├── Contact.php
│   │   └── Admin/
│   │       ├── Dashboard.php
│   │       ├── Settings.php
│   │       ├── Members.php
│   │       ├── Executives.php
│   │       ├── Events.php
│   │       ├── News.php
│   │       ├── Gallery.php
│   │       ├── Wishes.php
│   │       ├── Pages.php
│   │       ├── Banners.php
│   │       ├── Announcements.php
│   │       ├── Messages.php
│   │       ├── Audit.php
│   │       ├── Roles.php
│   │       └── Reports.php
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── 2026-06-05-055840_CreateSettingsTable.php
│   │   │   ├── 2026-06-05-060025_CreateMembersTable.php
│   │   │   └── ...
│   │   └── Seeds/
│   │       └── DatabaseSeeder.php
│   ├── Models/
│   │   ├── MemberModel.php
│   │   ├── EventModel.php
│   │   ├── NewsModel.php
│   │   └── ...
│   ├── Libraries/
│   │   ├── BirthdayEngine.php
│   │   ├── SettingsService.php
│   │   └── ThemeManager.php
│   ├── Helpers/
│   │   └── settings_helper.php
│   └── Views/
│       ├── layouts/
│       │   ├── header.php
│       │   ├── sidebar.php
│       │   └── footer.php
│       ├── home/
│       ├── members/
│       ├── events/
│       ├── news/
│       ├── gallery/
│       ├── birthday/
│       └── admin/
├── public/
│   └── index.php
├── writable/
│   ├── cache/
│   ├── logs/
│   ├── sessions/
│   └── uploads/
├── vendor/
├── .env
├── composer.json
└── spark
```

---

## 📸 Screenshots

> Screenshots coming soon! The system features a beautiful retro/vintage design with:
>
> - 📄 Paper-textured backgrounds
> - 🖼️ Polaroid-style photo cards
> - 📎 Stamp and letterpress effects
> - 🎨 Configurable color themes
> - 🌓 Dark/light mode toggle

---

## 🤝 Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

### How to Contribute

1. **Fork the Project**
2. **Create your Feature Branch**
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. **Commit your Changes**
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```
4. **Push to the Branch**
   ```bash
   git push origin feature/AmazingFeature
   ```
5. **Open a Pull Request**

### Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Write clean, maintainable code

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- The **September Born Association of Liberia** for inspiring this project
- The CodeIgniter team for an amazing framework
- All contributors who make open-source software possible
- Coffee ☕ and late-night debugging sessions

---

## 🎯 Roadmap

- [ ] Two-Factor Authentication
- [ ] Email Notifications (SMTP fully configured)
- [ ] API endpoints for mobile apps
- [ ] Advanced reporting and analytics
- [ ] Multi-language support
- [ ] Payment integration for membership dues
- [ ] Mobile responsive PWA

---

## 📞 Contact

**Project Maintainer**: [Your Name]
**Email**: [your.email@example.com]
**Project Link**: [https://github.com/yourusername/bmams](https://github.com/yourusername/bmams)

---

## ⭐ Show Your Support

If you found this project helpful, please give it a ⭐ on GitHub! It helps others discover the project and motivates us to keep improving.

---

> *"Software is like a birthday cake – it's best when shared with others."* 🎂

---

**Made with ❤️ and a lot of ☕ by developers who believe every birth month deserves a great management system.**