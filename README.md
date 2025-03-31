# Queensland Ladder

This project is a collaborative initiative between clubs to create an interactive ladder ratings system of table tennis players in Queensland, utilising the RatingsCentral system. This project is not affiliated with Table Tennis Queensland (TTQ), [please visit their website](https://www.tabletennisqld.org/eventsttq/rankings) for more info about rankings

## Development

To set up the development environment:

```bash
composer install
npm install
```

To start the local development server:

```bash
php artisan serve
```

## Features

- Interactive ladder ranking system for Queensland table tennis players
- Integration with RatingsCentral rating system
- Category filtering
- User registration and authentication
- Admin dashboard for managing players and rankings

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS, Livewire
- **Database**: sqlite
- **Authentication**: Laravel Breeze
- **Admin Panel**: CoreUI

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env` and configure your database settings
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```
6. Compile assets:
   ```bash
   npm run build
   ```
7. Start the development server:
   ```bash
   php artisan serve
   ```

## Contributing

Contributions are welcome! This project is a collaborative effort between Queensland table tennis clubs.

## License

This project is licensed under the [GNU General Public License v3.0](LICENSE.md).
