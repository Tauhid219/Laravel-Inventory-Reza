<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $products }}</h3><p>Products</p></div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
            <a href="{{ route('products.index') }}" class="small-box-footer">Manage products <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $orders }}</h3><p>Total Orders</p></div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <a href="{{ route('ordersV2.index') }}" class="small-box-footer">View orders <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $purchases }}</h3><p>Purchases</p></div>
            <div class="icon"><i class="fas fa-truck-loading"></i></div>
            <a href="{{ route('purchases.index') }}" class="small-box-footer">Go to purchases <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $suppliers }}</h3><p>Suppliers</p></div>
            <div class="icon"><i class="fas fa-people-carry"></i></div>
            <a href="{{ route('suppliers.index') }}" class="small-box-footer">Supplier directory <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Operations Snapshot</h3>
                <div class="card-tools">
                    <a href="{{ route('ordersV2.create') }}" class="btn btn-sm btn-primary">New Order</a>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-outline-primary">Add Product</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success"><i class="fas fa-check-circle"></i> {{ $completionRate }}%</span>
                            <h5 class="description-header">{{ $completedOrders }}</h5>
                            <span class="description-text">COMPLETED ORDERS</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block border-right">
                            <span class="description-percentage text-info"><i class="fas fa-sitemap"></i> Structured</span>
                            <h5 class="description-header">{{ $inventoryFamilies }}</h5>
                            <span class="description-text">CATEGORY NODES</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <span class="description-percentage text-warning"><i class="fas fa-calendar-day"></i> Today</span>
                            <h5 class="description-header">{{ $todayPurchases }}</h5>
                            <span class="description-text">PURCHASE ENTRIES</span>
                        </div>
                    </div>
                </div>
                <div class="progress mt-4">
                    <div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div>
                </div>
                <p class="text-muted mt-3 mb-0">This mode keeps a familiar light layout for everyday use and faster scannability across product, purchase, and order workflows.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Inventory Mix</h3></div>
            <div class="card-body p-0">
                <ul class="nav flex-column">
                    <li class="nav-item"><span class="nav-link">Categories <span class="float-right badge bg-primary">{{ $categories }}</span></span></li>
                    <li class="nav-item"><span class="nav-link">Sub Categories <span class="float-right badge bg-info">{{ $subCategories }}</span></span></li>
                    <li class="nav-item"><span class="nav-link">Customers <span class="float-right badge bg-success">{{ $customers }}</span></span></li>
                    <li class="nav-item"><span class="nav-link">Quotations <span class="float-right badge bg-warning">{{ $quotations }}</span></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
