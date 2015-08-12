# PHP Testing tools

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b/mini.png)](https://insight.sensiolabs.com/projects/b1fa13fc-3d1b-4b48-8bb1-4f0bb64d8a5b)

This is a set of examples using some of the coolest tools for testing and
design in PHP.

* Behat
* phpspec
* PHPUnit
* Mockery
* Alice
* Codeception

It was used for a testing class [here][1]. You can follow the progress of the
examples with each commit. You can also check the slides [here][2].

## Installation

In order to work with this demo you will need to install PHP 5.6, SQLite,
npm and Composer. If you have everything installed just execute the following
command:

```bash
$ make install
```

## Usage

You can run all the tests with this command:

```bash
$ bin/robo test
```

You can run the application with:

```bash
$ bin/robo run
```

You can now browse to [http://localhost:8000][3]

[1]: http://escuela.it/cursos/php-web-congress-2015/
[2]: http://bit.ly/php-testing-tools
[3]: http://localhost:8000
