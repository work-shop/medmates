# WordPress Development Kit

## Getting Started

1.  [Install Docker][Docker].

2.  Install project dependencies with `yarn install` (or `npm install`).

3.  Duplicate the __.env.sample__ file and name it __.env__.

4.  Edit the __.env__ file and assign your Advanced Custom Fields PRO license
    key to the __ACF_PRO_KEY__ variable. If needed, you can edit the other
    variables in this file as well.

[Docker]: https://store.docker.com/search?type=edition&offering=community


## Development

1.  Run `docker-compose up -d` to bring up the development environment. Be
    patient; this may take a while when first run.

2.  Run `yarn start` (or `npm start`). A browser window should soon appear
    showing your WordPress website. If not, check the console log for more
    information.

3.  If you're using a remote database, it may be necessary to add the following
    snippet to __dist/wp-config.php__, right before the line containing
    `require_once(ABSPATH . 'wp-settings.php');`, so that WordPress permalinks
    work properly.

    ```php
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
      define('WP_SITEURL', '//' . $_SERVER['HTTP_HOST']);
      define('WP_HOME', '//' . $_SERVER['HTTP_HOST']);
    }
    ```

4.  When you're finished working, run `docker-compose down` to safely stop the
    development environment.
