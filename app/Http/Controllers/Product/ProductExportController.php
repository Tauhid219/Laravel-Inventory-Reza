<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ProductExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view product')->only(['create']);
    }

    public function create()
    {
        $products = Product::all()->sortBy('product_name');

        // $products = Product::with(['category', 'subcategory'])
        //     ->sortBy('product_name');

        $product_array[] = array(
            'Product Name',
            'Category Name',
            'Sub Category Name',
            'Unit Id',
            'Product Code',
            'Stock',
            'Buying Price',
            'Selling Price',
            'Product Image',
        );

        foreach ($products as $product) {
            $product_array[] = array(
                'Product Name' => $product->name,
                // 'Category Id' => $product->category_id,
                'Category Name' => $product->category ? $product->category->name : 'N/A', // Category Name
                'Subcategory Name' => $product->subcategory ? $product->subcategory->name : 'N/A', // Subcategory Name
                'Unit Id' => $product->unit_id,
                'Product Code' => $product->code,
                'Stock' => $product->quantity,
                'Buying Price' => $product->buying_price,
                'Selling Price' => $product->selling_price,
                'Product Image' => $product->product_image,
            );
        }

        $this->store($product_array);
    }

    public function store($products)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="products.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }
}
