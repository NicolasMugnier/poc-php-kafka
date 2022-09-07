<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

require(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Uid\Uuid;

class BatchMessageProducer
{
    public function sendMessage(string $topic, string $message): void
    {
        $conf = Configuration::getConf();
        // producer
        $producer = new \RdKafka\Producer($conf);
        // $producer->addBrokers(Configuration::BROKERS);
        // if the topic doesn't exists it is created on the fly, awesome
        $topic = $producer->newTopic($topic);
        $headers = [
            'X-Correlation-ID' => Uuid::v4()->toRfc4122(),
            'X-Origin' => 'somewhere'
        ];
        $topic->producev(RD_KAFKA_PARTITION_UA, 0, $message, null, $headers);

        /**
         * retries in case of failure, for example when topic must be created the first time
         * can not be replaced by $conf->set('message.send.max.retries', '10');
         */
        $i = 0;
        do {
            $result = $producer->flush(1000);
        } while (RD_KAFKA_RESP_ERR_NO_ERROR !== $result || $i++ <= 10);

        // all retries failed
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            // TODO : log message
            throw new \RuntimeException('Was unable to flush, messages might be lost!');
        }
    }
}

$message = [];
for ($i = 0; $i <= 100; $i++) {
    $item = new \StdClass();
    $item->uuid = Uuid::v4()->toRfc4122();
    $message[] = $item;
}
(new BatchMessageProducer())->sendMessage(Configuration::BATCH_MESSAGE_TOPIC_NAME, json_encode($message));
