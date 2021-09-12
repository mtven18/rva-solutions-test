<?php

namespace App\Models;

use App\Models\Concerns\UsesUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Transaction
 *
 * @property string $id
 * @property int|null $from_id
 * @property int $to_id
 * @property string $sum
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $from
 * @property-read \App\Models\User $to
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;
    use UsesUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'from_id',
        'to_id',
        'sum',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sum' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * @return BelongsTo
     */
    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
