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

### Docker

You run this demo using Docker containers and Ansible. You will need
a [Github token][14] as you will be running `composer install` for several
applications. If you have everything configured, run this command:

```bash
$ make docker GTOKEN=YOUR_GITHUB_TOKEN_FOR_COMPOSER
$ source .alias
$ setup make install
```

It will set default hosts, users, and passwords for email, MySQL, and RabbitMQ.

## Usage

### Docker

#### Web

You can run the Web application with:

```bash
$ make web
```

Then browse to [http://localhost/][13]

#### Console

I created some aliases to ease the use of the console application. First `source`
the aliases.

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
[14]: https://github.com/settings/tokens
