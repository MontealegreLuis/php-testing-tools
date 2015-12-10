# PHP Testing tools

[![Build Status](https://travis-ci.org/MontealegreLuis/php-testing-tools.svg?branch=master)](https://travis-ci.org/MontealegreLuis/php-testing-tools)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b/mini.png)](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b)

This repository was initially a simple Web application which implemented a
single feature with a single scenario. Its goal was to provide a set of examples
using some of the coolest tools for testing and design in PHP.

* [Behat][4]
* [phpspec][5]
* [PHPUnit][6]
* [Mockery][7]
* [Alice][8]
* [Codeception][9]
* [Eris][10]
* [Humbug][11]

It was used for a testing [class][1], the slides are available [here][2].

I'm also starting to use it to describe how an [hexagonal architecture][12]
might look like in PHP.

## Installation

### Locally

In order to run this demo you will need to install PHP 5.6, MySQL, PhantomJS
and Composer (globally). If you have everything installed just execute the
following command:

```bash
$ cp .env.dist .env
$ source .env
$ make local \
    RUSER="root" \
    RPSWD="root" \
    HOST=$MYSQL_HOST \
    USER=$MYSQL_USER \
    PSWD=$MYSQL_PASSWORD
```

Where the values of `RUSER` and `RPSWD` are the credentials of a user with
permission to create users (`root` and `root` respectively, for this example).
All the other values are taken from `.env` file

The `.env.dist` file contains the environment variables that this application
needs to run. It uses `localhost` for MySQL, and also default values for
RabbitMQ (`guest`, `guest`,  `localhost`).

### Docker

You can also run this demo using Docker containers and Ansible. If you have them
installed, run this command:

```bash
$ make docker
$ source .alias
$ setup make install
```

It will set default hosts, users, and passwords for email, MySQL, and RabbitMQ.

## Usage

### Locally

#### Web

You can run the Web application with:

```bash
$ bin/robo run
```

Then browse to [http://localhost:8000/index_dev.php/][3]

#### Console

You can transfer funds to another member like in the web application with:

```bash
$ bin/robo console ewallet:transfer
```

The application raises a domain event when a transfer is made and can be
published to RabbitMQ with the command:

```bash
$ bin/robo console ewallet:events:spread
```

Members are notified by email through the published message, by running the
following consumer:

```bash
$ bin/robo console ewallet:transfer:email
```

#### Tests

You can run all the tests with this command:

```bash
$ bin/robo test
```

### Docker

#### Web

You can run the Web application with:

```bash
$ make web
```

Then browse to [http://localhost/][3]

#### Console

I created some alias to ease the use of the console application. First `source`
the alias.

```bash
$ source .alias
```

Then you can transfer funds to another member like in the web application with:

```bash
$ console ewallet:transfer
```

To publish domain events to RabbitMQ after a successful transfer is made,
execute:

```bash
$ console ewallet:events:spread
```

To send notification emails through the published messages, run the following
consumer:

```bash
$ console ewallet:transfer:email
```

#### Tests

You can run all the tests with this command:

```bash
$ robo test
```

[1]: http://escuela.it/cursos/php-web-congress-2015/
[2]: http://bit.ly/php-testing-tools
[3]: http://localhost:8000/index_dev.php/
[4]: http://behat.readthedocs.org/en/latest/
[5]: http://www.phpspec.net/en/latest/
[6]: https://phpunit.de/
[7]: http://docs.mockery.io/en/latest/
[8]: https://github.com/nelmio/alice
[9]: http://codeception.com/
[10]: https://github.com/giorgiosironi/eris
[11]: https://github.com/padraic/humbug
[12]: http://alistair.cockburn.us/Hexagonal+architecture
[13]: http://localhost/
