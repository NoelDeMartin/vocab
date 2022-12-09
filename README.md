# Noel De Martin's Vocabulary ![CI](https://github.com/noeldemartin/vocab/actions/workflows/ci.yml/badge.svg)

WIP

## Development

For development, you can clone the repository and serve it using [Laravel Sail](https://laravel.com/docs/sail). Make sure to also compile assets with `npm` and add the domain to `/etc/hosts`.

```sh
git clone git@github.com:NoelDeMartin/vocab.git vocab
cd vocab
cp .env.example .env
docker run --rm -v "$(pwd):/app" -w /app laravelsail/php81-composer:latest composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
sudo -- sh -c -e "echo '127.0.0.1 vocab.test' >> /etc/hosts"
npm install
npm run dev
```

After running these commands, you should be able to use the app on [http://vocab.test](http://vocab.test).

## Production

For production, you can use some the Docker Compose configuration that is included in the repository. It is, however, not straight-forward to use because it has some configuration parameters such as the domain where it will be served from. It is also integrated with [nginx-agora](https://github.com/noeldemartin/nginx-agora), which I use in my server.

In order to simplify this setup, you can use the `./vocab` binary:

```sh
git clone git@github.com:NoelDeMartin/vocab.git vocab
cd vocab
./vocab install
./vocab start
```

Run `./vocab` to see more commands.

## Production (headless)

The code is published in [Docker Hub](https://hub.docker.com/r/noeldemartin/vocab), so if you just want to run the application in the server without modifying any files you can do it in "headless mode". This only means that you won't have the source code checked out in the server, instead you'll only keep the configuration files and folders necessary to run the app using Docker Compose.

This can also be configured using the `./vocab` binary:

```sh
git clone --branch headless --single-branch git@github.com:NoelDeMartin/vocab.git vocab
cd vocab
./vocab install
./vocab start
```

Run `./vocab` to see more commands.
