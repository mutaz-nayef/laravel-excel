<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FailedRowsExport;
use App\Exports\StudentsExport;
use App\Models\StudentImport;



class StudentsController extends Controller
{

    public function index(Request $request)
    {

        $students = Student::paginate(10);

        $view = view('students.index', compact('students'));

        if ($request->ajax()) {
            // renderSections() returns an array keyed by section name
            $sections = $view->renderSections();
            // return just the “content” section HTML
            return $sections['content'];
        }

        return $view;
    }

    public function import(Request $request)
    {
        $request->validate([
            'students' => 'required|file|mimes:xlsx,xls',
        ]);

        $import = new StudentsImport();
        Excel::import($import, $request->file('students'));

        $importedCount = $import->importedCount;
        $failedRows = collect($import->failedRows);
        $skippedCount = $failedRows->count();

        $processedFailedRows = $failedRows->map(function ($row) {
            $errorText = collect($row['errors'])->flatten()->implode(', ');
            return array_merge($row['values'], [
                'row'   => $row['row'],
                'error' => $errorText,
            ]);
        });

        if ($skippedCount > 0) {
            session()->put('failed_rows', $processedFailedRows->toArray());
        }

        $filename = $request->file('students')->getClientOriginalName();

        StudentImport::create([
            'filename'       => $filename,
            'imported_count' => $import->importedCount,
            'skipped_count'  => count($import->failedRows),
            'error_rows'     => json_encode($import->failedRows),
        ]);
        return response()->json([
            'message'       => "Imported {$importedCount} students. Skipped {$skippedCount} row(s).",
            'imported'      => $importedCount,
            'skipped'       => $skippedCount,
            'failed_rows'   => $processedFailedRows,
            'download_url'  => $skippedCount > 0 ? route('students.failed-download') : null,
        ]);
    }


    public function importedStudents(Request $request)
    {

        $imported = StudentImport::latest()->get();

        $view = view('students.imported', compact('imported'));

        if ($request->ajax()) {
            // renderSections() returns an array keyed by section name
            $sections = $view->renderSections();
            // return just the “content” section HTML
            return $sections['content'];
        }

        return $view;
    }


    public function downloadFailedRows()
    {
        $data = session('failed_rows');

        if (!$data) {
            abort(404, 'No failed rows available for download.');
        }

        return Excel::download(new FailedRowsExport($data), 'failed_rows.xlsx');
    }

    public function export(Request $request)
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function create(Request $request)
    {

        $view = view('students.create');

        if ($request->ajax()) {
            // renderSections() returns an array keyed by section name
            $sections = $view->renderSections();
            // return just the “content” section HTML
            return $sections['content'];
        }

        return $view;
    }
}
