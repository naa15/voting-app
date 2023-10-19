## Build a Voting App

This is a practice project made for learning purposes. It follows the **"Build a Voting App"** series on Laracasts: https://laracasts.com/series/build-a-voting-app by Andre Madarang.

The app allows you to create ideas, vote and comment on them, sort and filter the results, and even administer the site. The project also includes feature and unit tests.


## Installation

1. Clone the repo and `cd` into it
1. `composer install`
1. Rename or copy `.env.example` file to `.env`
1. `php artisan key:generate`
1. Setup a database and add your database credentials in your `.env` file
1. `php artisan migrate` or `php artisan migrate --seed` if you want seed data
1. `npm install`
1. `npm run dev` or `npm run watch`
1. `php artisan serve` or use Laravel Valet
1. Visit `localhost:8000` in your browser