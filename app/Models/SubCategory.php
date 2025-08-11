<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $fillable = [
        'name',
        'slug',
        'category_id'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'sub_category_id', 'id');
    }
    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('slug', 'like', "%{$value}%");
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
