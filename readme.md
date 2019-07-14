# Gliding New Zealand Web App Platform

The GNZ web app platform's goal is to provide a platform for anyone to build apps, while maintaining a consistent experience for end users. The apps can be used by any gliding club. Contributors are welcome, either to help work on existing apps, or build entirely new apps.

Built on Laravel 5.8

## Installation

### Requirements

- MySQL
- Apache
- PHP 7.2 or something?
- Git https://git-scm.com
- Composer https://getcomposer.org

### Download the code

`git clone https://github.com/glidingnz/58gliding.net.nz.git`

### Create Databases

Create two MySQL databases. The '58glidernet' is the app platform database. In MySQL:

`CREATE DATABASE ogn`

`CREATE DATABASE 58glidernet`

Ensure you have users configured that can access those databases.


### Laravel Install steps

1. Clone the example .env file `cp .env.example .env`. Edit with your database connection details for each database. Change homestead to 58glidernet
2. In the project directory `composer install`
3. Give the uploads/ directory write-access by the web server: `chown -R www-data:www-data public`
4. Change the permissions of the storage/ directory: `chmod -R 777 storage/`
5. Change the permissions of the bootstrap cache directory: `chmod -R 777 bootstrap/cache`
6. Create a Laravel app key `php artisan key:generate`
7. Migrate the databases `php artisan migrate`. If this doesn't work, check your .env file.
8. Install demo seed data `php artisan db:seed`. This will insert a random assortment of users, and other data.
9: Run the `php artisan passport:install` command to install the Passport Oauth keys

### Javascript/CSS build instructions

Instructions from https://laravel.com/docs/5.8/mix

1. Install npm and node 
2. Install what's required for Laravel with `npm install` in the project directory
3. Run `npm run dev` to compile CSS and Javascript once
4. Run `npm run watch` to watch for CSS and Javascript changes while developing

### Configure Apache for Virtual Hosts

1. Read the Laravel instructions here https://laravel.com/docs/5.8
2. Set up Apache virtual host as follows

```
<VirtualHost *:80>
   DocumentRoot /Users/tim/Sites/58.gliding.net.nz/public
   ServerName 58gliding.net.test
   ServerAlias *.58gliding.net.test
</VirtualHost>
```

3. Sub sites use a subdomain e.g. piako.58gliding.net.test. Either set up a local DNS server to serve up the .test domain name OR much easier, set up  your hosts file to point to a couple of sub sites for testing e.g. 

```
127.0.0.1 58gliding.net.test
127.0.0.1 piako.58gliding.net.test
127.0.0.1 auckland.58gliding.net.test
```

## Login with the default user

A default root user is setup with the following username/password

	root@gliding.co.nz
	root

This will allow you to give roles and permissions to other users in your local system.

### Apple Maps

To test Apple Maps used on the tracking page, you need a key, installed the .env file. Either create one if you're a paid up apple developer, or contact Tim to get one.


## License

The Laravel framework & GNZ Web App Platform is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

