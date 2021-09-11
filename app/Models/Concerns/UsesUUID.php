<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait UsesUUID
{
    /**
     * Boot method.
     *
     * @return void
     */
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Disable autoincrement.
     *
     * @return false
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Primary key type.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
