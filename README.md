# WordPress Development Kit

## Getting Started

1.  [Install Docker][Docker].

2.  Duplicate the __.env.sample__ file and name it __.env__.

3.  Edit the __.env__ file and assign your Advanced Custom Fields PRO license
    key to the __ACF_PRO_KEY__ variable. If needed, you can edit the other
    variables in this file as well.

[Docker]: https://store.docker.com/search?type=edition&offering=community


## Development

1.  Run `docker-compose up -d` to bring up the development environment. Be
    patient; this may take a while when first run.

2.  Open [localhost:3000](http://localhost:3000) in your web browser and start
    coding!

4.  When you're finished working, run `docker-compose down` to safely close the
    development environment.
