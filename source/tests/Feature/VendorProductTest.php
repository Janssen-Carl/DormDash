<?php

namespace Tests\Feature;

use App\Http\Controllers\VendorProductController;
use App\Models\Item;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tests\TestCase;

class VendorProductTest extends TestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        $app->useStoragePath(dirname(__DIR__, 2).'/storage');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
        $app['config']->set('logging.default', 'null');
        $app['config']->set('logging.channels.null', ['driver' => 'null']);
        $app['config']->set('logging.channels.stack.channels', ['null']);
        $app['config']->set('logging.channels.single.path', dirname(__DIR__, 2).'/storage/logs/laravel.log');
        $app['config']->set('logging.channels.daily.path', dirname(__DIR__, 2).'/storage/logs/laravel.log');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('view.paths', [dirname(__DIR__, 2).'/resources/views']);
        $app['config']->set('view.compiled', dirname(__DIR__, 2).'/storage/framework/views');
        $app->forgetInstance('log');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists(dirname(__DIR__, 2).'/storage/framework/views');
        File::ensureDirectoryExists(dirname(__DIR__, 2).'/storage/logs');

        $this->app['config']->set('view.paths', [dirname(__DIR__, 2).'/resources/views']);
        $this->app['config']->set('view.compiled', dirname(__DIR__, 2).'/storage/framework/views');
        $this->app->forgetInstance('log');
        $this->app->forgetInstance('view');
        $this->app->forgetInstance('blade.compiler');
        $this->app->forgetInstance('view.engine.resolver');
        View::getFinder()->setPaths([dirname(__DIR__, 2).'/resources/views']);
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->createProductTestSchema();
    }

    public function test_vendor_can_create_a_product(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();

        $response = $this
            ->actingAs($vendorUser)
            ->post(route('vendor.items.store'), [
                'name' => 'Iced Coffee',
                'description' => 'Ready to drink coffee.',
                'price' => 89.50,
                'stock' => 25,
                'sku' => 'COFFEE-001',
                'brand' => 'DormDash',
                'barcode' => '1234567890',
                'unit_type' => 'bottle',
                'unit_value' => 1,
                'is_active' => '1',
                'is_available' => '1',
                'is_perishable' => '1',
            ]);

        $response
            ->assertRedirect(route('vendor.products'))
            ->assertSessionHas('success', 'Item and images uploaded successfully!');

        $this->assertDatabaseHas('items', [
            'vendor_id' => $vendorUser->user_id,
            'name' => 'Iced Coffee',
            'price' => 89.50,
            'stock' => 25,
            'sku' => 'COFFEE-001',
            'brand' => 'DormDash',
            'is_active' => 1,
            'is_available' => 1,
            'is_perishable' => 1,
        ]);

        $item = Item::where('sku', 'COFFEE-001')->firstOrFail();

        $this->assertDatabaseHas('item_images', [
            'item_id' => $item->item_id,
            'image' => '/images/items/iced-coffee.jpg',
        ]);
    }

    public function test_vendor_can_update_their_product(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();
        $item = $this->createProductFor($vendorUser, [
            'name' => 'Notebook',
            'price' => 30,
            'stock' => 10,
            'is_available' => 1,
        ]);

        $response = $this
            ->actingAs($vendorUser)
            ->post(route('vendor.products.update', $item), [
                'unit_type' => 'pack',
                'unit_value' => 2,
                'stock' => 40,
                'price' => 45.75,
            ]);

        $response
            ->assertRedirect(route('vendor.products'))
            ->assertSessionHas('success', 'Product updated successfully!');

        $this->assertDatabaseHas('items', [
            'item_id' => $item->item_id,
            'unit_type' => 'pack',
            'unit_value' => 2,
            'stock' => 40,
            'price' => 45.75,
            'is_available' => 0,
        ]);
    }

    public function test_vendor_can_restock_their_product_and_reactivate_availability(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();
        $item = $this->createProductFor($vendorUser, [
            'stock' => 3,
            'is_available' => 0,
        ]);

        $response = $this
            ->actingAs($vendorUser)
            ->post(route('vendor.products.restock', $item), [
                'quantity' => 12,
                'search' => 'snack',
                'status' => 'inactive',
                'sort' => 'stock',
                'dir' => 'asc',
            ]);

        $response
            ->assertRedirect(route('vendor.products', [
                'search' => 'snack',
                'status' => 'inactive',
                'sort' => 'stock',
                'dir' => 'asc',
            ]))
            ->assertSessionHas('success', "Restocked '{$item->name}' with +12 units.");

        $this->assertDatabaseHas('items', [
            'item_id' => $item->item_id,
            'stock' => 15,
            'is_available' => 1,
        ]);
    }

    public function test_vendor_can_soft_delete_their_product(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();
        $item = $this->createProductFor($vendorUser, [
            'is_active' => 1,
            'is_available' => 1,
        ]);

        $response = $this
            ->actingAs($vendorUser)
            ->delete(route('vendor.products.destroy', $item));

        $response
            ->assertRedirect(route('vendor.products'))
            ->assertSessionHas('success', 'Product has been removed successfully.');

        $this->assertDatabaseHas('items', [
            'item_id' => $item->item_id,
            'is_active' => 0,
            'is_available' => 0,
        ]);
    }

    public function test_vendor_products_index_filters_searches_and_limits_to_authenticated_vendor(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();
        $otherVendorUser = $this->createVendorUser([
            'username' => 'other_vendor',
            'email' => 'other-vendor@example.com',
        ]);

        $matchingProduct = $this->createProductFor($vendorUser, [
            'name' => 'Matcha Latte',
            'brand' => 'Campus Cafe',
            'is_active' => 1,
        ]);
        $this->createProductFor($vendorUser, [
            'name' => 'Dorm Chips',
            'brand' => 'Snack Bar',
            'is_active' => 1,
        ]);
        $this->createProductFor($vendorUser, [
            'name' => 'Inactive Matcha',
            'brand' => 'Campus Cafe',
            'is_active' => 0,
        ]);
        $this->createProductFor($otherVendorUser, [
            'name' => 'Matcha From Other Vendor',
            'brand' => 'Campus Cafe',
            'is_active' => 1,
        ]);

        $this->actingAs($vendorUser);

        $response = app(VendorProductController::class)->index(Request::create(
            route('vendor.products', [], false),
            'GET',
            [
                'search' => 'Matcha',
                'status' => 'active',
            ],
        ));

        $products = $response->getData()['products'];

        $this->assertSame('pages.vendor-products', $response->name());
        $this->assertCount(1, $products);
        $this->assertTrue($products->first()->is($matchingProduct));
    }

    public function test_vendor_cannot_manage_another_vendors_product(): void
    {
        $this->withoutExceptionHandling();

        $vendorUser = $this->createVendorUser();
        $otherVendorUser = $this->createVendorUser([
            'username' => 'restricted_vendor',
            'email' => 'restricted-vendor@example.com',
        ]);
        $otherVendorProduct = $this->createProductFor($otherVendorUser);

        $this->assertForbiddenRequest(function () use ($vendorUser, $otherVendorProduct): void {
            $this
                ->actingAs($vendorUser)
                ->post(route('vendor.products.update', $otherVendorProduct), [
                    'stock' => 99,
                    'price' => 99,
                    'is_available' => '1',
                ]);
        });

        $this->assertForbiddenRequest(function () use ($vendorUser, $otherVendorProduct): void {
            $this
                ->actingAs($vendorUser)
                ->post(route('vendor.products.restock', $otherVendorProduct), [
                    'quantity' => 5,
                ]);
        });

        $this->assertForbiddenRequest(function () use ($vendorUser, $otherVendorProduct): void {
            $this
                ->actingAs($vendorUser)
                ->delete(route('vendor.products.destroy', $otherVendorProduct));
        });
    }

    public function test_customer_cannot_access_vendor_product_routes(): void
    {
        $this->withoutExceptionHandling();

        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->assertForbiddenRequest(function () use ($customer): void {
            $this
                ->actingAs($customer)
                ->get(route('vendor.products'));
        });
    }

    private function createVendorUser(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'role' => 'vendor',
        ], $attributes));

        Vendor::create([
            'vendor_id' => $user->user_id,
            'name' => $attributes['name'] ?? 'Test Vendor '.$user->user_id,
            'email' => $user->email,
            'active' => 1,
        ]);

        return $user;
    }

    private function createProductFor(User $vendorUser, array $attributes = []): Item
    {
        return Item::create(array_merge([
            'vendor_id' => $vendorUser->user_id,
            'name' => 'Test Product '.fake()->unique()->numberBetween(1000, 9999),
            'description' => 'A product for testing.',
            'price' => 10.00,
            'stock' => 5,
            'sku' => fake()->unique()->bothify('SKU-####'),
            'brand' => 'Test Brand',
            'is_active' => 1,
            'is_bundle' => 0,
            'is_perishable' => 0,
            'is_available' => 1,
            'has_expiry' => 0,
        ], $attributes));
    }

    private function assertForbiddenRequest(callable $request): void
    {
        try {
            $request();
            $this->fail('The request was not forbidden.');
        } catch (HttpExceptionInterface $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    private function createProductTestSchema(): void
    {
        Schema::dropIfExists('item_images');
        Schema::dropIfExists('items');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('role');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table): void {
            $table->unsignedInteger('vendor_id')->primary();
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website', 255)->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->boolean('active')->default(true);
            $table->string('cover_img', 255)->nullable();
            $table->string('profile_img', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table): void {
            $table->increments('item_id');
            $table->unsignedInteger('vendor_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('sku', 100)->unique()->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('unit_type', 30)->nullable();
            $table->decimal('unit_value', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_bundle')->default(false);
            $table->boolean('is_perishable')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('has_expiry')->default(false);
            $table->timestamps();
        });

        Schema::create('item_images', function (Blueprint $table): void {
            $table->increments('item_image_id');
            $table->unsignedInteger('item_id');
            $table->string('image', 255)->default('/images/items/1/1.jpg');
            $table->timestamps();
        });
    }
}
