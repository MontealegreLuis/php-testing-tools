# eWallet Messaging Application

If you're using the Docker setup, the following commands are meant to be run from the `dev` container in this directory.

```bash
cd ui/messaging
```

## Setup

```bash
make bootstrap
```

## Configuration

If you're using the Docker setup you may skip this step.
Otherwise you'll need a `.env` with the settings to connect to RabbitMQ.
We're using Mailcatcher to see the sent emails, please install and configure locally.

```bash
make setup
```

## Tests

Run the tests

```bash
make tests
```
