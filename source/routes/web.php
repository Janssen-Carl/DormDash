<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\VendorOrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* -------------------- PUBLIC -------------------- */

Route::get('/', function () {

    if (!auth()->check()) {
        return app(HomeController::class)->index();
    }

    return match (auth()->user()->role) {
        'vendor' => redirect()->route('vendor.home'),
        'customer' => redirect()->route('customer.home'),
        default => app(HomeController::class)->index(),
    };
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/home', fn() => redirect('/'));

Route::get('/register', fn() => view('auth/register'));
Route::post('/register', [UserController::class, 'register']);

Route::get('/login', fn() => view('auth/login'))->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);

/* -------------------- AUTH COMMON -------------------- */

Route::get('/test-login/{id}', function ($id) {

    Auth::loginUsingId($id);

    return redirect('/');

});

Route::middleware('auth')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/profile', [UserController::class, 'show']);
    Route::get('/profile/edit', [\App\Http\Controllers\UserController::class, 'edit']);
    Route::post('/profile', [\App\Http\Controllers\UserController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [\App\Http\Controllers\UserController::class, 'updatePhoto'])->name('profile.photo');
    Route::get('/address-payment/add', fn() => view('pages/address-payment-add'));
    Route::post('/address/add', [\App\Http\Controllers\UserController::class, 'addAddress'])->name('address.add');
    Route::post('/payment/add', [\App\Http\Controllers\UserController::class, 'addPayment'])->name('payment.add');
    Route::delete('/address/{id}/delete', [\App\Http\Controllers\UserController::class, 'deleteAddress'])->name('address.delete');
    Route::delete('/payment/{id}/delete', [\App\Http\Controllers\UserController::class, 'deletePayment'])->name('payment.delete');
});

/* -------------------- CUSTOMER ONLY -------------------- */

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{item_id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item_id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders-overview', [OrderController::class, 'overview'])->name('orders.overview');
    Route::get('/analytics', [OrderController::class, 'analytics'])->name('orders.analytics');
    Route::get('/track/{tracking}', fn() => view('pages.track'))->name('orders.track');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/customer/home', [HomeController::class, 'index'])
        ->name('customer.home');
});

/* -------------------- VENDOR ONLY -------------------- */

Route::middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('/vendor-home', [\App\Http\Controllers\VendorHomeController::class, 'index'])
        ->name('vendor.home');

    Route::get('/vendor-products', [VendorProductController::class, 'index'])->name('vendor.products');

    Route::get('/vendor-profile', [UserController::class, 'showVendor'])->name('vendor.profile');

    Route::get('/vendor-profile/vendor-profile-edit', [UserController::class, 'editVendor'])->name('vendor.profile.edit');

    // Support POST from vendor profile edit form
    Route::post('/vendor-profile', [UserController::class, 'update'])->name('vendor.profile.update');

    Route::get('/vendor-profile/vendor-address-add', fn() => view('pages.vendor-address-add'))->name('vendor.address.add');
    Route::post('/vendor-profile/vendor-address-add', [UserController::class, 'addAddress'])->name('vendor.address.add.store');

    Route::get('/vendor-orders', [VendorOrderController::class, 'index'])->name('vendor.orders');
    Route::post('/vendor-orders/{order}/confirm', [VendorOrderController::class, 'confirm'])->name('vendor.orders.confirm');
    Route::post('/vendor-orders/{order}/ship', [VendorOrderController::class, 'ship'])->name('vendor.orders.ship');
    Route::post('/vendor-orders/{order}/deliver', [VendorOrderController::class, 'deliver'])->name('vendor.orders.deliver');

    Route::get('/vendor-analytics', [DashboardController::class, 'index'])->name('vendor.analytics');

    Route::get('/vendor-products/{item}/edit', [VendorProductController::class, 'edit'])->name('vendor.products.edit');
    Route::post('/vendor-products/{item}/edit', [VendorProductController::class, 'update'])->name('vendor.products.update');
    Route::delete('/vendor-products/{item}', [VendorProductController::class, 'destroy'])->name('vendor.products.destroy');
    Route::post('/vendor-products/{item}/edit-bundle', [VendorProductController::class, 'updateBundle'])->name('vendor.products.updateBundle');
    Route::post('/vendor-products/{item}/restock', [VendorProductController::class, 'restock'])->name('vendor.products.restock');

    Route::get('/vendor-profile/vendor-product-add', [VendorProductController::class, 'create'])->name('vendor.products.add');
    Route::get('/vendor-profile/vendor-product-add-bundle', [VendorProductController::class, 'createBundle'])->name('vendor.products.bundle');
    Route::post('/vendor-profile/vendor-product-add-bundle', [VendorProductController::class, 'storeBundle'])->name('vendor.products.storeBundle');

    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/items/create', [VendorProductController::class, 'create'])
            ->name('items.create');
        Route::post('/items', [VendorProductController::class, 'store'])
            ->name('items.store');
    });
});

Route::get('/check-auth', function () {
    $userId = Auth::id(); // default guard
    $user = Auth::user();

    if ($userId) {
        return response()->json([
            'logged_in' => true,
            'user_id' => $userId,
            'user' => $user
        ]);
    } else {
        return response()->json([
            'logged_in' => false,
            'message' => 'No user is logged in.'
        ]);
    }


});
Route::get('/test-item', function () {
    try {
        $item = \App\Models\Item::create([
            'vendor_id' => 1, // hardcoded for testing
            'name' => 'Test Item',
            'description' => 'Test description',
            'price' => 100,
            'stock' => 10,
            'sku' => 'TESTSKU',
            'brand' => 'TestBrand',
            'barcode' => '1234567890',
            'unit_type' => 'piece',
            'unit_value' => 1,
            'is_bundle' => 0,
            'is_perishable' => 0,
            'is_available' => 1,
            'has_expiry' => 0,
            'is_active' => 1,
        ]);

        return 'Item created: ' . $item->item_id;
    } catch (\Exception $e) {
        return 'Insert failed: ' . $e->getMessage();
    }
});
