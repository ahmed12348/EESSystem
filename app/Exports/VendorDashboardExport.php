<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorDashboardExport implements FromCollection, WithHeadings
{
    protected $vendorId;

    public function __construct()
    {
        $this->vendorId = auth()->id();
    }

    public function headings(): array
    {
        return [
            ['Vendor Dashboard Data'],
            ['Metric', 'Value'],
            ['Total Orders', Order::where('vendor_id', $this->vendorId)->count() ?: '0'],
            ['Total Revenue', '$' . number_format(Order::where('vendor_id', $this->vendorId)->sum('total_price') ?: '0', 2)],
            ['Customers', User::whereHas('orders', function ($query) {
                $query->where('vendor_id', $this->vendorId);
            })->count() ?: '0'],
            ['Total Products', Product::where('vendor_id', $this->vendorId)->count() ?: '0'],
            ['Pending Orders', Order::where('vendor_id', $this->vendorId)->where('status', 'pending')->count() ?:'0'],
            [],
            ['Latest Orders'],
            ['Order ID', 'Customer', 'Amount', 'Date'],
        ];
    }

    public function collection()
    {
        return Order::where('vendor_id', $this->vendorId)
            ->with('customer')
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    $order->id,
                    $order->customer->name ?? 'N/A',
                    '$' . number_format($order->total_price, 2),
                    $order->created_at->format('Y-m-d'),
                ];
            });
    }
}


