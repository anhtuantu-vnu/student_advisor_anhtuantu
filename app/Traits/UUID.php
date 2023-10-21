<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * @return bool
     */
    public function getIncrementing (): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getKeyType ()
    {
        return 'string';
    }
}
