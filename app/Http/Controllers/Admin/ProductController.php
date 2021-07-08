<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileImportRequest;
use App\Imports\ProductsImport;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function imports()
    {
        return view('admin.file-upload');
    }

    public function fileImport(FileImportRequest $request)
    {
        $productsImport = new ProductsImport();
        $importArray =  Excel::toArray($productsImport,  $request->file('file')->store('temp'));
        $importArray = $productsImport->getArrayWithKeys($importArray);
        foreach ($importArray as $importProduct) {
            $product = Product::firstOrCreate($importProduct);
            $product->save();
        }
        return redirect(route('home'));
    }
}
