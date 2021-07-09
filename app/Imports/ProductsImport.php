<?php

namespace App\Imports;

use App\Models\Product;
use Barryvdh\Reflection\DocBlock\Type\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductsImport implements ToModel, WithStartRow, WithUpserts
{
    public function startRow(): int
    {
        return 2;
    }

    public function uniqueBy(): string
    {
        return 'name';
    }

    public function model(array $row): Product
    {
        return new Product([
            'id' => $row[0],
            'name' => $row[1],
            'quantity' => $row[2],
            'description' => $row[3],
            'price' => $row[4],
            'image' => $row[5],
            'availability' => $row[6],
        ]);
    }

    public function getArrayWithKeys(array $importArray): array
    {
        $tempArray = [];
        foreach ($importArray[0] as $key => $product) {
            $tempArray[$key]['name'] = $product[1];
            $tempArray[$key]['quantity'] = $product[2];
            $tempArray[$key]['description'] = $product[3];
            $tempArray[$key]['price'] = $product[4];
            $tempArray[$key]['image'] = $product[5];
            $tempArray[$key]['availability'] = $product[6];
        }
        return $tempArray;
    }
}
