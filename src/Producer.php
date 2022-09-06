<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

class Producer
{
    public function sendMessage(string $topic, string $message): void
    {
        $brokers = "127.0.0.1:9093";
        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');

        // producer
        $producer = new \RdKafka\Producer($conf);
        $producer->addBrokers($brokers);
        $topic = $producer->newTopic($topic);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $producer->flush(1000);
    }
}

(new Producer())->sendMessage('test', 'HELLOOOOOOOOOO WORLD');
