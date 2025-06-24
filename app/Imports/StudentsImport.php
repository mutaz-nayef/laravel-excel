<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentsImport implements
    ToCollection,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading
{
    public array $failedRows = [];
    public int $importedCount = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $data = $row->toArray();
            try {
                $validated = validator($data, $this->rules())->validate();

                $this->storeRow($validated);
            } catch (\Illuminate\Validation\ValidationException $e) {

                $this->failedRows[] = [
                    'row' => $rowNumber,
                    'errors' => $e->errors(), // array of field => array of messages
                    'values' => $data, // the input data from the row
                ];
                continue;
            }
        }
    }

    protected function storeRow(array $row): void
    {
        try {
            Student::create([
                'name'          => $row['name'] ?? null,
                'gender'        => $row['gender'] ?? null,
                'date_of_birth' => $this->transformDate($row['date_of_birth']) ?? null,
                'email'         => $row['email'] ?? null,
                'phone'         => $row['phone'] ?? null,
                'city'          => $row['city'] ?? null,
                'address'       => $row['address'] ?? null,
                'gpa'           => $row['gpa'] ?? null,
                'major'         => $row['major'] ?? null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $this->importedCount++;
        } catch (\Throwable $e) {
            // Log, but don't push to failedRows — validation already caught invalid data
            logger()->error("Failed to insert student: {$e->getMessage()}");
        }
    }

    protected function transformDate($value): ?string
    {
        if (!$value) return null;

        try {
            return is_numeric($value)
                ? Date::excelToDateTimeObject($value)->format('Y-m-d')
                : Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'gender'        => 'required|in:Male,Female,male,female,ذكر,أنثى',
            'date_of_birth' => 'required|date',
            'email'         => 'required|email|unique:students,email',
            'phone'         => 'nullable|max:20',
            'city'          => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:255',
            'gpa'           => 'nullable|numeric|between:0,100',
            'major'         => 'nullable|string|max:100',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
