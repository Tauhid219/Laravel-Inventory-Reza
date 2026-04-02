<div class="row">
    <div class="col-md-4"><div class="card card-outline card-primary"><div class="card-body"><div class="compact-stat-label">Products</div><div class="compact-stat-value">{{ $products }}</div><div class="text-muted">Across {{ $categories }} categories and {{ $subCategories }} sub categories</div></div></div></div>
    <div class="col-md-4"><div class="card card-outline card-success"><div class="card-body"><div class="compact-stat-label">Orders</div><div class="compact-stat-value">{{ $orders }}</div><div class="text-muted">{{ $completedOrders }} completed with {{ $completionRate }}% success rate</div></div></div></div>
    <div class="col-md-4"><div class="card card-outline card-warning"><div class="card-body"><div class="compact-stat-label">Partners</div><div class="compact-stat-value">{{ $customers + $suppliers }}</div><div class="text-muted">{{ $customers }} customers and {{ $suppliers }} suppliers</div></div></div></div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0"><h3 class="card-title">At a Glance</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Area</th><th>Count</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td>Purchases today</td><td>{{ $todayPurchases }}</td><td><span class="badge badge-info">Tracking</span></td></tr>
                        <tr><td>Quotations</td><td>{{ $quotations }}</td><td><span class="badge badge-warning">Open</span></td></tr>
                        <tr><td>Inventory nodes</td><td>{{ $inventoryFamilies }}</td><td><span class="badge badge-primary">Structured</span></td></tr>
                        <tr><td>Suppliers</td><td>{{ $suppliers }}</td><td><span class="badge badge-success">Available</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Focus Areas</h3></div>
            <div class="card-body">
                <div class="compact-focus-item"><i class="fas fa-boxes text-primary"></i><span>Inventory setup and stock structure</span></div>
                <div class="compact-focus-item"><i class="fas fa-truck-loading text-warning"></i><span>Purchase entry and approval follow-up</span></div>
                <div class="compact-focus-item"><i class="fas fa-shopping-bag text-success"></i><span>Order completion and due management</span></div>
            </div>
        </div>
    </div>
</div>
