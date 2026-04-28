<?php

declare(strict_types=1);

namespace App\Actions\Purchases;

use App\Enums\PurchaseStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportPurchaseReport
{
    public function handle(string $startDate, string $endDate): StreamedResponse
    {
        $rows = DB::table('purchase_details')
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->join('users', 'users.id', '=', 'purchases.created_by')
            ->whereBetween('purchases.date', [$startDate, $endDate])
            ->where('purchases.status', PurchaseStatus::APPROVED->value)
            ->select([
                'purchases.purchase_no',
                'purchases.date as purchase_date',
                'suppliers.name as supplier_name',
                'products.code as product_code',
                'products.name as product_name',
                'purchase_details.quantity',
                'purchase_details.unitcost',
                'purchase_details.total',
                'users.name as created_by',
            ])
            ->get();

        $reportRows = [[
            'Date',
            'No Purchase',
            'Supplier',
            'Product Code',
            'Product',
            'Quantity',
            'Unitcost',
            'Total',
            'Created By',
        ]];

        foreach ($rows as $row) {
            $reportRows[] = [
                'Date' => $row->purchase_date,
                'No Purchase' => $row->purchase_no,
                'Supplier' => $row->supplier_name,
                'Product Code' => $row->product_code,
                'Product' => $row->product_name,
                'Quantity' => $row->quantity,
                'Unitcost' => $row->unitcost,
                'Total' => $row->total,
                'Created By' => $row->created_by,
            ];
        }

        return response()->streamDownload(function () use ($reportRows): void {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($reportRows);

            $writer = new Xls($spreadSheet);
            $writer->save('php://output');
        }, 'purchase-report.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
