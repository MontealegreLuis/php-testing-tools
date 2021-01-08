# PHP Testing tools

[![Build Status](https://travis-ci.org/MontealegreLuis/php-testing-tools.svg?branch=master)](https://travis-ci.org/MontealegreLuis/php-testing-tools)
[![Code Climate](https://codeclimate.com/github/MontealegreLuis/php-testing-tools/badges/gpa.svg)](https://codeclimate.com/github/MontealegreLuis/php-testing-tools)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](LICENSE)

This repository is a small Web application with a single feature with a single scenario. 
Its goal is to provide a set of examples using some of the coolest tools for testing and design in PHP.

* [Behat][4]
* [phpspec][5]
* [PHPUnit][6]
* [Mockery][7]
* [Alice][8]
* [Codeception][9]
* [Eris][10]
* [Infection][11]

It was used for a testing [class][1]. The slides are available [here][2].

I'm also starting to use it to describe an [hexagonal architecture][12] in PHP.

## Installation

*It is recommended to use [Docker][16] and [Docker Compose][17] to run this example.
However, it is also possible to install all the required software locally and run it without the containers.* 

All instructions below assume a Docker setup. 

```bash
make containers     # Build the Docker images
source .alias       # Aliases to ease the use of the Docker containers
dev make bootstrap  # Install Composer dependencies for all applications
```

You may need a [Github token][14] as you will be running composer install for multiple applications.
Please follow Composer documentation on [OAuth tokens][18] to use your newly created token with Composer.
No need to do anything with your `dev` container, it is configured to share your local Composer configuration.

In order to run the application you'll need to seed the development database

```bash
dev make setup # Creates and seeds a development database
```

## Usage

### Web

You can run the Web application with this command:

```bash
web
```

Browse to [http://localhost/][13] to see the web interface.

### Console

You can transfer funds to another member like in the web application with this
command:

```bash
console ewallet:transfer ABC LMN 5 # Transfers $5 MXN from sender with ID ABC to recipient with ID LMN 
```

### Messaging

Both the Web and the console application generate domain events, those events send email notifications. 
Browse to [http://localhost:8080/][15] to see the emails that are sent after transferring funds either from the console or the Web application. 

To start the messaging container run this command:

```bash
messaging # Container with the cron jobs that send emails to MailCatcher based on the messages in RabbitMQ
```

### Tests

Run the tests of all the applications and packages with this command:

```bash
dev make tests
```

Each folder in the `ui` and `ewallet` directories, has its own `README` file. 
Please read them for more details.

[1]: http://escuela.it/cursos/php-web-congress-2015/
[2]: http://bit.ly/php-testing-tools
[4]: http://behat.readthedocs.org/en/latest/
[5]: http://www.phpspec.net/en/latest/
[6]: https://phpunit.de/
[7]: http://docs.mockery.io/en/latest/
[8]: https://github.com/nelmio/alice
[9]: http://codeception.com/
[10]: https://github.com/giorgiosironi/eris
[11]: https://infection.github.io/
[12]: http://alistair.cockburn.us/Hexagonal+architecture
[13]: http://localhost/
[14]: https://github.com/settings/tokens
[15]: http://localhost:8080/
[16]: https://www.docker.com/
[17]: https://docs.docker.com/compose/
[18]: https://getcomposer.org/doc/articles/troubleshooting.md#api-rate-limit-and-oauth-tokens
