<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\Category;

class ProductsExportSample implements FromArray, WithEvents, WithMultipleSheets
{
    /**
     * @return array
     */
    public function array(): array
    {
        // First sheet will just be the header
        return [
            ['Product Name', 'Category', 'Description', 'Price', 'Stock Quantity'],
            ['(Enter Product Name)', '(Select Category)', '(Enter Description)', '(Enter Price)', '(Enter Stock Quantity)']
        ];
    }

    /**
     * Register events for data validation and category reference
     */
    public function registerEvents(): array
    {
        // Fetch all categories for the sample export (with ID and Name)
        $categories = Category::all()->pluck('name', 'id')->toArray();  // Pluck ID and Name as a key-value pair

        // Format the categories as a comma-separated list of "ID - Name"
        $categoryIds = implode(',', array_keys($categories));  // Get only the IDs
        $categoryNames = implode(',', $categories);  // Get only the Names

        return [
            AfterSheet::class => function (AfterSheet $event) use ($categoryIds, $categoryNames) {
                // Apply data validation for the 'Category' column in the first sheet
                // Set a dropdown list for the Category field with IDs
                $event->sheet->getDelegate()->getCell('B2')->getDataValidation()->setType('list')
                    ->setAllowBlank(true)
                    ->setShowDropDown(true)
                    ->setFormula1('"' . $categoryIds . '"');  // List of category IDs for the dropdown
                
                // Apply data validation to the entire Category column (B)
                $event->sheet->getDelegate()->getStyle('B2:B1000')->getDataValidation()->setType('list')
                    ->setAllowBlank(true)
                    ->setShowDropDown(true)
                    ->setFormula1('"' . $categoryIds . '"');
            },
        ];
    }

    /**
     * Returns the multiple sheets for the export.
     * The first sheet will be the header and the second sheet will contain the categories.
     */
    public function sheets(): array
    {
        return [
            // First sheet for product headers, named "sample"
            new class implements FromArray, WithTitle {
                public function array(): array
                {
                    return [
                        ['product_name', 'category_id', 'description', 'price', 'quantity'],
                    ];
                }

                public function title(): string
                {
                    return 'sample';  // Name of the first sheet
                }
            },
            
            // Second sheet for categories, named "categories"
            new class implements FromArray, WithTitle {
                public function array(): array
                {
                    // Fetch all categories for the second sheet (ID and Name)
                    $categories = Category::all()->pluck('name', 'id')->toArray();

                    // Prepare the data to create two rows: one for IDs and one for Names
                    $categoryIds = array_keys($categories);  // Get the IDs (keys)
                    $categoryNames = array_values($categories);  // Get the Names (values)

                    return [
                        $categoryIds,  // First row: IDs
                        $categoryNames  // Second row: Names
                    ];
                }

                public function title(): string
                {
                    return 'categories';  // Name of the second sheet
                }
            },
        ];
    }
}
