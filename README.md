# [POS Shop System Laravel Project](http://pos-shop.saico.rf.gd/)
![Screenshot 2023-03-22 170026](https://user-images.githubusercontent.com/83503164/227045208-a37ef277-0c8d-4da8-a605-c27068909382.png)
# Getting started
## Installation

Clone the repository
```
https://github.com/saicOo/laravel-POS-system.git
```
Switch to the repo folder
```
cd pos_shop_laravel
```
Install all the dependencies using composer
```
composer install
```
Copy the example env file and make the required configuration changes in the .env file
```
cp .env.example .env
```
Generate a new application key
```
php artisan key:generate
```
Run the database migrations (Set the database connection in .env before migrating)
```
php artisan migrate --seed
```
Start the local development server
```
php artisan serve
```

