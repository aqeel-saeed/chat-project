# Chat Project

 This is a base Laravel project that provides authentication functionality for a chat application.

## Requirements

- PHP >= 8.0 
- Composer
- MySQL or MariaDB
- Laravel 9.52.7

## Installation

1. Clone the repository to your local machine:
```cmd
git clone https://github.com/aqeel-saeed/chat-project.git
```

2. Install the project dependencies using Composer:
```cmd
cd chat-project
composer install
```

3. Create a copy of the .env.example file and rename it to .env.

4. Generate a new application key:
```
php artisan key:generate
```

5. Create a new database named 'chatProject_db' in MySQL or MariaDB, and link the project with the db.

6. Run the database migrations:
```cmd
php artisan migrate
```

7. Create The encryption keys needed to generate secure access tokens, create “personal access” and “password grant” clients which will be used to generate access tokens:
```cmd
php artisan passport:install
```

8. Start the development server:
```cmd
php artisan serve
```

You can now access the application in your web browser at http://localhost:8000.

## Usage

The application provides authentication functionality for your chat application. You can use the following routes to authenticate users:
 
- POST /login: Authenticates the user.
- POST /signup: Registers the user.
- GET /logout:  Logs out the user.

## Contributing

Contributions are welcome! If you would like to contribute to this project, please create a pull request on GitHub.

I hope this helps you get started with your Laravel chat project!
you can also see how i made this by review the diffrences between commits, the first one is an initial commit which has a fresh laravel project without any edit.
