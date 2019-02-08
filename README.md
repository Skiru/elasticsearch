Symfony demo combined with Elasticsearch
========================

The major goal of the application is to combine Sf demo application with Elasticsearch.


Requirements
------------

  * PHP 7.1.3 or higher;
  * PDO-SQLite PHP extension enabled;

Installation
------------

Cd into root of the project, and run:

```bash
$ docker-compose build
```

And after the build finish:

```bash
$ docker-compose up -d
```

Next step is to step into the php container:

```bash
$ docker exec -it php bash
```

Now you can run composer to install all required deoendencies

```bash
$ composer install
```

Usage
-----

There's already ruflin/elastica installed. To instantiate Elastic client you can use the following config:
```
host: elasticsearch 
port: 9200
```