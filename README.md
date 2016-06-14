# PHP Testing tools

[![Build Status](https://travis-ci.org/MontealegreLuis/php-testing-tools.svg?branch=master)](https://travis-ci.org/MontealegreLuis/php-testing-tools)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b/mini.png)](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)]()

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

It was used for a testing [class][1]. The slides are available [here][2].

I'm also starting to use it to describe how an [hexagonal architecture][12]
might look like in PHP.

## Installation

You will need [Docker][16] and [Docker Compose][17] to run this example. This is
now, a monolithic repository using [Composer's path feature][18].

You can customize most of the settings for the containers using the file
[.env.sh.template](containers/templates/.env.sh.template) as a guide.

Run the following command to use the default settings (recommended):

```bash
$ make env
```

The only key that you need to modify in the new file `containers/.env.sh` is
`GITHUB_TOKEN`. You'll need a [Github token][14] as you will be running
`composer install` for several applications.

Once you have everything configured, run this command:

```bash
$ make compose
```

## Usage

I created some aliases to ease the use of the containers.

```bash
$ source .alias
```

### Web

You can run the Web application with this command:

```bash
$ web
```

Browse to [http://localhost/][13] to see the web interface.

### Console

You can transfer funds to another member like in the web application with this
command:

```bash
$ console ewallet:transfer
```

### Messaging

Both the Web and the console application generate domain events, those events
trigger email notifications. Browse to [http://localhost:8080/][15] to see the
emails that are sent after transferring funds either from the console or the web
application. To start the messaging container run this command:

```bash
$ messaging
```

### Tests

You can start a `bash` session and run the tests of all the applications and
packages with this command:

```bash
$ dev
```

Each folder in the `applications` and `packages` directories, has its own
`README` file. See them for more details.

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
[16]: https://www.docker.com/
[17]: https://docs.docker.com/compose/
[18]: https://getcomposer.org/doc/05-repositories.md#path
