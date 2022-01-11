# LOGOS API

### Local installation

```bash
1) cp .env.example .env
2) cp docker-compose.example.yml docker-compose.yml
3) docker-compose build
4) docker-compose exec api composer i
5) docker-compose exec api php artisan migrate
6) docker-compose exec api php artisan db:seed
7) chmod -R 777 storage
8) docker-compose exec api php artisan passport:install // optional, step №-6 already covered passport tokens
```

### Post Installation Process

After your installation complete, you can access the website by following urls:
- http://localhost
- http://localhost/admin

#### Generate admin user
Now we need to create admin user
```bash
docker-compose exec api php artisan db:seed --class=SuperAdminUserSeed
```
Now you are able to login to Admin panel with following credentials:

USER: logos
PASSWORD: password


## CI/CD
We are using the custom docker container to build all our pipelines, so if any changes needed in PHP container we need
to upload fresh image to gitlab registry. To do this you need to do following actions:

First you need to login to registry
```
docker login registry.gitlab.com
```

Build and image
```
docker build -t registry.gitlab.com/mosakovskijj/logos-api docker/php/
```

Upload an image to GitLab
```
docker push registry.gitlab.com/mosakovskijj/logos-api
```
