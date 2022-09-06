<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

require(__DIR__ . '/../vendor/autoload.php');

class SingleMessageConsumer
{
    public function consume(string $topicName, int $brokerId = 0): void
    {
        // consumer
        $consumer = new \RdKafka\Consumer(Configuration::getConf());
        $consumer->addBrokers(Configuration::BROKERS);
        $topic = $consumer->newTopic($topicName);
        $topic->consumeStart($brokerId, RD_KAFKA_OFFSET_BEGINNING);

        while (true) {
            // The first argument is the partition (again).
            // The second argument is the timeout.
            $msg = $topic->consume($brokerId, 1000);
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                // Constant check required by librdkafka 0.11.6. Newer librdkafka versions will return NULL instead.
                continue;
            } elseif ($msg->err) {
                echo $msg->errstr(), "\n";
                break;
            } else {
                echo $msg->payload, "\n";
            }
        }
    }
}

(new SingleMessageConsumer())->consume(Configuration::SINGLE_MESSAGE_TOPIC_NAME);
