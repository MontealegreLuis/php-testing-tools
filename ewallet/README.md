# eWallet application

This is a very small application to transfer funds between 2 registered users of this application

You'll need to install SQLite 3.19 or higher and RabbitMQ 3.7 or higher to execute the test suite.

As alternative you can use the Docker containers present in this repository

## Setup

To install this application dependencies execute the following command

```bash
$ make bootstrap
```

In order to run the tests you'll need a `.env` file specific for testing.
To generate this file execute:

```bash
$ make setup
```

Initial configuration assumes RabbitMQ host is `localhost` and username and password are set to `guest/guest`.
If you're using the Docker containers in this repository change host to `queue`

## Tests

This project test suite assumes RabbitMQ service is running

```bash
$ make tests
```
