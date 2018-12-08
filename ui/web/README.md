# Ewallet Web Application

The following commands are meant to be run from the `dev` container in this
folder.

```bash
$ cd applications/web
```

## Setup

```bash
$ composer install
```

## Run application

```bash
$
$ php -S localhost:8000 -t public
```

## Tests

```bash
$ bin/phpunit --testdox
$ bin/robo acceptance
```
## Static analysis

```bash
$ phan -p -o phan.txt
```
