# Laravel (PHP) Authorised Event Example

## Requirements
* PHP 5.6.4 or above
* SQLite 3 or above (Or another compatible database engine)
* Composer
* NodeJS and NPM

## Setup instructions
1. Clone this repository
2. Install PHP depenencies with [composer](https://getcomposer.org), run ```composer install``` in the application folder.
3. Install JavaScript dependencies with [npm](https://www.npmjs.com/get-npm) run ```npm install``` in the application folder.
4. Copy the .env.example file to .env and fill in the CareerHub settings.
5. Create an application key by running ```php artisan key:generate``` in the application folder.
6. Configure the Database credentials in .env if relevant. By default an SQLite database in database\database.sqlite will be used.
7. Start in a PHP server

Contact CareerHub Support for more information