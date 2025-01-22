<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
        'base',
        'species',
        'description',
        'evolution_prev',
        'evolution_next',
        'profile',
        'egg',
        'ability',
        'image',
    ];

    public $timestamps = false;

    public function setNameAttribute($value)
    {
        if (!is_string($value)) {
            $this->attributes['name'] = $value['english'];
        }
    }

    public function setTypeAttribute($value)
    {
        if (!is_string($value)) {
            $this->attributes['type'] = implode('|', $value);
        }
    }

    public function setBaseAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['base'] = implode('|', $value);
        }
    }

    public function setEvolutionPrevAttribute($value)
    {
        if (is_array($value)) {
            $formatArrayValue = array_map(fn($v) => implode('-', $v), $value);
            $formatValue = implode('|', $formatArrayValue);
            $this->attributes['evolution_prev'] = $formatValue;
        }
    }

    public function setEvolutionNextAttribute($value)
    {
        if (is_array($value)) {
            $formatArrayValue = array_map(fn($v) => implode('-', $v), $value);
            $formatValue = implode('|', $formatArrayValue);
            $this->attributes['evolution_next'] = $formatValue;
        }
    }

    public function setProfileAttribute($value)
    {
        if (is_array($value)) {
            $formatArrayValue = [$value['height'], $value['weight'], $value['gender']];
            $formatValue = implode('|', $formatArrayValue);
            $this->attributes['profile'] = $formatValue;
        }
    }

    public function setEggAttribute($value)
    {
        if (!is_string($value)) {
            $this->attributes['egg'] = implode('|', $value['egg']);
        }
    }

    public function setAbilityAttribute($value)
    {
        if (is_array($value)) {
            $formatArrayValue = array_map(fn($v) => implode('-', $v), $value);
            $formatValue = implode('|', $formatArrayValue);
            $this->attributes['ability'] = $formatValue;
        }
    }
}
