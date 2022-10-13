<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table ='mst_products';

    protected $keyType = 'string';
    
    protected $primaryKey = 'product_id';

    protected $fillable = [     
        'product_name',
        'product_image',
        'product_price',
        'is_sales',
        'description',
    ];

    public function scopeSearchProduct($query, $filters)
    {
        return $query
        ->when(isset($filters['product_name']), function ($q) use ($filters){
                return $q->where('product_name', 'like', '%'.$filters['product_name'].'%');        
        })
        ->when(isset($filters['status']), function ($q) use ($filters){
            return $q->where('is_sales', $filters['status']); 
        })
        ->when(isset($filters['price_from']), function ($q) use ($filters){
            return $q->where('product_price', '>=', $filters['price_from']);       
        })
        ->when(isset($filters['price_to']), function ($q) use ($filters){
            return $q->where('product_price', '<=', $filters['price_to']);       
        });
    }
}
