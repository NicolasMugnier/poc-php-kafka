<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

require(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Uid\Uuid;

class BatchMessageProducer
{
    public function sendMessage(string $topic, string $message): void
    {
        // producer
        $producer = new \RdKafka\Producer(Configuration::getConf());
        $producer->addBrokers(Configuration::BROKERS);
        $topic = $producer->newTopic($topic);
        // $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $headers = [
            'X-Correlation-ID' => Uuid::v4()->toRfc4122(),
            'X-Origin' => 'somewhere'
        ];
        $topic->producev(RD_KAFKA_PARTITION_UA, 0, $message, null, $headers);
        $producer->flush(1000);
    }
}

$message = [];
for ($i = 0; $i < 100; $i++) {
    $item = new \StdClass();
    $item->uuid = Uuid::v4()->toRfc4122();
    $message[] = $item;
}
(new BatchMessageProducer())->sendMessage(Configuration::BATCH_MESSAGE_TOPIC_NAME, json_encode($message));
