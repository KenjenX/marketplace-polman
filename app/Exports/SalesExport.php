<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select('id', 'created_at', 'total_price', 'status')
                    ->where('status', 'completed')->get();
    }

    public function headings(): array
    {
        return ["ID Pesanan", "Tanggal", "Total Pendapatan", "Status"];
    }
}