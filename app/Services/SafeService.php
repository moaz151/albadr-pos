<?php

namespace App\Services;

use App\Enums\SafeTransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafeService
{
    /**
     * @param Model $reference
     * @param float $amount
     * @param string $description
     * @return void
     */

    public function inTransaction(Model $reference, float $amount, string $description): void
    {
        $this->performTransaction($reference, $amount, SafeTransactionTypeEnum::in, $description);
    }

    public function outTransaction(Model $reference, float $amount, string $description): void
    {
        $this->performTransaction($reference, $amount, SafeTransactionTypeEnum::in, $description);
    }

    private function performTransaction(Model $reference, float $amount, SafeTransactionTypeEnum $type, string $description): void
    {
        if($amount <= 0){
            return;
        }

        DB::transaction(function () use($reference, $amount, $type, $description) {
            $safe = $reference->safe()->LockForUpdate()->FirstOrFail();

            if($type = SafeTransactionTypeEnum::in){
                $safe->balance += $amount;
            }
            else {
                $safe->balance -= $amount;
            }

            $safe->save();

             $reference->safeTransaction()->create([
                 'user_id' => Auth::id,
                 'type' => $type,
                 'safe_id' => $safe->id,
                 'amount' => $amount,
                 'balance_after' => $safe->fresh()->balance,
                 'description' => $description,
             ]);
        });
    }








    //  public function inTransaction(Model $reference, float $amount, string $description): void
    //  {
    //      // @TODO: at safe controller, should have initial balance
    //      if($amount == 0){
    //          return;
    //      }
    //      $reference->safe->decrement('balance', $amount);
    //          $reference->safeTransaction()->create([
    //              'user_id' => auth()->user()->id,
    //              'type' => SafeTransactionTypeEnum::out->value,
    //              'safe_id' => $reference->safe_id,
    //              'amount' => $amount,
    //              'balance_after' => $reference->safe->fresh()->balance,
    //              'description' => $description,
    //          ]);
    //  }


    //  public function outTransaction(Model $reference, float $amount, string $description): void
    //  {
    //      // @TODO: at safe controller, should have initial balance
    //      if($amount == 0){
    //          return;
    //      }
    //      $reference->safe->decrement('balance', $amount);
    //          $reference->safeTransaction()->create([
    //              'user_id' => auth()->user()->id,
    //              'type' => SafeTransactionTypeEnum::out->value,
    //              'safe_id' => $reference->safe_id,
    //              'amount' => $amount,
    //              'balance_after' => $reference->safe->fresh()->balance,
    //              'description' => $description,
    //          ]);
    //  }
}