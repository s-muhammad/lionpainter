<?php

namespace App\Services\Seo;

class CsvImportResult
{
    public int $created = 0;

    public int $updated = 0;

    public int $skipped = 0;

    /**
     * @var array<int, array{row: int, reason: string}>
     */
    public array $skippedRows = [];

    /**
     * @var array<int, array{row: int, reason: string}>
     */
    public array $warnings = [];

    public function recordCreated(): void
    {
        $this->created++;
    }

    public function recordUpdated(): void
    {
        $this->updated++;
    }

    public function recordSkipped(int $row, string $reason): void
    {
        $this->skipped++;
        $this->skippedRows[] = ['row' => $row, 'reason' => $reason];
    }

    public function recordWarning(int $row, string $reason): void
    {
        $this->warnings[] = ['row' => $row, 'reason' => $reason];
    }

    public function processed(): int
    {
        return $this->created + $this->updated + $this->skipped;
    }

    public function toArray(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'skipped' => $this->skipped,
            'processed' => $this->processed(),
            'skipped_rows' => $this->skippedRows,
            'warnings' => $this->warnings,
        ];
    }
}
