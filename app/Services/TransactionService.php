<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use \Exception;

class TransactionService
{
    const LIST_PAGE_COUNT = 20;

    /**
     * List of user transactions.
     *
     * @param User $user
     *
     * @return LengthAwarePaginator
     */
    public function list(User $user): LengthAwarePaginator
    {
        return Transaction::query()->where(function (Builder $query) use ($user) {
            $query->where('from_id', $user->id)
                ->orWhere('to_id', $user->id);
        })->latest()->paginate(static::LIST_PAGE_COUNT);
    }

    /**
     * Make transaction.
     *
     * @param string    $username
     * @param float     $sum
     * @param User|null $from
     *
     * @return Transaction|false
     */
    public function send(string $username, float $sum, User $from = null)
    {
        if ($from->balance - $sum < 0) {
            return false;
        }

        $to = User::whereUsername($username)->firstOrFail();

        DB::beginTransaction();
        try {
            $transaction = Transaction::query()->create([
                'from_id' => $from->id ?? null,
                'to_id' => $to->id,
                'sum' => $sum,
            ]);

            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage(), $e->getTrace());
            return false;
        }
    }
}
