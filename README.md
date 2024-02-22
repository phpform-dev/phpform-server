# PHPForm - Lightweight Headless Form Builder and API

Welcome to PHPForm, a fully open-source, headless form builder designed with simplicity, efficiency, and privacy in mind. 
Built to be easily installed on any budget-friendly hosting solution or free cloud tiers, PHPForm is the perfect choice for 
developers and businesses looking for a reliable and GDPR-compliant form management solution.

<img src="./public/images/screen.png" width="100%">

# Features

- **Headless Architecture**: PHPForm is built as a headless server, offering flexibility and ease of integration with various frontend systems.
- **Robust Admin Panel**: Manage your forms with ease using our user-friendly admin panel, designed for efficient and intuitive form management.
- **Cost-Effective**: Designed to run smoothly on inexpensive hosting or free cloud services, reducing your operational costs.
- **GDPR Compliant**: We prioritize your data privacy. PHPForm ensures that all your data remains yours, complying fully with GDPR regulations.
- **Browser Push and Email notifications**: Get notified when a new form submission is received.
- **reCaptcha Protection**: Protect your forms from spam and abuse with Google reCaptcha.
- **Token-based Protection**: Ideal protection for mobile and desktop apps.

# Requirements
### PHP Version
- PHP 8.2 or higher.

### PHP Extensions
- GD
- ZIP
- XML

### Supported Databases
- SQLite
- MySQL
- PostgreSQL
- MariaDB
- AuroraDB

# License

PHPForm is released under MIT, ensuring it remains free and open for use and modification.

# Getting Started

 - [Deploying on cloud hosting](#deploying-on-cloud-hosting)
 - [Deploying on shared hosting](#deploying-on-shared-hosting)
 - [Running locally with docker](#running-locally-with-docker)
 - [Running locally without docker](#running-locally-with-php-server)

 - [Deploy instructions](#deploy-instructions)


## Running locally with docker
Use the latest [image from docker hub](https://hub.docker.com/r/phpform/phpform-server) to run it locally:
```bash
docker run --name phpform -d -p 9000:9000 phpform/phpform-server:0.2
```
Copy environment file and adjust it to your needs:
```bash
cp .env.prod .env
```
Run DB migrations from container
```bash
docker exec -it phpform bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Running locally with PHP server
Make sure you have PHP 8.2 and composer installed.
Checkout the repository jump to the folder.
Copy environment file and adjust it to your needs:
```bash
cp .env.prod .env
```
Install dependencies:
```bash
composer install
```
Run database migrations:
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```
Run the server:
```bash
php -S localhost:8000 -t public
```

## Deploying on shared hosting
Build the project on your local machine or [download prebuild release](https://phpform.nyc3.cdn.digitaloceanspaces.com/phpform-server-latest.zip).
Upload the files to your shared hosting.
Make sure you make "public" folder as your main folder.

## Deploying on cloud hosting
You can deploy PHPForm on any cloud hosting provider that supports PHP 8.2 or higher.
Also you can use [PHPForm Docker Image](https://hub.docker.com/r/phpform/phpform-server) to deploy PHPForm on any cloud hosting provider that supports docker.

Make sure you:
- Set the environment variable `APP_ENV` to `prod`
- Set the environment variable `APP_SECRET` to a random string
- Set the environment variable `DATABASE_URL` to your database connection string

Copy environment file with default values:
```bash
cp .env.prod .env
```

**Please make sure you made /app/var as persistent storage if you use SQLite. If you want to use other database please change DATABASE_URL env variable.**

Run database migrations:
```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Troubleshooting
#### Vendor folder is missing
If you see an error that the vendor folder is missing, you need to install the dependencies.
```
Warning: require_once(/app/vendor/autoload_runtime.php): Failed to open stream: No such file or directory in /app/public/index.php on line 5
```
The main problem usually when you run composer install from the root user.
If you still want to run composer install from the root user, you can use the following command:
```bash
COMPOSER_ALLOW_SUPERUSER=1 composer install
```



## Deploy instructions
 - [Installing PHPForm on DigitalOcean Apps](https://medium.com/@ashelestov/installing-flexform-on-digitalocean-apps-b3e5b1ba868a)

# Frontend components
PHPForm is a headless form server. This means that you can use any frontend framework to build your forms.
We provide a set of frontend components that you can use to build your forms.

Soon will be available components for React, Vue, Angular, Svelte and more.

Stay in touch with us on [Twitter](https://twitter.com/avshelestov) to get the latest updates.
