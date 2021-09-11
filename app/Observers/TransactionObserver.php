<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle "created" event.
     *
     * @param Transaction $transaction
     *
     * @return void
     */
    public function created(Transaction $transaction)
    {
        if ($transaction->from_id) {
            $transaction->from->update([
                'balance' => $transaction->from->balance - $transaction->sum
            ]);
        }

        $transaction->to->update([
            'balance' => $transaction->to->balance + $transaction->sum
        ]);
    }
}
