# Noel De Martin's Vocabulary ![CI](https://github.com/noeldemartin/vocab/actions/workflows/ci.yml/badge.svg)

This repository contains the source for custom RDF ontologies I've created for my apps. I try to use existing vocabs when possible, but sometimes I've had to create my own.

You can find the vocabularies themselves under [resources/ontologies/](resources/ontologies/). If there is something that is not implemented properly for RDF consumption, please let me know!

If you want to create your own vocabs, you're also welcome to clone this repository. It's not coupled to my vocabs, so you should be able to use it by updating some files. The code is written with PHP using [Laravel](https://laravel.com).

## Development

For development, you can clone the repository and serve it using [Laravel Sail](https://laravel.com/docs/sail). Make sure to also compile assets with `npm` and add the domain to `/etc/hosts`.

```sh
git clone git@github.com:NoelDeMartin/vocab.git vocab
cd vocab
cp .env.example .env
docker run --rm -u '$(id -u):$(id -g)' -v '$(pwd):/var/www/html' -w /var/www/html laravelsail/php82-composer:latest composer install --ignore-platform-reqs
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
sudo -- sh -c -e "echo '127.0.0.1 vocab.test' >> /etc/hosts"
npm install
npm run build
```

After running these commands, you should be able to use the app on [http://vocab.test](http://vocab.test).

## Production

For production, the repository is prepared to work in "headless mode". This means that the application code is published in [Docker Hub](https://hub.docker.com/r/noeldemartin/vocab), and you don't need to clone all the source to your server in order to run it. Instead, you only need the configuration files and the folders necessary to run the app using [Docker Compose](https://docs.docker.com/compose/). It is also prepared to work with [nginx-agora](https://github.com/noeldemartin/nginx-agora).

You can configure your server with the following commands:

```sh
sudo git clone --branch headless --single-branch https://github.com/NoelDeMartin/vocab.git vocab
sudo chown $USER:$USER vocab -R
cd vocab
find storage -type d -exec chmod 777 {} +
find storage -type f -exec chmod 666 {} +
./vocab install
./vocab start
```

Run `./vocab` to see more commands.
