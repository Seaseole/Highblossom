<?php

declare(strict_types=1);

namespace App\Services;

final class EnvEditor
{
    private string $path;

    public function __construct()
    {
        $this->path = base_path('.env');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (! file_exists($this->path)) {
            return $default;
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$envKey, $envValue] = $this->parseLine($line);

            if ($envKey === $key) {
                return $this->unquote($envValue);
            }
        }

        return $default;
    }

    public function set(string $key, mixed $value): void
    {
        if (! file_exists($this->path)) {
            return;
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES);
        $replaced = false;
        $newValue = $this->quote((string) $value);

        foreach ($lines as $index => $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$envKey] = $this->parseLine($line);

            if ($envKey === $key) {
                $lines[$index] = "{$key}={$newValue}";
                $replaced = true;
                break;
            }
        }

        if (! $replaced) {
            $lines[] = "{$key}={$newValue}";
        }

        file_put_contents($this->path, implode(PHP_EOL, $lines) . PHP_EOL);
    }

    public function all(): array
    {
        if (! file_exists($this->path)) {
            return [];
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $result = [];

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = $this->parseLine($line);

            if ($key !== '') {
                $result[$key] = $this->unquote($value);
            }
        }

        return $result;
    }

    private function parseLine(string $line): array
    {
        if (! str_contains($line, '=')) {
            return ['', ''];
        }

        $parts = explode('=', $line, 2);

        return [trim($parts[0]), trim($parts[1])];
    }

    private function quote(string $value): string
    {
        if (str_contains($value, ' ') || str_contains($value, '#')) {
            return '"' . $value . '"';
        }

        return $value;
    }

    private function unquote(string $value): string
    {
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
