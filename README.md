# PHP Testing tools

[![Build Status](https://travis-ci.org/MontealegreLuis/php-testing-tools.svg?branch=master)](https://travis-ci.org/MontealegreLuis/php-testing-tools)
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

### Tests

You can run all the tests with this command:

```bash
$ bin/robo test
```

### Web

You can run the Web application with:

```bash
$ bin/robo run
```

You can now browse to [http://localhost:8000/index_dev.php/][3]

### Console

You can run the console application with:

```bash
$ bin/robo console <command> <arg1> ... <argN>
```

It implements the Transfer Funds feature  in the `ewallet:transfer` command. The
following command would transfer $5.00 MXN from the member with ID 'ABC' to the
member with ID 'LMN'.

```bash
$ bin/robo console ewallet:transfer ABC LMN 5
```

### Environments

The application can be run in two environments `dev` and `prod`. Currently it
defaults to the `dev` environment.

You don't need to change anything if you're not curious on how to run it in the
"production environment". However, if you are, you will need to set some
environment variables.

```
DOCTRINE_DEV_MODE=0
TWIG_DEBUG=0
SMTP_HOST=127.0.0.1 # It uses mailcatcher for emails by defaultq
SMTP_PORT=1025      # You'll need to change these to try it in a real SMTP server
```

These values need to be in the `.env` file for the console application.

If you're running an Apache virtual host, you can add those values in its
configuration file.

```apache
<VirtualHost *:8080>
  ServerName ewallet.dev
  DocumentRoot "/path/to/src/EwalletApplication/Bridges/Slim/Resources/web"
  SetEnv DOCTRINE_DEV_MODE 0
  SetEnv TWIG_DEBUG 0
  SetEnv SMTP_HOST 127.0.0.1
  SetEnv SMTP_PORT 1025
  <Directory "/path/to/src/EwalletApplication/Bridges/Slim/Resources/web">
   Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    Allow from all
  </Directory>
</VirtualHost>
```

[1]: http://escuela.it/cursos/php-web-congress-2015/
[2]: http://bit.ly/php-testing-tools
[3]: http://localhost:8000/index_dev.php/
