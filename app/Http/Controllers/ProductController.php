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
        try {
            Excel::import(new ProductsImport(), '/home/alexandr/Завантаження/Items.xlsx');
        } catch (\Exception $exception) {
            return response($exception->getMessage(), 503);
        }
        return redirect(route('home'));
    }
}
