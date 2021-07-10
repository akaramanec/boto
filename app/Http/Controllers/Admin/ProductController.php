<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileImportRequest;
use App\Imports\ProductsImport;
use App\Services\GoogleSheetService;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $productsImport;

    public function __construct(ProductsImport $productsImport)
    {
        $this->productsImport = $productsImport;
    }

    public function imports()
    {
        return view('admin.file-upload');
    }

    public function fileImport(FileImportRequest $request)
    {
        $importData =  Excel::toArray($this->productsImport,  $request->file('file')->store('temp'));
        $this->productsImport->saveFileData($importData);
        return redirect(route('imports'))->with('status', 'Parsing data complete!');
    }

    public function googleSheetsImport()
    {
        $googleSheetService = new GoogleSheetService();
        $importData = $googleSheetService->getDataFromSheet();
        $this->productsImport->saveGoogleSheetData($importData);
        return redirect(route('imports'))->with('status', 'Parsing data complete!');
    }
}
