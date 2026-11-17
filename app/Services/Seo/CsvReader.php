<?php

namespace App\Services\Seo;

use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class CsvReader
{
    /**
     * @return array{
     *     headers: array<int, string>,
     *     rows: array<int, array{line: int, data: array<string, string>}>
     * }
     */
    public function read(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $handle = $path !== false ? fopen($path, 'rb') : false;

        if ($handle === false) {
            throw new InvalidArgumentException('فایل CSV قابل خواندن نیست.');
        }

        $headers = null;
        $rows = [];
        $line = 0;

        try {
            while (($cells = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $line++;

                if ($this->isEmptyRow($cells)) {
                    continue;
                }

                if ($headers === null) {
                    $headers = $this->normalizeHeaders($cells);

                    continue;
                }

                $data = [];

                foreach ($headers as $index => $header) {
                    if ($header === '') {
                        continue;
                    }

                    $data[$header] = trim((string) ($cells[$index] ?? ''));
                }

                $rows[] = ['line' => $line, 'data' => $data];
            }
        } finally {
            fclose($handle);
        }

        if ($headers === null) {
            throw new InvalidArgumentException('فایل CSV خالی است.');
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, string>  $required
     */
    public function requireHeaders(array $headers, array $required): void
    {
        $missing = array_values(array_diff($required, $headers));

        if ($missing !== []) {
            throw new InvalidArgumentException(
                'ستون‌های ضروری در فایل وجود ندارند: '.implode(', ', $missing)
            );
        }
    }

    /**
     * @param  array<int, string|null>  $cells
     * @return array<int, string>
     */
    private function normalizeHeaders(array $cells): array
    {
        $headers = [];

        foreach ($cells as $index => $cell) {
            $header = trim((string) $cell);

            if ($index === 0) {
                $header = preg_replace('/^\xEF\xBB\xBF/', '', $header) ?? $header;
            }

            $headers[] = strtolower(str_replace([' ', '-'], '_', $header));
        }

        return $headers;
    }

    /**
     * @param  array<int, string|null>  $cells
     */
    private function isEmptyRow(array $cells): bool
    {
        foreach ($cells as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }
}
