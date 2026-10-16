<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function login(string $role = 'kasir', string $password = 'password123'): string
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => "{$role}@sultaf.com",
            'password' => $password,
        ])->assertOk()->assertJsonPath('success', true);

        return $response->json('access_token');
    }

    private function auth(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }

    private function orderPayload(array $overrides = []): array
    {
        return array_merge([
            'nomor_meja'     => 4,
            'nama_pelanggan' => 'Budi',
            'no_telepon'     => '0811222',
            'items'          => [
                ['menu_id' => Menu::first()->id_menu, 'qty' => 2],
            ],
        ], $overrides);
    }

    /* ---------- auth ---------- */

    public function test_login_returns_token_with_role_scopes(): void
    {
        $this->postJson('/api/auth/login', ['email' => 'kasir@sultaf.com', 'password' => 'password123'])
            ->assertOk()
            ->assertJsonPath('token_type', 'bearer')
            ->assertJsonPath('user.role', 'kasir')
            ->assertJsonPath('user.scopes', ['orders:read', 'orders:write', 'payments:write'])
            ->assertJsonStructure(['access_token', 'expires_in']);
    }

    public function test_login_rejects_wrong_password_and_invalid_input(): void
    {
        $this->postJson('/api/auth/login', ['email' => 'kasir@sultaf.com', 'password' => 'nope'])
            ->assertStatus(401)
            ->assertJsonPath('error', 'invalid_credentials');

        $this->postJson('/api/auth/login', ['email' => 'not-an-email'])->assertStatus(422);
    }

    public function test_me_returns_profile_for_valid_token_only(): void
    {
        $token = $this->login('admin');

        $this->getJson('/api/auth/me', $this->auth($token))
            ->assertOk()
            ->assertJsonPath('user.email', 'admin@sultaf.com')
            ->assertJsonPath('user.scopes', ['admin:all']);

        $this->getJson('/api/auth/me')->assertStatus(401)->assertJsonPath('error', 'token_absent');
        $this->getJson('/api/auth/me', $this->auth('garbage.token.value'))->assertStatus(401);
    }

    public function test_refresh_issues_new_token_and_logout_invalidates_it(): void
    {
        $token = $this->login();

        $new = $this->postJson('/api/auth/refresh', [], $this->auth($token))
            ->assertOk()
            ->json('access_token');

        $this->assertNotSame($token, $new);

        // The old token was blacklisted by the refresh.
        $this->getJson('/api/auth/me', $this->auth($token))->assertStatus(401);
        $this->getJson('/api/auth/me', $this->auth($new))->assertOk();

        $this->postJson('/api/auth/logout', [], $this->auth($new))->assertOk();
        $this->getJson('/api/auth/me', $this->auth($new))->assertStatus(401);
    }

    /* ---------- scopes ---------- */

    public function test_scopes_are_enforced(): void
    {
        $dapur = $this->login('dapur');

        $this->getJson('/api/waiter/tables', $this->auth($dapur))->assertOk();
        $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($dapur))
            ->assertStatus(403)
            ->assertJsonPath('required_scope', 'orders:write');
        $this->postJson('/api/payments/intent', [], $this->auth($dapur))->assertStatus(403);

        // admin:all bypasses scope checks
        $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($this->login('admin')))
            ->assertStatus(201);
    }

    public function test_waiter_endpoints_require_a_token(): void
    {
        $this->getJson('/api/waiter/tables')->assertStatus(401);
        $this->postJson('/api/waiter/orders', $this->orderPayload())->assertStatus(401);
        $this->postJson('/api/payments/intent', [])->assertStatus(401);
    }

    /* ---------- waiter orders ---------- */

    public function test_waiter_can_create_order_and_see_table_occupied(): void
    {
        $token = $this->login();
        $menu = Menu::first();

        $created = $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($token))
            ->assertStatus(201)
            ->assertJsonPath('data.tipe_pesanan', 'dine_in')
            ->assertJsonPath('data.status_pembayaran', 'menunggu')
            ->assertJsonPath('data.detail_transaksi.0.jumlah', 2);

        // subtotal + 10% tax
        $this->assertEquals($menu->harga * 2 * 1.10, $created->json('data.total_bayar'));
        $this->assertSame(1, TransaksiPenjualan::count());
        $this->assertDatabaseHas('detail_transaksis', ['id_menu' => $menu->id_menu, 'jumlah' => 2]);

        $code = $created->json('data.kode_transaksi');
        $this->assertStringStartsWith('WTR-', $code);

        $tables = $this->getJson('/api/waiter/tables', $this->auth($token))->assertOk()->json('tables');
        $this->assertCount(20, $tables);
        $this->assertSame('occupied', $tables[3]['status']);
        $this->assertSame($code, $tables[3]['order_code']);
        $this->assertSame('available', $tables[0]['status']);

        // by code and by id
        $this->getJson("/api/waiter/orders/{$code}", $this->auth($token))->assertOk()->assertJsonPath('data.nama_pelanggan', 'Budi');
        $id = $created->json('data.id_transaksi');
        $this->getJson("/api/waiter/orders/{$id}", $this->auth($token))->assertOk()->assertJsonPath('data.kode_transaksi', $code);
        $this->getJson('/api/waiter/orders/UNKNOWN', $this->auth($token))->assertStatus(404);
    }

    public function test_waiter_order_validation(): void
    {
        $token = $this->login();

        $this->postJson('/api/waiter/orders', $this->orderPayload(['nomor_meja' => 99]), $this->auth($token))
            ->assertStatus(422)->assertJsonValidationErrors('nomor_meja');
        $this->postJson('/api/waiter/orders', $this->orderPayload(['items' => []]), $this->auth($token))
            ->assertStatus(422)->assertJsonValidationErrors('items');
        $this->postJson('/api/waiter/orders', $this->orderPayload(['items' => [['menu_id' => 9999, 'qty' => 1]]]), $this->auth($token))
            ->assertStatus(422)->assertJsonValidationErrors('items.0.menu_id');

        $this->assertSame(0, TransaksiPenjualan::count());
    }

    /* ---------- payments ---------- */

    public function test_create_payment_intent_returns_mock_intent_without_stripe_keys(): void
    {
        $token = $this->login();
        $order = $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($token))->json('data');

        $this->postJson('/api/payments/intent', [
            'order_reference' => $order['kode_transaksi'],
            'amount'          => $order['total_bayar'],
        ], $this->auth($token))
            ->assertOk()
            ->assertJsonPath('data.provider', 'mock')
            ->assertJsonStructure(['data' => ['intent_id', 'client_secret', 'amount', 'currency']]);

        $this->postJson('/api/payments/intent', ['order_reference' => 'NOPE', 'amount' => 5000], $this->auth($token))
            ->assertStatus(422);
        $this->postJson('/api/payments/intent', ['order_reference' => $order['kode_transaksi'], 'amount' => 10], $this->auth($token))
            ->assertStatus(422);
    }

    private function signedWebhook(array $payload, ?string $secret = null)
    {
        $body = json_encode($payload);
        $signature = hash_hmac('sha256', $body, $secret ?? config('services.payment.webhook_secret'));

        return $this->call('POST', '/api/payments/webhook', [], [], [], [
            'CONTENT_TYPE'     => 'application/json',
            'HTTP_ACCEPT'      => 'application/json',
            'HTTP_X_SIGNATURE' => $signature,
        ], $body);
    }

    public function test_webhook_requires_valid_signature(): void
    {
        $this->postJson('/api/payments/webhook', ['order_id' => 'X', 'type' => 'payment.succeeded'])->assertStatus(401);
        $this->signedWebhook(['order_id' => 'X', 'type' => 'payment.succeeded'], 'wrong-secret')->assertStatus(401);
    }

    public function test_webhook_marks_order_paid_and_awards_member_points_once(): void
    {
        $user = User::create(['username' => 'siti', 'password' => 'x', 'no_hp' => '0811222', 'role' => 'customer']);
        $member = Member::create(['id_user' => $user->id_user, 'total_poin' => 0, 'tanggal_daftar' => now()]);

        $order = $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($this->login()))->json('data');
        $code = $order['kode_transaksi'];

        // A non-success event changes nothing.
        $this->signedWebhook(['order_id' => $code, 'transaction_status' => 'pending'])->assertOk();
        $this->assertSame('menunggu', TransaksiPenjualan::first()->status_pembayaran);

        $this->signedWebhook(['order_id' => $code, 'transaction_status' => 'settlement'])
            ->assertOk()->assertJsonPath('acknowledged', true);

        $this->assertSame('terverifikasi', TransaksiPenjualan::first()->status_pembayaran);
        $points = $member->fresh()->total_poin;
        $this->assertGreaterThan(0, $points);

        // Replaying the same webhook must not award points twice.
        $this->signedWebhook(['order_id' => $code, 'transaction_status' => 'settlement'])->assertOk();
        $this->assertSame($points, $member->fresh()->total_poin);
    }

    public function test_stripe_style_webhook_payload_is_understood(): void
    {
        $order = $this->postJson('/api/waiter/orders', $this->orderPayload(), $this->auth($this->login()))->json('data');

        $this->signedWebhook([
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => ['metadata' => ['order_reference' => $order['kode_transaksi']]]],
        ])->assertOk();

        $this->assertSame('terverifikasi', TransaksiPenjualan::first()->status_pembayaran);
    }
}
