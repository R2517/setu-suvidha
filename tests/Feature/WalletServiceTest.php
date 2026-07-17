<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\FormPricing;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $walletService;
    private User $user;
    private Profile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = new WalletService();

        // Create test user with profile
        $this->user = User::factory()->create();
        $this->profile = Profile::create([
            'user_id' => $this->user->id,
            'wallet_balance' => 1000.00,
        ]);
    }

    // ─── Test 1: Wallet Debit reduces balance correctly ───────────────

    public function test_debit_reduces_wallet_balance_correctly(): void
    {
        FormPricing::create([
            'form_type' => 'test_form',
            'form_name' => 'Test Form',
            'price' => 100,
            'is_active' => true,
        ]);

        $result = $this->walletService->deduct($this->user, 'test_form', 'SUB-001');

        $this->assertEquals(100, $result['deducted']);
        $this->assertEquals(900, $result['balance_after']);

        // Verify DB state
        $this->profile->refresh();
        $this->assertEquals(900, $this->profile->wallet_balance);

        // Verify transaction log was created
        $txn = WalletTransaction::where('user_id', $this->user->id)
            ->where('type', 'debit')
            ->first();
        $this->assertNotNull($txn);
        $this->assertEquals(100, $txn->amount);
        $this->assertEquals(900, $txn->balance_after);
        $this->assertEquals('SUB-001', $txn->reference_id);
    }

    // ─── Test 2: Insufficient balance throws exception ────────────────

    public function test_debit_fails_with_insufficient_balance(): void
    {
        FormPricing::create([
            'form_type' => 'expensive_form',
            'form_name' => 'Expensive Form',
            'price' => 5000, // More than the 1000 balance
            'is_active' => true,
        ]);

        $this->expectException(\Exception::class);
        $this->walletService->deduct($this->user, 'expensive_form');

        // Verify balance unchanged
        $this->profile->refresh();
        $this->assertEquals(1000, $this->profile->wallet_balance);

        // Verify NO transaction was created
        $this->assertDatabaseCount('wallet_transactions', 0);
    }

    // ─── Test 3: Wallet Credit adds balance correctly ─────────────────

    public function test_credit_increases_wallet_balance_correctly(): void
    {
        $result = $this->walletService->credit(
            $this->user,
            500.00,
            'Razorpay recharge',
            'pay_test123'
        );

        $this->assertEquals(500, $result['credited']);
        $this->assertEquals(1500, $result['balance_after']);
        $this->assertFalse($result['already_credited']);

        // Verify DB state
        $this->profile->refresh();
        $this->assertEquals(1500, $this->profile->wallet_balance);

        // Verify transaction log
        $txn = WalletTransaction::where('reference_id', 'pay_test123')->first();
        $this->assertNotNull($txn);
        $this->assertEquals('credit', $txn->type);
        $this->assertEquals(500, $txn->amount);
    }

    // ─── Test 4: Idempotent credit prevents double-crediting ──────────

    public function test_credit_is_idempotent_for_same_reference(): void
    {
        // First credit
        $result1 = $this->walletService->credit(
            $this->user, 500.00, 'Razorpay recharge', 'pay_duplicate'
        );
        $this->assertEquals(500, $result1['credited']);
        $this->assertEquals(1500, $result1['balance_after']);
        $this->assertFalse($result1['already_credited']);

        // Second credit with same reference_id (simulates duplicate webhook)
        $result2 = $this->walletService->credit(
            $this->user, 500.00, 'Razorpay recharge', 'pay_duplicate'
        );
        $this->assertEquals(0, $result2['credited']);
        $this->assertTrue($result2['already_credited']);

        // Balance should be 1500, NOT 2000
        $this->profile->refresh();
        $this->assertEquals(1500, $this->profile->wallet_balance);

        // Only ONE transaction should exist
        $txnCount = WalletTransaction::where('reference_id', 'pay_duplicate')->count();
        $this->assertEquals(1, $txnCount);
    }

    // ─── Test 5: Debit without active pricing returns zero ────────────

    public function test_debit_with_no_pricing_returns_zero(): void
    {
        $result = $this->walletService->deduct($this->user, 'nonexistent_form');

        $this->assertEquals(0, $result['deducted']);
        $this->assertEquals(1000, $result['balance_after']);

        // Balance unchanged
        $this->profile->refresh();
        $this->assertEquals(1000, $this->profile->wallet_balance);
    }

    // ─── Test 6: Debit creates transaction with correct balance_after ─

    public function test_sequential_debits_track_running_balance(): void
    {
        FormPricing::create([
            'form_type' => 'small_form',
            'form_name' => 'Small Form',
            'price' => 100,
            'is_active' => true,
        ]);

        // First debit: 1000 - 100 = 900
        $r1 = $this->walletService->deduct($this->user, 'small_form', 'TXN-1');
        $this->assertEquals(900, $r1['balance_after']);

        // Second debit: 900 - 100 = 800
        $r2 = $this->walletService->deduct($this->user, 'small_form', 'TXN-2');
        $this->assertEquals(800, $r2['balance_after']);

        // Third debit: 800 - 100 = 700
        $r3 = $this->walletService->deduct($this->user, 'small_form', 'TXN-3');
        $this->assertEquals(700, $r3['balance_after']);

        // Verify final DB state
        $this->profile->refresh();
        $this->assertEquals(700, $this->profile->wallet_balance);

        // Verify all 3 transactions exist with correct running balances
        $txns = WalletTransaction::where('user_id', $this->user->id)
            ->orderBy('id')
            ->get();
        $this->assertCount(3, $txns);
        $this->assertEquals(900, $txns[0]->balance_after);
        $this->assertEquals(800, $txns[1]->balance_after);
        $this->assertEquals(700, $txns[2]->balance_after);
    }

    // ─── Test 7: Credit with null reference allows multiple credits ───

    public function test_credit_without_reference_allows_duplicates(): void
    {
        $r1 = $this->walletService->credit($this->user, 100.00, 'Manual credit', null);
        $r2 = $this->walletService->credit($this->user, 100.00, 'Manual credit', null);

        $this->assertEquals(1200, $r2['balance_after']);
        $this->assertFalse($r1['already_credited']);
        $this->assertFalse($r2['already_credited']);
    }
}
