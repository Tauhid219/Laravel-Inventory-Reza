<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRolePermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/health', HealthCheckController::class)->name('health');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/theme/{theme}', function (string $theme) {
        $allowedThemes = ['classic', 'dark-fixed', 'compact'];

        abort_unless(in_array($theme, $allowedThemes, true), 404);

        session(['adminlte_theme' => $theme]);

        return redirect()->back();
    })->name('theme.switch');

    // User Management
    Route::resource('/users', UserController::class)->middleware(['auth', 'role:super-admin']); //->except(['show']);
    // Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::put('/user/change-password/{user}', [UserController::class, 'updatePassword'])->middleware(['auth', 'role:super-admin'])->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/quotations', QuotationController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/sub-categories', SubCategoryController::class);
    Route::resource('/units', UnitController::class);

    // Route Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products/import', [ProductImportController::class, 'create'])->name('products.import.view');
        Route::post('/products/import', [ProductImportController::class, 'store'])->name('products.import.store');
        Route::get('/products/export', [ProductExportController::class, 'create'])->name('products.export');
        Route::resource('/products', ProductController::class);
        Route::get('/subcategories/{category_id}', 'getSubCategories');
    });

    // Route Orders
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/pending', 'pendingOrders')->name('orders.pending');
        Route::get('/orders/complete', 'completedOrders')->name('orders.complete');
        Route::get('/orders/create', 'create')->name('orders.create');
        Route::post('/orders', 'store')->name('orders.store');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::put('/orders/{order}', 'update')->middleware(['auth', 'role:super-admin|admin'])->name('orders.update');
        Route::delete('/orders/{order}', 'destroy')->name('orders.destroy');
        Route::get('/orders/details/{order}/download', 'downloadInvoice')->name('orders.downloadInvoice');
    });

    // DUES
    Route::get('/due/orders/', [DueOrderController::class, 'index'])->name('due.index');
    Route::get('/due/order/view/{order}', [DueOrderController::class, 'show'])->name('due.show');
    Route::get('/due/order/edit/{order}', [DueOrderController::class, 'edit'])->name('due.edit');
    Route::put('/due/order/update/{order}', [DueOrderController::class, 'update'])->name('due.update');

    // Route Purchases
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/purchases/approved', 'approvedPurchases')->name('purchases.approvedPurchases');
        Route::get('/purchases/pending', 'pendingPurchases')->name('purchases.pendingPurchases');
        Route::get('/purchases/report', 'dailyPurchaseReport')->name('purchases.dailyPurchaseReport');
        Route::get('/purchases/report/export', 'getPurchaseReport')->name('purchases.getPurchaseReport');
        Route::post('/purchases/report/export', 'exportPurchaseReport')->name('purchases.exportPurchaseReport');

        Route::get('/purchases', 'index')->name('purchases.index');
        Route::get('/purchases/create', 'create')->name('purchases.create');
        Route::post('/purchases', 'store')->name('purchases.store');
        Route::get('/purchases/{purchase}', 'show')->name('purchases.show');
        Route::get('/purchases/{purchase}/edit', 'edit')->name('purchases.edit');
        Route::put('/purchases/{purchase}/edit', 'update')->middleware(['auth', 'role:super-admin|admin'])->name('purchases.update');
        Route::delete('/purchases/{purchase}', 'destroy')->name('purchases.destroy');
    });

    // Route Role and Permission
    Route::middleware(['role:super-admin|admin|demo-admin'])->group(function () {
        Route::resource('/permission', PermissionController::class)->names('pr');
        Route::resource('/role', RoleController::class)->names('rl');
        Route::get('/role/{id}/add-permissions', [RoleController::class, 'addPermissionToRole'])->name('addPermissionToRole');
        Route::put('/role/{id}/give-permissions', [RoleController::class, 'givePermissionToRole'])->name('givePermissionToRole');
        Route::resource('/user', UserRolePermissionController::class)->names('user');
        Route::get('welcome-page', function () {
            return view('role-permission.welcome-page');
        })->name('welcome-page');
    });
});

require __DIR__.'/auth.php';
