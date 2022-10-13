<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table ='mst_customers';

    protected $primaryKey = 'customer_id';

    protected $fillable = [     
        'customer_name',
        'email',
        'tel_num',
        'address',
        'is_active',
    ];

    public function scopeSearchCustomer($query, $filters)
    {
        return $query
        ->when(isset($filters['name']), function ($q) use ($filters){
                return $q->where('customer_name', 'like', '%'.$filters['name'].'%');        
        })
        ->when(isset($filters['email']), function ($q) use ($filters){
            return $q->where('email', 'like', '%'.$filters['email'].'%'); 
        })
        ->when(isset($filters['address']), function ($q) use ($filters){
            return $q->where('address', 'like', '%'.$filters['address'].'%');       
        })
        ->when(isset($filters['status']), function ($q) use ($filters){
            return $q->where('is_active', $filters['status']);       
        });
    }
}
