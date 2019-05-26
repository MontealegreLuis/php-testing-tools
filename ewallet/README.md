# eWallet application

This application transfers funds between 2 registered users.

You'll need to install SQLite 3.19 or higher, and RabbitMQ 3.7 or higher to execute the test suite.

As an alternative you can use the Docker containers available in this repository.

## Setup

To install all Composer dependencies execute the following command

```bash
$ make bootstrap
```

## Configuration

**Skip this step if you're using the `dev` Docker containers**

In order to run the tests you'll need a `.env` file specific for testing.
To generate this file execute:

```bash
$ make setup
```

This initial configuration assumes RabbitMQ host is `localhost` and username and password are set to `guest/guest`.

## Tests

This project test suite assumes the RabbitMQ service is running.
If you're not using the `dev` Docker container please start the service.

```bash
$ make tests
```
