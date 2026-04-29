<?php

namespace App\Http\Controllers\Purchase;

use App\Actions\Purchases\CompletePurchase;
use App\Actions\Purchases\CreatePurchase;
use App\Actions\Purchases\ExportPurchaseReport;
use App\Enums\PurchaseStatus;
use App\Data\Purchases\CreatePurchaseData;
use App\Exceptions\Purchases\InvalidPurchaseApproval;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view purchase')->only(['index', 'show', 'approvedPurchases', 'pendingPurchases', 'dailyPurchaseReport', 'getPurchaseReport', 'exportPurchaseReport']);
        $this->middleware('permission:create purchase')->only(['create', 'store']);
        $this->middleware('permission:update purchase')->only(['edit', 'update']);
        $this->middleware('permission:delete purchase')->only(['destroy']);
        $this->middleware('deny.demo')->only(['create', 'store', 'edit', 'update', 'destroy', 'getPurchaseReport', 'exportPurchaseReport']);
    }

    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::with('supplier')->latest()->get(),
        ]);
    }

    public function approvedPurchases()
    {
        $purchases = Purchase::with(['supplier', 'details.product.category', 'details.product.subCategory'])
            ->where('status', PurchaseStatus::APPROVED)
            ->latest()
            ->get(); // 1 = approved

        return view('purchases.approved-purchases', [
            'purchases' => $purchases,
        ]);
    }

    public function pendingPurchases()
    {
        $purchases = Purchase::with(['supplier', 'details.product.category', 'details.product.subCategory'])
            ->where('status', PurchaseStatus::PENDING) // Assuming 0 = pending
            ->latest()
            ->get();

        return view('purchases.pending-purchases', [
            'purchases' => $purchases,
        ]);
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'details', 'createdBy', 'updatedBy']);

        $products = PurchaseDetails::where('purchase_id', $purchase->id)->get();

        return view('purchases.details-purchase', [
            'purchase' => $purchase,
            'products' => $products
        ]);
    }

    public function edit(Purchase $purchase)
    {
        // N+1 Problem if load 'createdBy', 'updatedBy',
        $purchase->load(['supplier', 'details']);

        return view('purchases.edit', [
            'purchase' => $purchase,
        ]);
    }

    public function create()
    {
        return view('purchases.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'suppliers' => Supplier::select(['id', 'name'])->get(),
        ]);
    }

    public function store(StorePurchaseRequest $request, CreatePurchase $createPurchase)
    {
        $createPurchase->handle(CreatePurchaseData::fromArray($request->validatedForCreation()));

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been created!');
    }

    public function update(Purchase $purchase, CompletePurchase $completePurchase)
    {
        try {
            $completePurchase->handle($purchase);
        } catch (InvalidPurchaseApproval $e) {
            return redirect()
                ->route('purchases.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been approved!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase has been deleted!');
    }

    public function dailyPurchaseReport()
    {
        $purchases = Purchase::with(['supplier'])
            //->where('purchase_status', 1)
            ->where('date', today()->format('Y-m-d'))->get();

        return view('purchases.daily-report', [
            'purchases' => $purchases,
        ]);
    }

    public function getPurchaseReport()
    {
        return view('purchases.report-purchase');
    }

    public function exportPurchaseReport(Request $request, ExportPurchaseReport $exportPurchaseReport)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|string|date_format:Y-m-d',
            'end_date' => 'required|string|date_format:Y-m-d',
        ]);

        return $exportPurchaseReport->handle(
            $validatedData['start_date'],
            $validatedData['end_date'],
        );
    }
}
