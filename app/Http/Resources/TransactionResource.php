<?php

namespace App\Http\Resources;

use App\Http\Resources\User\AuthUserResource;
use App\Http\Resources\User\TransactionUserResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TransactionResource
 * @package App\Http\Resources
 *
 * @mixin Transaction
 */
class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sum' => $this->sum,
            'from' => $this->from_id
                ? new TransactionUserResource($this->from)
                : [
                    'name' => 'system'
                ],
            'to' => new TransactionUserResource($this->to),
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
