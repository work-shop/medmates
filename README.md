# WordPress Development Kit

## Installation

1.  Edit `docker-compose.yml` and add your Advanced Custom Fields PRO license
    key to the `ACF_PRO_KEY` field.

2.  Install [Docker][].

3.  Run `docker-compose up` to create your development environment. Be patient;
    this may take a little while.

4.  Navigate to [http://localhost:8080][localhost] and install WordPress.

5.  Activate Advanced Custom Fields PRO and Timber plugins.

6.  Run `yarn install` to install project dependencies.

[Docker]: https://store.docker.com/search?type=edition&offering=community
[localhost]: http://localhost:8080


## Development

1.  Run `docker-compose up` to bring up your development environment (if it's
    not up already).

2.  Run `yarn run start` to start the watch and build tasks.

3.  Activate "Custom" WordPress theme.

4.  Get coding!
