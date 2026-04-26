<?php

use Illuminate\Support\Facades\Route;
use Paperdoc\Facades\Paperdoc;

Route::get('/', function () {

// Create and save
$doc = Paperdoc::create('docx', 'Invoice #1042');
$doc->openSection()->addParagraph('Amount due: $500');
Paperdoc::save($doc, storage_path('invoices/1042.docx'));

// Parse an existing file
// $doc = Paperdoc::open('uploads/report.xlsx');

// // Convert directly (file to file)
// Paperdoc::convert('report.docx', 'report.pdf', 'pdf');

// // Render document to string (e.g. HTML)
// $html = Paperdoc::renderAs($doc, 'html');

// // Batch open multiple files
// $docs = Paperdoc::openBatch([
//     'file1.pdf',
//     'file2.docx',
//     'file3.xlsx',
// ]);
});
