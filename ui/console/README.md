# eWallet Console Application

## Setup

Execute the following command to install Composer dependencies.

```bash
make bootstrap
```

## Configuration

If you're not using the Docker containers to run this application, you'll need a `.env` file.
Run the following command

```bash
make setup
```

It will create a `.env` that you can adjust to match your local setup configuration.

## Usage

This console application has one command that allows one member transfer funds to another.

```bash
bin/console ewallet:transfer ABC LMN 10
```

## Tests

If you're using the Docker containers, tests are meant to be run from the `dev` container in this directory.

```bash
source .alias         # Only if you're using the Docker setup
dev                   # Only if you're using the Docker setup
cd ui/console         # This application directory
make tests
```
