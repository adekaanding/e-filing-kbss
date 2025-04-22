# e-Filing KBSS - Project Architecture

## 1. System Overview

**e-Filing KBSS** is a digital file borrowing system designed for Kementerian Belia dan Sukan Sabah (KBSS) to streamline file tracking, improve record-keeping, and reduce manual paperwork. The system digitalizes the traditionally paper-based file tracking process while maintaining the existing staff-to-officer interaction paradigm.

**Core Objectives:**
- Replace manual file borrowing records with a digital system
- Provide real-time tracking of file status and location
- Streamline the file borrowing workflow
- Generate automatic notifications for overdue files
- Create a searchable historical record of all file transactions

## 2. Development Environment

### Local Development
- **Laragon**: Comprehensive local development environment
  - PHP Version: 8.2+
  - MySQL Version: 8.0+
  - Nginx for local server

### Version Control
- **Git**: All code changes will be tracked using Git
- **Repository Structure**: Follows Laravel's standard directory structure
- **Branching Strategy**: 
  - `main`: Production-ready code
  - `development`: Integration branch for feature testing
  - `feature/{feature-name}`: Individual feature branches

### Environmental Configuration
- `.env` file for environment-specific configurations
- Separate configurations for development, testing, and production environments

## 3. Technology Stack

### Backend Framework
- **Laravel 10+**: PHP-based MVC framework
  - Artisan command-line tool for scaffolding and migrations
  - Eloquent ORM for database interactions
  - Blade templating for server-side rendering

### Frontend Technologies
- **Tailwind CSS**: Utility-first CSS framework for responsive design
- **Alpine.js**: Lightweight JavaScript framework for DOM manipulation
- **Livewire**: Full-stack framework that makes building dynamic interfaces simple

### Database
- **MySQL**: Relational database management system
  - InnoDB storage engine for transaction support
  - UTF-8 character encoding

### Authentication
- Laravel's built-in authentication system
- Custom middleware for role-based access control

## 4. Architectural Patterns

### MVC Architecture
- **Models**: Represent database tables and business logic
- **Views**: Blade templates enhanced with Livewire and Alpine.js
- **Controllers**: Handle HTTP requests and application flow

### Repository Pattern
- Implement repository interfaces for all data access
- Separate business logic from data access logic
- Facilitate unit testing through dependency injection

### Service Layer
- Implement service classes for complex business logic
- Maintain separation of concerns between controllers and repositories

### SOLID Principles
- **Single Responsibility**: Each class has one responsibility
- **Open/Closed**: Open for extension, closed for modification
- **Liskov Substitution**: Derived classes must be substitutable for base classes
- **Interface Segregation**: Many client-specific interfaces over one general-purpose interface
- **Dependency Inversion**: Depend on abstractions, not concretions

## 5. Database Design

### Entity Relationship Diagram

```
+---------------+       +----------------+       +---------------+
| Department    |       | File           |       | User          |
+---------------+       +----------------+       +---------------+
| id            |       | id             |       | id            |
| name          |       | reference_no   |       | name          |
| created_at    |       | title          |       | password      |
| updated_at    |<----->| department_id  |       | role          |
+---------------+       | status         |       | created_at    |
                        | created_at     |       | updated_at    |
                        | updated_at     |       +---------------+
                        +----------------+             ^
                               ^                       |
                               |                       |
                        +----------------+             |
                        | Borrowing      |             |
                        +----------------+             |
                        | id             |             |
                        | file_id        |<------------+
                        | borrower_name  |
                        | borrow_date    |
                        | return_date    |
                        | officer_id     |<------------+
                        | status         |
                        | created_at     |
                        | updated_at     |
                        +----------------+
```

### Tables

#### departments
- `id`: Primary key
- `name`: Department name (e.g., "Bahagian Belia", "Sukan", "Digital")
- timestamps

#### files
- `id`: Primary key
- `reference_no`: File reference number (unique)
- `title`: File title/description
- `department_id`: Foreign key to departments
- `status`: Current file status (Available, Dalam Pinjaman, Belum Dikembalikan)
- timestamps

#### users
- `id`: Primary key
- `name`: Username for login
- `password`: Hashed password
- `role`: User role (File Admin, File Officer)
- timestamps

#### borrowings
- `id`: Primary key
- `file_id`: Foreign key to files
- `borrower_name`: Name of staff borrowing the file
- `borrow_date`: Date file was borrowed
- `return_date`: Date file was returned (nullable)
- `officer_id`: Foreign key to users (officer who processed the request)
- `status`: Status of borrowing (Dalam Pinjaman, Dikembalikan, Belum Dikembalikan)
- timestamps

## 6. Application Structure

### Routes
- **Web Routes** (`routes/web.php`): All application routes
  - Authentication routes
  - Dashboard routes
  - File management routes
  - Borrowing management routes

### Models
- **User**: Represents system users (admins and officers)
- **Department**: Represents organizational departments
- **File**: Represents physical files that can be borrowed
- **Borrowing**: Represents file borrowing transactions

### Controllers
- **AuthController**: Handles authentication
- **DashboardController**: Manages dashboard view and data
- **FileController**: Manages file records
- **BorrowingController**: Manages borrowing transactions
- **HistoryController**: Manages transaction history

### Middleware
- **Authenticate**: Verifies user is logged in
- **RoleMiddleware**: Verifies user has appropriate role
- **RedirectIfAuthenticated**: Redirects logged-in users

### Views
- **layouts/app.blade.php**: Main application layout
- **auth/login.blade.php**: Login page
- **dashboard/index.blade.php**: Dashboard view
- **files/index.blade.php**: File listing page
- **borrowings/form.blade.php**: Borrowing form
- **history/index.blade.php**: History page

### Livewire Components
- **Dashboard**: Dashboard statistics cards and file table
- **FileTable**: Dynamic file listing with filters
- **BorrowingForm**: Dynamic form for registering file borrowings
- **HistoryTable**: Borrowing history with search and filters

## 7. Authentication and Authorization

### Authentication System
- Form-based authentication using Laravel's built-in authentication
- Session-based authentication with CSRF protection
- Password hashing using bcrypt

### Authorization
- Role-based access control:
  - **File Admin**: Full access to all system functions
  - **File Officer**: Limited access to borrowing functions

### Access Control Matrix

| Feature                    | File Admin | File Officer |
|----------------------------|------------|--------------|
| View Dashboard             | ✅         | ✅           |
| Manage Files               | ✅         | ❌           |
| Register Borrowings        | ✅         | ✅           |
| Update Borrowing Status    | ✅         | ✅           |
| View History               | ✅         | ✅           |
| Edit/Delete Records        | ✅         | ❌           |
| User Management            | ✅         | ❌           |

## 8. User Workflows

### File Borrowing Workflow

1. Staff (Peminjam) approaches File Officer in person to request file(s)
2. File Officer logs into the system
3. Officer navigates to the Pinjaman File form
4. Officer enters borrower name, selects department
5. System displays available files for selected department
6. Officer selects one or more files
7. Officer submits the form
8. System records the transaction and updates file status to "Dalam Pinjaman"

### File Return Workflow

1. Staff returns file(s) to File Officer
2. Officer logs into the system
3. Officer navigates to Dashboard or History page
4. Officer locates the borrowing record
5. Officer updates status to "Dikembalikan"
6. System records return date and updates file status to "Available"

### Late Return Handling

1. System automatically identifies files not returned after 7 days
2. File status automatically changes to "Belum Dikembalikan"
3. Dashboard displays overdue files for officer attention
4. When returned, system calculates days overdue

## 9. Interface Design

### Layout Components

#### Sidebar
- Logo at the top
- Navigation links:
  - Dashboard
  - Sejarah Peminjaman File
  - Pinjaman File Form
  - File Management (Admin only)
  - User Management (Admin only)

#### Header
- Welcome message with user name
- User avatar with dropdown for:
  - My Profile
  - Logout

#### Main Content Area
- Page title
- Action buttons (where applicable)
- Content specific to each page

### Color Coding System
- **Available** (Green): #10B981
- **Dalam Pinjaman** (Yellow): #F59E0B
- **Belum Dikembalikan** (Red): #EF4444

### Responsive Design
- Mobile-first approach using Tailwind CSS
- Breakpoints:
  - sm: 640px
  - md: 768px
  - lg: 1024px
  - xl: 1280px

## 10. Performance Considerations

### Database Optimization
- Appropriate indexing on frequently queried columns
- Eager loading of relationships to prevent N+1 query problems
- Query caching for frequently accessed data

### Frontend Performance
- Minimize JavaScript bundle size
- Lazy loading of components where appropriate
- Image optimization

### Caching Strategy
- Cache dashboard statistics
- Cache department and file lists
- Use Laravel's built-in cache system with file driver for local development and Redis for production

## 11. Testing Strategy

### Unit Testing
- PHPUnit for testing individual components
- Test all models and repositories
- Mock database connections where appropriate

### Feature Testing
- Test complete user workflows
- Ensure all routes return correct responses
- Test authorization rules

### Browser Testing
- Laravel Dusk for browser automation testing
- Test critical user interfaces and interactions
- Ensure responsive design works as expected

## 12. Deployment Strategy

### Server Requirements
- PHP 8.2+
- MySQL 8.0+
- Nginx or Apache web server
- Composer for dependency management

### Deployment Process
1. Clone repository from Git
2. Install dependencies with Composer
3. Configure environment variables
4. Run database migrations
5. Compile assets with npm
6. Configure web server
7. Set appropriate permissions

### Backup Strategy
- Daily database backups
- Store backups in secure, offsite location
- Regular testing of backup restoration

## 13. Maintenance Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel naming conventions
- Document all code with appropriate comments

### Git Workflow
- Develop features in feature branches
- Pull requests for code review
- Merge to development for integration testing
- Merge to main for production deployment

### Logging
- Log all significant system events
- Use Laravel's built-in logging system
- Configure log rotation to prevent disk space issues

## 14. References and Resources

### Laravel Documentation
- [Laravel Official Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://laravel-livewire.com/docs)

### Tailwind CSS Resources
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind UI Components](https://tailwindui.com/)

### Alpine.js Resources
- [Alpine.js Documentation](https://alpinejs.dev/start-here)

### Design Patterns
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [SOLID Principles in PHP](https://www.digitalocean.com/community/tutorials/solid-object-oriented-design-principles-in-php)

---

This architecture document serves as the foundational blueprint for the e-Filing KBSS system development. It provides comprehensive guidance for implementing the system according to established best practices while maintaining alignment with the specific requirements of KBSS. All development work should adhere to this architecture to ensure consistency, maintainability, and scalability of the final system.