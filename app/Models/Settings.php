<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['key', 'value', 'value_type', 'edit'];

    protected $casts = [
        'edit' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        return match ($this->value_type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'float' => (float) $value,
            'array' => json_decode($value, true),
            'null' => null,
            default => $value
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value_type'] = match (true) {
            is_int($value) => 'integer',
            is_bool($value) => 'boolean',
            is_float($value) => 'float',
            is_array($value) => 'array',
            is_null($value) => 'null',
            default => 'string'
        };

        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }
}
