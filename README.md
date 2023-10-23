# Payment_Api
Developing this project helped me to learn and understand how to create a REST API using Slim Framework, Docker and Doctrine.

Welcome to the API Slim Framework-Movie Details documentation!
This API provides information about movies, including details such as
title, genre, and more.

This project helped me to learn and understand how to create and use a REST API using Slim Framework.

## Table of Contents

-[Requirements](#requirements)

-[Getting Started](#getting-started)

-[Authentication](#authentication)

-[Endpoint](#endpoints)

-[Examples](#examples)

## [Requirements](#requirements)

- PHP 8.2
- [Composer](https://getcomposer.org/)
- [XAMPP](https://www.apachefriends.org/index.html)
- [MAMP](https://www.mamp.info/en/windows/)


## Getting Started

To get started with this API, follow these steps:

1. **Clone the repository**: Clone this repository to your local development environment.
```bash
git clone https://github.com/Victoria-ElenaLazar/SlimAPI-MovieDetails.git
```
1 **Install dependencies**: navigate to your project root directory and install
the required dependencies using Composer.
```bash
composer install
```
2 **Configure Environment**: Create a '.env' file based on the provided '.env.example' file and configure
your environment variables, including database settings.

3 **Database Setup**: Set up your database and import the movie details data.

4 **Configure a Built-In Server**: Before you can run the API, you need to set up
a built-in web server. Open a terminal and navigate to the project's root directory:
```bash
cd /path/to/api-slim-movie-details
```
5 **Start the PHP server:**
```bash
php -S localhost:8080 -t public
```

## Authentication

This API uses token-based authentication. To access protected endpoint, include an
**'Authorization'** header with a valid API token:

```bash
user2Token
```

## Endpoints

Here are some of the main API endpoints you can use:

- 'GET /v1/movies': Get a list of all movies.

- 'POST /v1/movies': Create a new movie. Using Insomnia, create a movie in json format.

- 'PUT /v1/movies/{id:[0-9]+}': Update a particular movie by its ID.

- 'DELETE /v1/movies/{id:[0-9]+}' Delete a particular movie by its ID.

## Examples

Here are some example requests you can make using API clients like [Insomnia](https://insomnia.rest/):

1 Get a list of all movies:
```bash
GET /v1/movies
```

2 Create a new movie:

```bash
POST /v1/movies

Content-Type: application/json

{
"title": "New Movie",
		"year": 2013,
		"released": "13 Sep 2015",
		"runtime": "80 min",
		"genre": "Comedy",
		"director": "Director name",
		"actors": "Actor 1, Actor 2",
		"country": "United States",
		"poster": "URL to Poster Image",
		"imdb": 8.4,
		"type": "Movie Type"
}

```
3 Delete a particular movie based on its ID

```bash
DELETE /v1/movies/{id}

```


