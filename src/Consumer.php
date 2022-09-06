<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

class Consumer
{
    public function consume(): void
    {
        $brokers = "127.0.0.1:9093";
        $topic = "test";
        $brokerId = 0;

        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');

        // consumer
        $consumer = new \RdKafka\Consumer($conf);
        $consumer->addBrokers($brokers);
        $topic = $consumer->newTopic($topic);
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

(new Consumer())->consume();
