<?php

use App\Http\Controllers\admin\Paymentcontroller;
use App\Http\Controllers\Admin\RentalsController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\ActivityController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\RentalController;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Route;
use Midtrans\Config;
use Midtrans\Transaction;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Register Page
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');

// Login page
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// Logout
Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout.process');

// Admin Dashboard
Route::get('/admin/dashboard', [App\Http\Controllers\admin\DashboardController::class, 'index'])->name('admin.dashboard');

// User management
Route::get('/admin/users', [App\Http\Controllers\admin\UserController::class, 'showPage'])->name('admin.users');
Route::post('/admin/users/insert', [App\Http\Controllers\admin\UserController::class, 'insert'])->name('admin.users.insert');
Route::put('/admin/users/update/{id}', [App\Http\Controllers\admin\UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/delete/{id}', [App\Http\Controllers\admin\UserController::class, 'destroy'])->name('admin.users.delete');

// Equipment Management
// Sesuaikan namespace dengan controller (Admin huruf kapital)
Route::get('/admin/equipments', [App\Http\Controllers\Admin\EquipmentsController::class, 'index'])->name('admin.equipments');
Route::post('/equipments/insert', [App\Http\Controllers\Admin\EquipmentsController::class, 'insert'])->name('admin.equipments.insert');
Route::put('/equipments/update/{id}', [App\Http\Controllers\Admin\EquipmentsController::class, 'update'])->name('admin.equipments.update');
Route::delete('/equipments/delete/{id}', [App\Http\Controllers\Admin\EquipmentsController::class, 'destroy'])->name('admin.equipments.delete');

// Equipment Categories routes
Route::get('/admin/equipment-categories', [App\Http\Controllers\admin\EquipmentCategoriesController::class, 'index'])->name('admin.equipment-categories');
Route::post('/equipment-categories/insert', [App\Http\Controllers\admin\EquipmentCategoriesController::class, 'insert'])->name('admin.equipment-categories.insert');
Route::get('/equipment-categories/{id}', [App\Http\Controllers\admin\EquipmentCategoriesController::class, 'show'])->name('admin.equipment-categories.show');
Route::put('/equipment-categories-update/{id}', [App\Http\Controllers\admin\EquipmentCategoriesController::class, 'update'])->name('admin.equipment-categories.update');
Route::delete('/equipment-categories-delete/{id}', [App\Http\Controllers\admin\EquipmentCategoriesController::class, 'destroy'])->name('admin.equipment-categories.delete');

// Rental
Route::get('/admin/rentals', [RentalsController::class, 'index'])->name('admin.rentals.index');
Route::get('/admin/rentals/{id}', [RentalsController::class, 'show'])->name('admin.rentals.show'); // Opsional jika pakai AJAX, tapi kita pakai modal JS
Route::put('/admin/rentals/{id}/update-status', [RentalsController::class, 'updateStatus'])->name('admin.rentals.updateStatus');
Route::delete('/admin/rentals/{id}', [RentalsController::class, 'destroy'])->name('admin.rentals.destroy');

// Export PDF
Route::get(
    '/admin/rentals/export/pdf',
    [App\Http\Controllers\Admin\RentalsController::class, 'exportPdf']
)->name('admin.rentals.export.pdf');

// Priview PDF
Route::get(
    '/admin/rentals/preview/pdf',
    [RentalsController::class, 'exportPdf']
)->name('admin.rentals.preview.pdf');

// Returns
Route::get('/admin/returns', [ReturnController::class, 'index'])->name('admin.return.index');
Route::delete('/admin/returns/destroy{id}', [ReturnController::class, 'destroy'])->name('admin.return.destroy');
Route::put('/admin/return/update{id}', [App\Http\Controllers\Admin\ReturnController::class, 'update'])->name('admin.return.update');
Route::post('/admin/return/store', [App\Http\Controllers\Admin\ReturnController::class, 'store'])->name('admin.return.store');

// Export return data
Route::get('/admin/returns/priview/pdf', [ReturnController::class, 'exportPdf'])->name('admin.return.export-pdf');

// finance report
// Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
// });

Route::get('/admin/finance/dashboard', [App\Http\Controllers\admin\Paymentcontroller::class, 'index'])->name('admin.finance.index');
Route::get('/admin/finance/chart-data', [App\Http\Controllers\admin\Paymentcontroller::class, 'getChartData'])->name('admin.finance.chart-data');

// User
Route::middleware(['auth'])->group(function () {
    // Dashboard user
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // Peminjaman
    Route::prefix('user/rentals')->name('user.rentals.')->group(function () {
        Route::get('/', [RentalController::class, 'index'])->name('index');
        Route::get('/create', [RentalController::class, 'create'])->name('create');
        Route::post('/', [RentalController::class, 'store'])->name('store');
        Route::get('/{id}', [RentalController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [RentalController::class, 'cancel'])->name('cancel');
    });

    // Keranjang
    Route::prefix('user/cart')->name('user.cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{equipmentId}', [CartController::class, 'add'])->name('add');
        Route::put('/update/{cartId}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartId}', [CartController::class, 'remove'])->name('remove');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // Payment routes COD
    Route::get('rentals/receipt/{rental}', [CartController::class, 'receipt'])
        ->name('user.rentals.receipt');
    
    Route::get('rentals/download-receipt/{rental}', [CartController::class, 'downloadReceipt'])
        ->name('user.rentals.download-receipt');

    Route::prefix('user/payments')->name('payments.')->group(function () {
        Route::get('rental/{rental}', [App\Http\Controllers\User\PaymentController::class, 'index'])->name('index');
        Route::post('rental/{rental}/create', [App\Http\Controllers\User\PaymentController::class, 'create'])->name('create');
        Route::get('finish', [App\Http\Controllers\User\PaymentController::class, 'finish'])->name('finish');
        Route::get('unfinish', [App\Http\Controllers\User\PaymentController::class, 'unfinish'])->name('unfinish');
        Route::get('error', [App\Http\Controllers\User\PaymentController::class, 'error'])->name('error');
    });

    // activity
     Route::get('/activity-logs', [App\Http\Controllers\User\ActivityLogController::class, 'index'])
        ->name('user.activity-logs.index');
    Route::get('/activity-logs/{id}', [App\Http\Controllers\User\ActivityLogController::class, 'show'])
        ->name('user.activity-logs.show');
    Route::get('/activity-logs/export/csv', [App\Http\Controllers\User\ActivityLogController::class, 'export'])
        ->name('user.activity-logs.export');
    Route::delete('/activity-logs/{id}', [App\Http\Controllers\User\ActivityLogController::class, 'destroy'])
        ->name('user.activity-logs.destroy');
    Route::delete('/activity-logs/clear/all', [App\Http\Controllers\User\ActivityLogController::class, 'clearAll'])
        ->name('user.activity-logs.clear-all');

    // web.php - tambahkan route callback
    Route::post('payments/callback', [App\Http\Controllers\User\PaymentController::class, 'callback'])
        ->name('payments.callback');
});

// Peminjam atau Staff
Route::middleware(['auth'])->group(function () {
    Route::get('/petugas/dashboard', [App\Http\Controllers\petugas\DashboardController::class, 'index'])->name('petugas.dashboard');

    // List Alat
    Route::get('/petugas/equipments', [App\Http\Controllers\petugas\ItemListController::class, 'index'])->name('petugas.equipments');
    Route::post('/petugas/equipments/insert', [App\Http\Controllers\petugas\ItemListController::class, 'insert'])->name('petugas.equipments.insert');
    Route::put('/petugas/equipments/update/{id}', [App\Http\Controllers\petugas\ItemListController::class, 'update'])->name('petugas.equipments.update');
    Route::delete('/petugas/equipments/delete/{id}', [App\Http\Controllers\petugas\ItemListController::class, 'destroy'])->name('petugas.equipments.delete');

    // Peminjaman
    Route::get('/petugas/peminjaman', [App\Http\Controllers\petugas\PeminjamanController::class, 'index'])->name('petugas.peminjaman');
    Route::put('/petugas/peminjaman/update-status/{id}', [App\Http\Controllers\petugas\PeminjamanController::class, 'updateStatus'])->name('petugas.peminjaman.updateStatus');
    Route::delete('/petugas/peminjaman/delete/{id}', [App\Http\Controllers\petugas\PeminjamanController::class, 'destroy'])->name('petugas.peminjaman.destroy');

    // Pengembalian
    Route::get('/petugas/pengembalian', [App\Http\Controllers\petugas\PegembalianController::class, 'index'])->name('petugas.pengembalian');
    Route::post('/petugas/pengembalian/insert', [App\Http\Controllers\petugas\PegembalianController::class, 'insert'])->name('petugas.pengembalian.insert');
    Route::put('/petugas/pengembalian/update/{id}', [App\Http\Controllers\petugas\PegembalianController::class, 'update'])->name('petugas.pengembalian.update');
    Route::delete('/petugas/pengembalian/delete/{id}', [App\Http\Controllers\petugas\PegembalianController::class, 'destroy'])->name('petugas.pengembalian.destroy');
});

Route::get('/test-midtrans', function () {

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');

    try {
        return Transaction::status('test-order-id');
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/debug-env', function () {
    return [
        'server_key_env' => env('MIDTRANS_SERVER_KEY'),
        'server_key_config' => config('midtrans.server_key'),
    ];
});