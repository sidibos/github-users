# GitHub User Favourite Programming Language
This application will guess GitHub user favourite programming language

# Requirements
You will need [Docker](https://www.docker.com/products/docker-desktop) installed

# Setup
Please follow the instructions below to setup the application

## Clone the application from GitHub
```console
$ git clone https://github.com/sidibos/github-users.git
```

## Go into the application folder
```console
$ cd github-users

```

## Get a GitHub Token
Go to GitHub [token page](https://github.com/settings/developers), grab an access token.

Populate the environment variable `GITHUB_API_TOKEN` with this value

## Run Docker command to build the environment

```console
$ docker-compose up -d

```

## Install the dependencies
```
$ docker-compose exec php composer install
```

## Navigate the application
Browse [https://localhost:8000](https://localhost:8000) and accept the certificate

You should see the page where you can enter your GitHub username and get your favourite programming language when you hit the `Submit` button.

# Running the tests
To run the tests, do the following command

```console
$ docker-compose exec php bin/phpunit tests
```