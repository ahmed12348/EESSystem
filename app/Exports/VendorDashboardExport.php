<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VendorDashboardExport implements WithMultipleSheets
{
    protected $vendorId;

    public function __construct()
    {
        $this->vendorId = auth()->id(); // Get the authenticated vendor ID
    }

    public function sheets(): array
    {
        return [
            new VendorDashboardSheet($this->vendorId),
        ];
    }
}

class VendorDashboardSheet implements FromArray, WithHeadings, WithStyles
{
    protected $vendorId;

    public function __construct($vendorId)
    {
        $this->vendorId = $vendorId;
    }

    public function headings(): array
    {
        return [
            ['Vendor Dashboard Data'],
            ['Metric', 'Value'],
            ['Total Orders', Order::where('vendor_id', $this->vendorId)->count() ?: 0],
            ['Total Revenue', '$' . number_format(OrderItem::whereHas('product', function ($query) {
                $query->where('vendor_id', $this->vendorId);
            })->sum('price') ?: 0, 2)],
            ['Total Customers', User::whereHas('orders', function ($query) {
                $query->where('vendor_id', $this->vendorId);
            })->count() ?: 0],
            ['Total Products', Product::where('vendor_id', $this->vendorId)->count() ?: 0],
            ['Pending Orders', Order::where('vendor_id', $this->vendorId)->where('status', 'pending')->count() ?: 0],
            [],
            ['Latest Orders'],
            ['Order ID', 'Customer', 'Amount', 'Date'],
        ];
    }

    public function array(): array
    {
        $data = [];

        // ✅ Fetch latest 10 orders for the vendor
        $latestOrders = Order::where('vendor_id', $this->vendorId)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        foreach ($latestOrders as $order) {
            $data[] = [
                $order->id,
                $order->customer->name ?? 'N/A',
                '$' . number_format($order->total_price, 2),
                $order->created_at->format('Y-m-d'),
            ];
        }

        $data[] = []; // Empty row separator
        $data[] = ['Top 5 Selling Products'];
        $data[] = ['Product Name', 'Sales Count'];

        // ✅ Fetch top 5 best-selling products
        $topProducts = Product::where('vendor_id', $this->vendorId)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        foreach ($topProducts as $product) {
            $data[] = [
                $product->name,
                $product->order_items_count ?: 0,
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // Title row
            2 => ['font' => ['bold' => true]], // Dashboard Metrics header
            10 => ['font' => ['bold' => true]], // Latest Orders header
            20 => ['font' => ['bold' => true]], // Top Selling Products header
        ];
    }
}
