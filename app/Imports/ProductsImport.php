<?php

namespace App\Imports;

use App\Models\Product;
use Barryvdh\Reflection\DocBlock\Type\Collection;
use Google\Service\Sheets\ValueRange;
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
        foreach ($importArray as $key => $product) {
            $tempArray[$key]['id'] = (int)$product[0];
            $tempArray[$key]['name'] = $product[1];
            $tempArray[$key]['quantity'] = (int)$product[2];
            $tempArray[$key]['description'] = $product[3];
            $tempArray[$key]['price'] = (double)$product[4];
            $tempArray[$key]['image'] = $product[5];
            $tempArray[$key]['availability'] = (int)$product[6];
        }
        return $tempArray;
    }

    public function saveFileData(array $importData): void
    {
        $importArray = $this->getArrayWithKeys($importData[0]);
        $this->saveData($importArray);
    }

    public function saveGoogleSheetData(ValueRange $importData): void
    {
        $importArray = (array)$importData->values;
        unset($importArray[0]);
        $importArray = $this->getArrayWithKeys($importArray);
        $this->saveData($importArray);
    }

    protected function saveData(array $importArray): void
    {
        foreach ($importArray as $importProduct) {
            $product = Product::firstOrCreate($importProduct);
            $product->save();
        }
    }
}
