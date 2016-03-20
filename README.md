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

You will need to have installed Docker and Ansible to run this example. You'll
also need a [Github token][14] as you will be running `composer install` for
several applications. If you have everything configured, run these commands:

```bash
$ make docker GTOKEN=YOUR_GITHUB_TOKEN_FOR_COMPOSER
$ source .alias
$ setup make install
```

It will set default hosts, users, and passwords for email, MySQL, and RabbitMQ.

## Usage

### Docker

#### Web

You can run the applications (web, console and messaging) with:

```bash
$ make start
```

Browse to [http://localhost/][13] to see the web interface. Browse to
[http://localhost:8080/][15] to see the emails that are sent after
transferring funds either from the console or the web application.

#### Console

I created some aliases to ease the use of the console application. First `source`
the aliases.

```bash
$ source .alias
```

You can transfer funds to another member like in the web application with:

```bash
$ console ewallet:transfer
```

#### Tests

You can run all the tests with this command:

```bash
$ run tests
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
[15]: http://localhost:8080/
