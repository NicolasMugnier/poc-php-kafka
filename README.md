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

## Run

```bash
docker-compose up -d
php ./src/BatchMessageProducer.php
php ./src/BatchMessageConsumer.php
php ./src/SingleMessageConsumer.php
```
