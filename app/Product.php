<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 *
 * @package App
 */
class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'price', 'units', 'description', 'image'
    ];


    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
