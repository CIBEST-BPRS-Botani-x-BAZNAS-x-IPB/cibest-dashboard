<?php

namespace App\Imports;

use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

abstract class BaseImport implements ToCollection, WithStartRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    protected int $rows = 0;

    public function getRowCount(): int
    {
        return $this->rows;
    }

    abstract public function startRow(): int;

    abstract public function mapping(int $index): array|string|null;

    protected function getOptionId(string $model, string|null $value, int $index, bool $allowIsOther = false): int|null
    {
        if (!$value) return null;

        $value = trim($value);

        $record = $model::where('value', $value)->first();

        if (!$record) {
            if (!$allowIsOther)
                throw new Exception("Nilai '{$value}' pada kolom '{$this->mapping($index)}' tidak ditemukan di tabel {$model}.");

            return $model::create([
                'value' => $value,
                'is_other' => true
            ])->id;
        }

        return $record->id;
    }

    protected function getCheckboxId(string $model, string|null $values): array|null
    {
        if (!$values) return null;

        $items = explode(', ', trim($values));
        $ids = [];

        foreach ($items as $item) {
            $value = trim($item);
            if ($value === '') continue;

            $record = $model::where('value', $value)->first();

            if (!$record) {
                $record = $model::create([
                    'value' => $value,
                    'is_other' => true,
                ]);
            }

            $ids[] = $record->id;
        }

        return $ids;
    }

    protected function getLikertId(string $model, string|null $descriptionValue, int $index): int|null
    {
        if (!$descriptionValue) return null;

        $descriptionValue = trim($descriptionValue);

        $record = $model::where('description', $descriptionValue)->first();

        if (!$record) {
            throw new Exception("Nilai '{$descriptionValue}' pada kolom '{$this->mapping($index)}' tidak ditemukan di tabel {$model}.");
        }

        return $record->id;
    }

    protected function detectLembagaZiswaf(string $opzType, string $opzLevel): string
    {
        if ($opzType === 'laz') {
            return 'LAZ';
        }

        if ($opzType === 'baz') {
            return 'BAZNAS Provinsi/Daerah';
        }

        return $opzType . ' ' . $opzLevel;
    }


    public array $data = [];

    abstract public function collection(Collection $rows);

    abstract public function rules(): array;
}
