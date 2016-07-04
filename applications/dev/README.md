# Ewallet Dev Application

The following commands are meant to be run from the `dev` container in this
folder.

```bash
$ cd applications/dev
```

## Setup

```bash
$ composer install
```

## Commands

The goal of this application is to refresh the database when needed. It has the
following commands:

```
ewallet:db:create       Create database
ewallet:db:drop         Drops the database
ewallet:db:refresh      Recreates and optionally seeds the database
ewallet:db:seed         Seed the database with some initial information
orm:schema-tool:update  Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata.
```

To recreate the database and its data, run the following command:

```bash
$ bin/console ewallet:db:refresh -s
```
