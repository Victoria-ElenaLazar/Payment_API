# Payment_Api


[![Build Status](https://img.shields.io/badge/Build-Passing-brightgreen)](URL)
[![Code Coverage](https://img.shields.io/badge/Coverage-100%25-yellowgreen)](URL)
[![Version](https://img.shields.io/badge/Version-1.0-blue)](URL)




Welcome to the API Slim Framework-Payment API documentation!
This API provides information about customers, customer's basket, transactions and payment methods.

This project helped me to learn and understand how to create and use a REST API using Slim Framework, Docker and ORM Doctrine.

## Table of Contents

-[Features](#features)

-[Requirements](#requirements)

-[Getting Started](#getting-started)

-[Authentication](#authentication)

-[Endpoint](#endpoints)

-[Response Format](#response-format)

-[Examples](#examples)

## [Requirements](#requirements)

- PHP 8.2
- [Composer](https://getcomposer.org/)
- [XAMPP](https://www.apachefriends.org/index.html)
- [MAMP](https://www.mamp.info/en/windows/)
- [Docker](https://www.docker.com/products/docker-desktop/)
- [Insomnia](https://insomnia.rest/download)

## Features
- **Support for Various Payment Methods:** Accept payments via credit cards, digital wallets, and more, making it convenient for your users.
- **Customizable:** Tailor the Payment API to your specific business needs with configuration options.
- **Detailed Documentation:** Easy-to-follow documentation and code examples for quick integration.


## Getting Started

To get started with this API, follow these steps:

1. **Clone the repository**: Clone this repository to your local development environment.
```bash
git clone https://github.com/Victoria-ElenaLazar/Payment_API.git
```
1 **Install dependencies**: navigate to your project root directory and install
the required dependencies using Composer.
```bash
composer install
```
2 **Configure Environment**: Create an '.env' file based on the provided '.env.example' file and configure
your environment variables, including database settings.

3 **Database Setup**: The "Model" folder contains necessary ORM Mapping. Run the following command:

```bash
docker exec -it payment_api_php bash
```
following by:

```bash
./vendor/bin/doctrine orm:schema-tool:create
```


## Authentication

This API uses JWT. To access protected endpoint, include an
**'Authorization'** header with a valid API token. First, you need to:

1 **Sign-up to get your JWT**: In order to use this application you need to register first.
Open Insomnia, select method POST using the following endpoint:
```bash
http://localhost:8000/v1/user/registration
```

Write your data in json format. Example:
```bash
{ 
    "name": "first and last name",
	"address": "address",
	"email": "youremail@email.com",
	"birthday": "your date of birth in format: 1 Jan 2000",
	"password": "choose a password"
}
```
2 **Sign in**: After generating the JWT, take the key, paste it into your environment file. With the same key
go to Insomnia or any other tool for API testing -> Headers -> add: 
```bash
Authorization        Bearer {yourSecretKey}
```

## Endpoints

Here are some of the main API endpoints you can use:

- 'GET /v1/payment-methods': Get a list of all payment methods.

- 'POST /v1/customer': Create a new customer. Using Insomnia, create a new customer in json format.

- 'PUT /v1/transaction/{id:[0-9]+}': Update a particular transaction by its ID.

- 'DELETE /v1/basket/{id:[0-9]+}' Delete a particular basket by its ID.


## Response-format

API responses are in JSON format and include relevant payment information, IDs, and status details.

Example Response:

````
{
	"type": "Success",
	"message": "payment method created successfully",
	"status": 200,
	"detail": 2,
	"instance": "/v1/payment method"
}
````

## Examples

Here are some example requests you can make using API clients like [Insomnia](https://insomnia.rest/):

1 Get a list of all customers:
```bash
GET /v1/customer
```

2 Create a new transaction:

```bash
POST /v1/transaction

Content-Type: application/json

{
    "payment_method_name": "Paypal",
    "customer_name": "first and last name",
    "basket_id": 1,
    "amount": 20.66,
    "sent": 1,
    "transaction_date": "17 Oct 2023"
}

```
3 Delete a particular basket based on its ID

```bash
DELETE /v1/basket/{id}

```


