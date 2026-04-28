<div class="row">
    <div class="col-md-3"><div class="info-box bg-gradient-dark"><span class="info-box-icon"><i class="fas fa-box-open"></i></span><div class="info-box-content"><span class="info-box-text">Products</span><span class="info-box-number">{{ $products }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-gradient-info"><span class="info-box-icon"><i class="fas fa-user-friends"></i></span><div class="info-box-content"><span class="info-box-text">Customers</span><span class="info-box-number">{{ $customers }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-gradient-success"><span class="info-box-icon"><i class="fas fa-check-double"></i></span><div class="info-box-content"><span class="info-box-text">Completed Orders</span><span class="info-box-number">{{ $completedOrders }}</span></div></div></div>
    <div class="col-md-3"><div class="info-box bg-gradient-warning"><span class="info-box-icon"><i class="fas fa-file-invoice"></i></span><div class="info-box-content"><span class="info-box-text">Quotations</span><span class="info-box-number">{{ $quotations }}</span></div></div></div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card card-dark">
            <div class="card-header"><h3 class="card-title">Fulfillment Performance</h3></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3"><span>Completion rate</span><strong>{{ $completionRate }}%</strong></div>
                <div class="progress progress-sm"><div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div></div>
                <div class="row mt-4">
                    <div class="col-sm-6"><div class="dark-metric-card"><div class="metric-label">Today's Purchases</div><div class="metric-value">{{ $todayPurchases }}</div></div></div>
                    <div class="col-sm-6"><div class="dark-metric-card"><div class="metric-label">Inventory Families</div><div class="metric-value">{{ $inventoryFamilies }}</div></div></div>
                </div>
                <p class="text-muted mb-0 mt-4">This layout is tuned for fixed navigation, high contrast, and a denser control room feel.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-secondary">
            <div class="card-header"><h3 class="card-title">Quick Actions</h3></div>
            <div class="card-body">
                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-block mb-2">Create Order</a>
                <a href="{{ route('purchases.create') }}" class="btn btn-outline-light btn-block mb-2">Record Purchase</a>
                <a href="{{ route('products.create') }}" class="btn btn-outline-info btn-block mb-2">Add Product</a>
                <a href="{{ route('customers.create') }}" class="btn btn-outline-success btn-block">Add Customer</a>
            </div>
        </div>
    </div>
</div>
