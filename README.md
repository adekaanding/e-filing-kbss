# e-Filing KBSS - Digital File Borrowing System

## Project Overview

**e-Filing KBSS** is a digital file borrowing system designed for Kementerian Belia dan Sukan Sabah (KBSS) to streamline file tracking, improve record-keeping, and reduce manual paperwork. The system digitalizes the traditionally paper-based file tracking process while maintaining the existing staff-to-officer interaction paradigm.

## Core Objectives

- Replace manual file borrowing records with a digital system
- Provide real-time tracking of file status and location
- Streamline the file borrowing workflow
- Generate automatic notifications for overdue files
- Create a searchable historical record of all file transactions

## Technology Stack

- **Backend**: Laravel 10+
- **Frontend**: Tailwind CSS, Alpine.js, Livewire
- **Database**: MySQL

## Development Setup

1. Clone this repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure your environment
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Seed the database (optional): `php artisan db:seed`
7. Start the development server: `php artisan serve`

## Branching Strategy

- `main`: Production-ready code
- `development`: Integration branch for feature testing
- `feature/{feature-name}`: Individual feature branches

## Documentation

For more detailed information about the project architecture and implementation, please refer to the project documentation.