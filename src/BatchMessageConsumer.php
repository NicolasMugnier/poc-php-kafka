<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

require(__DIR__ . '/../vendor/autoload.php');

// @todo add consumer group
class BatchMessageConsumer
{
    public function consume(string $topicName, int $brokerId = 0): void
    {
        // consumer
        $consumer = new \RdKafka\Consumer(Configuration::getConf());
        $consumer->addBrokers(Configuration::BROKERS);
        $consumerTopic = $consumer->newTopic($topicName);
        $consumerTopic->consumeStart($brokerId, RD_KAFKA_OFFSET_BEGINNING);

        while (true) {
            // The first argument is the partition (again).
            // The second argument is the timeout.
            $msg = $consumerTopic->consume($brokerId, 1000);
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
                $producer->addBrokers(Configuration::BROKERS);
                $producerTopic = $producer->newTopic(Configuration::SINGLE_MESSAGE_TOPIC_NAME);

                foreach ($rows as $row) {
                    $producerTopic->producev(124, 0, json_encode($row), null, $headers);
                }
                $producer->flush(1000);
            }
        }
    }
}

(new BatchMessageConsumer())->consume(Configuration::BATCH_MESSAGE_TOPIC_NAME);
