<?php

declare(strict_types=1);

namespace NicolasMugnier\PocPhpKafka;

class Configuration
{
    public static function getConf(): \RdKafka\Conf
    {
        $conf = new \RdKafka\Conf();
        $conf->set('log_level', (string) LOG_DEBUG);
        $conf->set('debug', 'all');

        return $conf;
    }
}
