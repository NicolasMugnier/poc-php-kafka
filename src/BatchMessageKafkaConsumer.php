<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

require(__DIR__ . '/../vendor/autoload.php');

/**
 * high-level consumer
 * 
 * @see https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/class.rdkafka-kafkaconsumer.html
 * @see https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/rdkafka.examples-high-level-consumer.html
 */
class BatchMessageKafkaConsumer
{
    public function consume(string $topicName, int $brokerId = 0): void
    {
        // consumer
        $conf = Configuration::getConf();
        $conf->set('group.id', 'batch_message_php_consumer');
        // Set where to start consuming messages when there is no initial offset in
        // offset store or the desired offset is out of range.
        // 'earliest': start from the beginning
        $conf->set('auto.offset.reset', 'earliest');

        // Emit EOF event when reaching the end of a partition
        $conf->set('enable.partition.eof', 'true');

        $consumer = new \RdKafka\KafkaConsumer($conf);
        $consumer->subscribe([$topicName]);

        while (true) {
            $msg = $consumer->consume(1000);
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                // Constant check required by librdkafka 0.11.6. Newer librdkafka versions will return NULL instead.
                continue;
            } elseif ($msg->err) {
                echo $msg->errstr(), "\n";
                break;
            } else {
                $rows = json_decode($msg->payload, true);
                $headers = $msg->headers;
                $producer = new \RdKafka\Producer(Configuration::getConf());
                // $producer->addBrokers(Configuration::BROKERS);
                $producerTopic = $producer->newTopic(Configuration::SINGLE_MESSAGE_TOPIC_NAME);

                foreach ($rows as $row) {
                    $producerTopic->producev(RD_KAFKA_PARTITION_UA, 0, json_encode($row), null, $headers);
                }
                for ($i = 0; $i <= 10; $i++) {
                    $result = $producer->flush(1000);
                    if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                        break;
                    }
                }
                // all retries failed
                if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
                    throw new \RuntimeException('Was unable to flush, messages might be lost!');
                }
            }
        }
    }
}

(new BatchMessageConsumer())->consume(Configuration::BATCH_MESSAGE_TOPIC_NAME);
