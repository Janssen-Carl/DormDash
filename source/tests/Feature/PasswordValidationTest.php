<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordValidationTest extends TestCase
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
        
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('role');
            $table->string('password');
            $table->string('verification_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table): void {
            $table->unsignedInteger('vendor_id')->primary();
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('website', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function test_registration_fails_with_weak_passwords(): void
    {
        $weakPasswords = [
            'short', // too short
            '12345678', // numeric only
            'abcdefgh', // lowercase only
            'ABCDEFGH', // uppercase only
            'Abcdefgh', // upper + lowercase only (no numbers or symbols)
            'Abcdef12', // upper + lowercase + numbers (no symbols)
        ];

        foreach ($weakPasswords as $password) {
            $response = $this->post('/register', [
                'username' => 'testuser_' . uniqid(),
                'email' => 'test_' . uniqid() . '@example.com',
                'password' => $password,
                'password_confirmation' => $password,
                'role' => 'customer',
            ]);

            $response->assertSessionHasErrors('password');
        }
    }

    public function test_registration_passes_with_strong_password(): void
    {
        $response = $this->post('/register', [
            'username' => 'testcustomer',
            'email' => 'customer@example.com',
            'password' => 'StrongP@ss1',
            'password_confirmation' => 'StrongP@ss1',
            'role' => 'customer',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'username' => 'testcustomer',
            'email' => 'customer@example.com',
        ]);
    }
}
