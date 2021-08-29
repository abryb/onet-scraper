# Simple news scraper from https://www.onet.pl

# Running with docker
```bash
# build container
docker-compose build
# install composer dependencies
docker-compose run --rm php composer install
# run 
docker-compose run --rm php bin/run.php
# run verbose
docker-compose run --rm php bin/run.php -v
```

# Testing
```bash
docker-compose run --rm php vendor/bin/phpunit tests
```
