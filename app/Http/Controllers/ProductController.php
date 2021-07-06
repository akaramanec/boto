<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(16);
        return view('index', compact('products'));
    }

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
