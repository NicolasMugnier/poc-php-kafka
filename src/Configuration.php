<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

class Configuration
{
    public const BATCH_MESSAGE_TOPIC_NAME = 'batch-message';

    public const SINGLE_MESSAGE_TOPIC_NAME = 'single-message';

    public const BROKERS = '127.0.0.1:9093';

    public static function getConf(): \RdKafka\Conf
    {
        $conf = new \RdKafka\Conf();
        //$conf->set('log_level', (string) LOG_DEBUG);
        //$conf->set('debug', 'all');
        // Main errors, like Kafka is not running
        $conf->setErrorCb(function ($kafka, $err, $reason) {
            printf("Kafka error: %s (reason: %s)\n", \rd_kafka_err2str($err), $reason);
        });
        // Delivery message status
        $conf->setDrMsgCb(function ($kafka, $message) {
            if ($message->err) {
                // the message was not delivered, so in this case we need to raise an error and log it
                printf("Message permanently failed to be delivered\n");
            } else {
                printf("Message successfully delivered\n");
            }
        });
        // Global logs
        $conf->setLogCb(function ($kafka, $level, $facility, $message) {
            printf("Kafka %s: %s (level: %d)\n", $facility, $message, $level);
        });

        return $conf;
    }
}
