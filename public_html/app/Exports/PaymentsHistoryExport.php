<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentsHistoryExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    // Método que retorna los datos que deseas exportar
    public function collection()
    {
        return Order::join('members', 'orders.dni', '=', 'members.rut')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id') 
            ->leftJoin('services', 'order_items.service_id', '=', 'services.id')
            ->leftJoin('readings', 'readings.service_id', '=', 'services.id')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.id')
            ->where('payment_methods.status', 1) 
            ->select(
                'orders.id as order_id',
                'orders.order_code',
                'members.rut as rut', 
                'members.first_name as first_name', 
                'members.last_name as last_name',
                'services.nro as service_nro',
                'members.address as address',
                'order_items.folio',
                'order_items.type_dte',
                'order_items.price',
                'orders.payment_detail',
                'payment_methods.title as payment_method',
                'readings.period',
                'order_items.created_at as created_at' 
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Orden',
            'Rut',
            'Nombres',
            'Apellidos',
            'N° Servicio',
            'Sector',
            'Folio',
            'Tipo Dcto',
            'Monto',
            'Estado de Pago',
            'Método de Pago',
            'Periodo',
            'Fecha'
            
        ];
    }

   
    public function styles($sheet)
    {
       
        $sheet->getStyle('A1:O1')->getFont()->setBold(true);
        return [];
    }

    
    public function title(): string
    {
        return 'Historial de Pagos';
    }
}