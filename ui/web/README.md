# Ewallet Web Application

The following commands are meant to be run from the `dev` container in this folder.

```bash
$ cd ui/web
```

## Setup

```bash
make bootstrap
```

## Configuration

If you're using the Docker containers, skip this step

```bash
make setup
```

## Run application

If you're using the Docker containers, skip this step

```bash
make server
```

## Tests

End to end tests use PhantomJS, please install it globally so you can run the test suite.
If you're using the Docker containers no need to worry about it.

```bash
make tests
```
