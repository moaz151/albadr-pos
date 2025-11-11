<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientAccountTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientAccountService
{

	/**
	 * @param Model $reference
	 * @param float $net_amount
	 * @param float $paid_amount
	 * @return void
	 */
	public function handleClientBalance(Model $reference, Float $net_amount, Float $paid_amount): void
	{
		$balanceDelta = $net_amount - $paid_amount;
		if($balanceDelta != 0){
			$reference->client->increment('balance', $balanceDelta);
		}

		$reference->clientAccountTransaction()->create([
			'user_id' => auth()->id(),
			'client_id' => $reference->client_id,
			'credit' => $net_amount,
			'debit' => $paid_amount,
			'balance' => $balanceDelta,
			'balance_after' => $reference->client->fresh()->balance,
			'description' => __('trans.sale_remaining, Invoice Number: ' . $reference->invoice_number),
		]);
	}

	/**
	 * Record a manual client payment and update client's balance and ledger.
	 *
	 * @param Client $client
	 * @param float $amount
	 * @param string|null $description
	 * @return void
	 */
	public function ClientPayment(Client $client, float $amount, string $description = null): void
	{
		DB::transaction(function () use ($client, $amount, $description) {
			$previousBalance = (float) $client->balance;
			$balanceAfter = $previousBalance - $amount;

			$client->balance = $balanceAfter;
			$client->save();

			ClientAccountTransaction::create([
				'client_id' => $client->id,
				'user_id' => auth()->id(),
				'description' => $description ?? 'Client payment',
				'credit' => $previousBalance,
				'debit' => $amount,
				'balance' => $previousBalance,
				'balance_after' => $balanceAfter,
				'reference_id' => null,
				'reference_type' => null,
			]);
		});
	}
}