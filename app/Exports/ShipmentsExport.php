<?php

namespace App\Exports;

use App\Models\Shipment;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ShipmentsExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    function array(): array
    {
        $shipments = Shipment::with('sale','sale.client','sale.warehouse')->where('deleted_at', '=', null);

        if ($shipments->isNotEmpty()) {
            foreach ($shipments as $shipment) {

                $item['date'] = $shipment['date'];
                $item['shipment_ref'] = $shipment['Ref'];
                $item['sale_ref'] = $shipment['sale']['Ref'];
                $item['delivered_to'] = $shipment['delivered_to'];
                $item['warehouse_name'] = $shipment['sale']['warehouse']->name;
                $item['customer_name'] = $shipment['sale']['client']->name;
                $item['status'] = $shipment['status'];
                
                $data[] = $item;
            }
        } else {
            $data = [];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:G1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                ];

            },
        ];

    }

    public function headings(): array
    {
        return [
            'Date',
            'Shipment Ref',
            'Sale Ref',
            'Delivered to',
            'Warehouse',
            'Customer',
            'Status',
        ];
    }
}
