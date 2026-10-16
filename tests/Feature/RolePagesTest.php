<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_public_pages_load(): void
    {
        foreach (['/', '/menu', '/cart', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_each_role_can_open_its_pages(): void
    {
        $pages = [
            'admin' => ['/admin/dashboard', '/admin/menu', '/admin/menu/create', '/admin/benefit', '/admin/promo'],
            'owner' => ['/owner/dashboard', '/owner/laporan'],
            'kasir' => ['/kasir/dashboard', '/kasir/pos', '/kasir/pembayaran', '/kasir/pesanan'],
            'dapur' => ['/dapur/dashboard', '/dapur/pesanan', '/dapur/menu'],
        ];

        foreach ($pages as $role => $urls) {
            $user = User::where('role', $role)->firstOrFail();

            foreach ($urls as $url) {
                $this->actingAs($user)->get($url)->assertOk();
            }
        }
    }

    public function test_role_middleware_blocks_other_roles(): void
    {
        $kasir = User::where('role', 'kasir')->firstOrFail();

        $this->actingAs($kasir)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/kasir/dashboard')->assertRedirect(route('login'));
    }
}
