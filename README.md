# Perfect Smile - Dental Clinic Management System

A comprehensive dental clinic management system built with CodeIgniter 4, featuring multi-role user management, patient management, appointment scheduling, and modern UI.

## Features

- 🏥 **Multi-Role Management**: Admin, Doctor, Staff, and Patient roles
- 👥 **Patient Management**: Add, view, edit, and manage patient records
- 📅 **Appointment Scheduling**: Book and manage appointments
- 🎨 **Modern UI**: Beautiful, responsive design with slide-in panels
- 🔐 **Secure Authentication**: Role-based access control
- 📱 **Mobile Responsive**: Works perfectly on all devices

## Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/perfect-smile.git
cd perfect-smile
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

Copy the environment file:
```bash
cp env .env
```

Edit `.env` file and configure your database:
```env
database.default.hostname = localhost
database.default.database = perfect_smile
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
```

### 4. Database Setup

Create a new MySQL database:
```sql
CREATE DATABASE perfect_smile;
```

Run the migrations:
```bash
php spark migrate
```

Seed the database with initial data:
```bash
php spark db:seed UserSeeder
```

### 5. Fix User Passwords (Important!)

Run the password fix command to properly hash user passwords:
```bash
php spark auth:fix-passwords
```

### 6. Start the Development Server

```bash
php spark serve
```

The application will be available at `http://localhost:8080`

## Default Login Credentials

After running the seeder and password fix, you can login with:

### Admin
- **Email**: admin@perfectsmile.com
- **Password**: admin123

### Doctor
- **Email**: doctor@perfectsmile.com
- **Password**: doctor123

### Staff
- **Email**: staff@perfectsmile.com
- **Password**: staff123

### Patient
- **Email**: patient@perfectsmile.com
- **Password**: patient123

## Project Structure

```
perfect-smile/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/         # Database models
│   ├── Views/          # View templates
│   └── Database/       # Migrations and seeders
├── public/             # Public assets (CSS, JS, images)
├── vendor/             # Composer dependencies
└── writable/           # Logs and cache
```

## Key Features

### Patient Management
- Add new patients with comprehensive information
- View patient details in modern slide-in panels
- Edit patient information
- Toggle patient status (active/inactive)

### Modern UI Components
- Slide-in panels for forms and details
- Modern date picker with Flatpickr
- Responsive design for all screen sizes
- Beautiful purple theme

### Role-Based Access
- **Admin**: Full access to all features
- **Staff**: Patient management and basic features
- **Doctor**: Medical-specific features (to be implemented)
- **Patient**: Personal dashboard and appointments

## Troubleshooting

### Icons Not Showing
If Font Awesome icons are not displaying:
1. Check your internet connection (CDN is used)
2. Clear browser cache
3. Ensure no ad blockers are blocking CDN resources

### Database Connection Issues
1. Verify database credentials in `.env`
2. Ensure MySQL service is running
3. Check database permissions

### Permission Issues
If you encounter permission errors:
```bash
chmod -R 755 writable/
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please open an issue on GitHub.

---

**Perfect Smile** - Making dental clinic management simple and efficient! 🦷✨
