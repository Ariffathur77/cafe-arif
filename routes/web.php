<?php

use App\Livewire\CategoryManagement;
use App\Livewire\InventoryManagement;
use App\Livewire\MenuDisplay;
use App\Livewire\MenuManagement;
use App\Livewire\OrderDashboard;
use App\Livewire\OrderTracker;
use App\Livewire\PromoManagement;
use App\Livewire\RecipeManagement;
use App\Livewire\ReportPage;
use App\Livewire\StockHistory;
use App\Livewire\TableDashboard;
use App\Livewire\TableManagement;
use App\Livewire\UserManagement;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

use App\Models\Table;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

Route::get('/', function () {
    return view('auth.login');
});

// RUTE UNTUK PELANGGAN (TIDAK PERLU LOGIN)
Route::get('/table/{table:barcode_identifier}', MenuDisplay::class)->name('customer.menu');
Route::get('/order/success/{order:order_code}', OrderTracker::class)->name('order.success');


// RUTE UNTUK ADMIN/STAF (PERLU LOGIN)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Livewire\MainDashboard::class)
        ->middleware(['role.redirect'])
        ->name('dashboard');

    // Pesanan & Meja
    Route::get('/admin/orders', OrderDashboard::class)->name('admin.orders');
    Route::get('/admin/table-status', TableDashboard::class)->name('admin.table-status');

    // Manajemen Data
    Route::get('/admin/menus', MenuManagement::class)->name('admin.menus');
    Route::get('/admin/menus/{menu}/recipes', RecipeManagement::class)->name('admin.recipes');
    Route::get('/admin/categories', CategoryManagement::class)->name('admin.categories');
    Route::get('/admin/tables', TableManagement::class)->name('admin.tables');
    Route::get('/admin/inventory', InventoryManagement::class)->name('admin.inventory');
    Route::get('/admin/promos', PromoManagement::class)->name('admin.promos');

    // Laporan
    Route::get('/admin/reports', ReportPage::class)->name('admin.reports');

    // Khusus Owner
    Route::get('/admin/users', UserManagement::class)->name('admin.users');
    Route::get('/admin/stock-history', StockHistory::class)->name('admin.stock-history');

    // QR Code Generator
    Route::get('/admin/tables/{table}/qr-code', function (Table $table) {
        $url = route('customer.menu', $table->barcode_identifier);

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($url);

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    })->name('admin.tables.qr');

    Route::get('/admin/orders/{order}/receipt', function (Order $order) {
        // Muat relasi payment sebelum mengirim ke view
        $order->load('payment');
        return view('receipt', ['order' => $order]);
    })->name('admin.orders.receipt');
});
