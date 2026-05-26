<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        $app->useStoragePath(dirname(__DIR__, 2).'/storage');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.foreign_key_constraints', true);
        $app['config']->set('logging.default', 'null');
        $app['config']->set('session.driver', 'array');
        
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        Schema::dropIfExists('customers');
        Schema::dropIfExists('addresses');
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

        Schema::create('customers', function (Blueprint $table): void {
            $table->unsignedInteger('customer_id')->primary();
            $table->unsignedInteger('primary_address_id')->nullable();
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table): void {
            $table->increments('address_id');
            $table->unsignedInteger('user_id');
            $table->string('street');
            $table->string('city');
            $table->string('province_state');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('email');
            $table->string('country');
            $table->timestamps();
        });
    }

    public function test_customer_can_set_default_address(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $customer = Customer::create([
            'customer_id' => $user->user_id,
            'primary_address_id' => null,
        ]);

        $address1 = Address::create([
            'user_id' => $user->user_id,
            'street' => 'Street 1',
            'city' => 'City 1',
            'province_state' => 'State 1',
            'postal_code' => '1234',
            'phone' => '123456',
            'email' => 'test@example.com',
            'country' => 'Country 1',
        ]);

        $address2 = Address::create([
            'user_id' => $user->user_id,
            'street' => 'Street 2',
            'city' => 'City 2',
            'province_state' => 'State 2',
            'postal_code' => '5678',
            'phone' => '789012',
            'email' => 'test@example.com',
            'country' => 'Country 2',
        ]);

        $customer->primary_address_id = $address1->address_id;
        $customer->save();

        $response = $this->actingAs($user)
            ->post("/address/{$address2->address_id}/default");

        $response->assertRedirect('/profile');

        $this->assertDatabaseHas('customers', [
            'customer_id' => $user->user_id,
            'primary_address_id' => $address2->address_id,
        ]);
    }
}
