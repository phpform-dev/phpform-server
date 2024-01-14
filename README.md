# FlexForm - Lightweight Form Server

## Introduction

Welcome to FlexForm, a fully open-source, headless form server designed with simplicity, efficiency, and privacy in mind. Built to be easily installed on any budget-friendly hosting solution or free cloud tiers, FlexForm is the perfect choice for developers and businesses looking for a reliable and GDPR-compliant form management solution.

<img src="./public/images/screen.png" width="100%">

## Features

- **Headless Architecture**: FlexForm is built as a headless server, offering flexibility and ease of integration with various frontend systems.
- **Robust Admin Panel**: Manage your forms with ease using our user-friendly admin panel, designed for efficient and intuitive form management.
- **Cost-Effective**: Designed to run smoothly on inexpensive hosting or free cloud services, reducing your operational costs.
- **GDPR Compliant**: We prioritize your data privacy. FlexForm ensures that all your data remains yours, complying fully with GDPR regulations.
- **Open Source**: Dive into the code, customize, and contribute! Our community-driven approach means FlexForm is continually evolving.

## License

FlexForm is released under MIT, ensuring it remains free and open for use and modification.

## Getting Started

### How to run locally with docker
Checkout the repository jump to the folder and run the following commands:
```bash
docker-compose up
```
Run DB migrations from container
```bash
php bin/console doctrine:migrations:migrate
```

### How to run locally without docker
Make sure you have PHP 8.2 and composer installed.
Checkout the repository jump to the folder.
Copy environment file and adjust it to your needs:
```bash
cp .env.dev .env
```
Install dependencies:
```bash
composer install
```
Run database migrations:
```bash
php bin/console doctrine:migrations:migrate
```
Run the server:
```bash
php -S localhost:8000 -t public
```

### How to install on shared hosting
Build the project on your local machine or download prebuild release from [here](https://flexform.dev/downloads/flexform-server-latest.zip).
Upload the files to your shared hosting.

Make sure you:
- Set the environment variable `APP_ENV` to `prod` in your `.env` file. See [.env.prod](.env.prod) for an example.

### Deploy on cloud hosting
You can deploy FlexForm on any cloud hosting provider that supports PHP 8.2 or higher.
Also you can use our Dockerfile to deploy FlexForm on any cloud hosting provider that supports docker.

Make sure you:
- Set the environment variable `APP_ENV` to `prod`
- Set the environment variable `APP_SECRET` to a random string
- Set the environment variable `DATABASE_URL` to your database connection string

You can see an example of environment variables in the [.env.prod](.env.prod) file.

## Frontend components
FlexForm is a headless form server. This means that you can use any frontend framework to build your forms.
We provide a set of frontend components that you can use to build your forms.

Soon will be available components for React, Vue, Angular, Svelte and more.

Stay in touch with us on [Twitter](https://twitter.com/avshelestov) to get the latest updates.
