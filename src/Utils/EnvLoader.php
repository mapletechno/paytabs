<?php

namespace App\Utils;

class EnvLoader
{
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("Environment file not found: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || $line[0] === '#') {
                continue; // Skip empty lines and comments
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));

            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }
        }
    }
}
