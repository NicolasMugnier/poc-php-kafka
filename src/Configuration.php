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
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');

        return $conf;
    }
}
