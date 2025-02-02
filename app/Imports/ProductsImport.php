<?php
namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsImport implements WithMultipleSheets
{
    /**
     * Handle importing only the first sheet.
     */
    public function sheets(): array
    {
        return [
            0 => new class implements ToModel, WithValidation, WithHeadingRow {
                public function model(array $row)
                {
                    // Check if category exists in the database by category_id
                    $category = Category::find($row['category_id']);  // Assuming category_id is part of the row

                    // If category doesn't exist, return null to skip this row
                    if (!$category) {
                        return null;  // This will skip the row if no valid category is found
                    }

                    // Create and return a new Product instance
                    return new Product([
                        'name' => $row['product_name'],  // Product name column
                        'price' => $row['price'],        // Price column
                        'vendor_id' => Auth::id(),       // Vendor ID from authenticated user
                        'quantity' => $row['quantity'],  // Quantity column
                        'description' => $row['description'],  // Description column
                        'category_id' => $category->id,  // Use category ID from the existing category record
                    ]);
                }

                /**
                 * Define validation rules for the imported data
                 */
                public function rules(): array
                {
                    return [
                        'product_name' => 'required|string',  // Product name is required and must be a string
                        'price' => 'required|numeric',        // Price must be numeric
                        'quantity' => 'required|integer',     // Quantity must be an integer
                        'category_id' => 'nullable|exists:categories,id', // Validate that the category_id exists in the database
                    ];
                }
            }
        ];
    }
}


