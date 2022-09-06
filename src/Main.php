<?php

namespace NicolasMugnier\PocPhpKafka;

class Main
{
    public function __construct()
    {
    }

    public function run(): void
    {
        var_dump('Hello from poc');
    }
}

(new Main())->run();
