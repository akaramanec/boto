<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function fileImport()
    {
        $productsImport = new ProductsImport();
        $importArray =  Excel::toArray($productsImport, '/home/alexandr/Завантаження/Items.xlsx');
        $importArray = $productsImport->getArrayWithKeys($importArray);
        foreach ($importArray as $importProduct) {
            $product = Product::firstOrCreate($importProduct);
            $product->save();
        }
        return redirect(route('home'));
    }
}
