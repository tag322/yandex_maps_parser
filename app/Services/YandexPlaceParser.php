<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class YandexPlaceParser
{
    public function parse(string $url): array
    {
        $scriptPath = base_path('scripts/yandex-parser.js');

        $process = new Process([
            'node',
            $scriptPath,
            $url
        ]);

        $process->setEnv([
            'SYSTEMROOT' => getenv('SYSTEMROOT'),
            'PATH' => getenv('PATH'),
        ]);

        $process->setTimeout(120);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output = $process->getOutput();

        return json_decode($output, true) ?? [];
    }
}