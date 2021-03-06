# WordPress Development Kit

## Getting Started

1.  [Install Docker][Docker].

2.  Install project dependencies with `npm install`.

3.  Duplicate the __.env.sample__ file and name it __.env__.

4.  Edit the __.env__ file and assign your Advanced Custom Fields PRO license
    key to the __ACF_PRO_KEY__ variable. If needed, you can edit the other
    variables in this file as well.

[Docker]: https://store.docker.com/search?type=edition&offering=community


## Development

1.  Bring up the development environment with `docker-compose up -d`. Be
    patient; this may take a while when first run.

2.  Enter `npm run watch` and start coding!

4.  When you're finished working, run `docker-compose down` to safely close the
    development environment.
