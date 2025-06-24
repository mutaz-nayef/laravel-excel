<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use App\Exports\FailedRowsExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function (Request $request) {

    $view = view('dashboard');

    if ($request->ajax()) {
        // renderSections() returns an array keyed by section name
        $sections = $view->renderSections();
        // return just the “content” section HTML
        return $sections['content'];
    }
    return $view;
})->name('dashboard');




Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentsController::class, 'create'])->name('students.create');
Route::post('/students/import', [StudentsController::class, 'import'])->name('students.import');
Route::get('/students/imported', [StudentsController::class, 'importedStudents'])->name('students.imported');

Route::get('export', [StudentsController::class, 'export'])->name('students.export');



Route::get('/students/failed-download', [StudentsController::class, 'downloadFailedRows'])->name('students.failed-download');
