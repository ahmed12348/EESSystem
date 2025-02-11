<?php

namespace App\Exports;


use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorOrdersExport implements FromCollection, WithHeadings
{
    protected $vendorId;

    public function __construct($vendorId)
    {
        $this->vendorId = $vendorId;
    }

    public function collection()
    {
        return Order::where('vendor_id', $this->vendorId)
            ->select('id', 'total_price', 'status', 'created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'Order ID' => $order->id,
                    // 'Customer ID' => $order->customer_id ?? 'N/A',
                    'Total Price' => $order->total_price > 0 ? number_format($order->total_price, 2) : '0.00',
                    'Status' => ucfirst($order->status ?? 'Unknown'),
                    'Created At' => $order->created_at->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Order ID', 'Customer ID', 'Total Price', 'Status', 'Created At'];
    }
}

