# POC php Kafka

## Installation

### Main library

[librdkafka](https://github.com/edenhill/librdkafka)

```bash
apt install librdkafka-dev
```

### php extension

[arnaud-lb/php-rdkafka](https://github.com/arnaud-lb/php-rdkafka)

```bash
sudo pecl install rdkafka
```

## Run

```bash
docker-compose up -d
php ./src/Main.php
```
