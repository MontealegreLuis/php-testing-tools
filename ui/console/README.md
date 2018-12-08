# Ewallet Console Application

## Setup

```bash
$ composer install
```

## Usage

This console application has one command that allows one member transfer funds
to another.

```bash
$ bin/console ewallet:transfer ABC LMV 10
```

## Tests

Tests are meant to be run from the `dev` container in this folder.

```bash
$ cd applications/console
$ bin/phpunit --testdox
```
