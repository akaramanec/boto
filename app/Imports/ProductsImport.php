<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row): Product
    {
        return new Product([
            'name' => $row[1],
            'quantity' => $row[2],
            'description' => $row[3],
            'price' => $row[4],
            'image' => $row[5],
            'availability' => $row[6],
        ]);
    }
}
