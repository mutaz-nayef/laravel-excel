<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentImport extends Model
{
    protected $table = 'student_imports'; // Explicitly set the table name

    protected $fillable = [
        'filename',
        'imported_count',
        'skipped_count',
        'error_rows',
    ];

    protected $casts = [
        'error_rows' => 'array', // automatically cast JSON to array
    ];
}
