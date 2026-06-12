<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class InstantEstateLogFormatter
{
    public function __invoke($logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                "Y-m-d H:i:s",
                true,
                true
            ));
        }
    }
}