# POC php Kafka

## Installation

### Main library

[librdkafka](https://github.com/edenhill/librdkafka)

```bash
sudo apt install librdkafka-dev
```

### php extension

[arnaud-lb/php-rdkafka](https://github.com/arnaud-lb/php-rdkafka)

```bash
sudo pecl install rdkafka
```

Enable the extension by adding `extension=rdkafka.so` in `php.ini` file.

### Dependencies

```bash
composer install
```

## Run

```bash
docker-compose up -d
```

Run batch message producer, it will generate an array of 100 items and push it into `batch-message` topic. The topic will be created if it does not exists.

```bash
php ./src/BatchMessageProducer.php
```

Run batch message consumer, it will split the batch message and push singles messages on `single-message` topic. The topic will be created if it does not exists.

```bash
php ./src/BatchMessageConsumer.php
```

Run single message consumer, it will just display message content in the console.

```bash
php ./src/SingleMessageConsumer.php
```

## Docs

- [php-rdkafka](https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/book.rdkafka.html)
- [Kafka Configuration](https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md)
