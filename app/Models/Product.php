<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'brand',
        'price',
        'stock',
        'condition',
        'is_featured',
        'is_active',
        'image',
        'category_id',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Check if product is in stock
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
