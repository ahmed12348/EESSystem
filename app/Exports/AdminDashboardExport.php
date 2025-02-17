<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminDashboardExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new AdminDashboardSheet(),
        ];
    }
}

class AdminDashboardSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            ['Admin Dashboard Data'],
            ['Metric', 'Value'],
            ['Total Orders', Order::count() ?: '0'],
            ['Total Revenue', '$' . number_format(Order::sum('total_price') ?: 0, 2)],
            ['Total Customers', User::whereHas('orders')->count() ?: '0'],
            ['Total Products', Product::count() ?: '0'],
            ['Pending Orders', Order::where('status', 'pending')->count() ?: '0'],
            [],
            ['Latest Orders'],
            ['Order ID', 'Vendor', 'Customer', 'Amount', 'Date'],
        ];
    }

    public function array(): array
    {
        $data = [];

        // Latest Orders
        $latestOrders = Order::with(['customer', 'vendor'])
            ->latest()
            ->take(10)
            ->get();

        foreach ($latestOrders as $order) {
            $data[] = [
                $order->id ?: '0',
                $order->vendor->name ?? 'N/A',
                $order->customer->name ?? 'N/A',
                number_format($order->total_price ?: 0, 2) . ' $',
                $order->created_at ? $order->created_at->format('Y-m-d') : 'N/A',
            ];
        }

        $data[] = []; // Empty row separator
        $data[] = ['Top 5 Products'];
        $data[] = ['Product Name', 'Sales Count'];

        // Top 5 Products
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        foreach ($topProducts as $product) {
            $data[] = [
                $product->name ?? 'N/A',
                $product->order_items_count ?: '0',
            ];
        }

        $data[] = []; // Empty row separator
        $data[] = ['Top 5 Vendors'];
        $data[] = ['Vendor Name', 'Total Revenue'];

        // **Fixed Query for Top 5 Vendors**
    
        $topVendors = Vendor::select('vendors.*')
            ->withCount(['products as total_products'])
            ->addSelect([
                'total_revenue' => OrderItem::selectRaw('SUM(order_items.price)')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->whereColumn('products.vendor_id', 'vendors.id')
            ])
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();
        

        foreach ($topVendors as $vendor) {
            $data[] = [
                $vendor->business_name ?? 'N/A',
                number_format($vendor->products_order_items_sum_price ?: 0, 2) . ' $',
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1  => ['font' => ['bold' => true, 'size' => 14]], // Title row
            2  => ['font' => ['bold' => true]], // Header row
            10 => ['font' => ['bold' => true]], // Latest Orders header
            20 => ['font' => ['bold' => true]], // Top Products header
            30 => ['font' => ['bold' => true]], // Top Vendors header
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 30,
            'D' => 20,
            'E' => 20,
        ];
    }
}
