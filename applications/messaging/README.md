# Ewallet Messaging Application

The following commands are meant to be run from the `dev` container in this
folder.

```bash
$ cd applications/messaging
```

## Setup

```bash
$ composer install
```

## Tests

Setup the testing database

```bash
$ bin/doctrine orm:schema-tool:update --force
```

Run the tests

```bash
$ bin/phpunit --testdox
```
