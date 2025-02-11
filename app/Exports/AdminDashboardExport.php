<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminDashboardExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            ['Admin Dashboard Data'],
            ['Metric', 'Value'],
            ['Total Orders', Order::count() ?: '0'],
            ['Total Revenue', '$' . number_format(Order::sum('total_price') ?: '0', 2)],
            ['Total Customers', User::whereHas('orders')->count() ?: '0'],
            ['Total Products', Product::count() ?: '0'],
            ['Pending Orders', Order::where('status', 'pending')->count() ?: '0'],
            [],
            ['Latest Orders'],
            ['Order ID', 'Vendor', 'Customer', 'Amount', 'Date'],
        ];
    }

    public function collection()
    {
        return Order::with(['customer', 'vendor'])
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    $order->id,
                    $order->vendor->name ?? 'N/A',
                    $order->customer->name ?? 'N/A',
                    '$' . number_format($order->total_price, 2),
                    $order->created_at->format('Y-m-d'),
                ];
            });
    }
}
