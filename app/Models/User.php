<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table ='mst_users';

    protected $fillable = [     
        'name',
        'email',
        'password',
        'remember_token',
        'verify_email',
        'is_active',
        'is_delete',
        'group_role',
        'last_login_at',
        'last_login_ip',
    ];

    public function scopeSearchUser($query, $filters)
    {
        return $query
        ->when(isset($filters['name']), function ($q) use ($filters){
                return $q->where('name', 'like', '%'.$filters['name'].'%');        
        })
        ->when(isset($filters['email']), function ($q) use ($filters){
            return $q->where('email', 'like', '%'.$filters['email'].'%'); 
        })
        ->when(isset($filters['group_role']), function ($q) use ($filters){
            return $q->where('group_role', $filters['group_role']);       
        })
        ->when(isset($filters['status']), function ($q) use ($filters){
            return $q->where('is_active', $filters['status']);       
        });
    }
  
}
