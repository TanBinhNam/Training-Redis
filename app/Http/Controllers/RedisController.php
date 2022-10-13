<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    //
    public function index(){
        $group_role = User::all();
        Redis::set('product:info', $group_role);

       // echo Redis::get('product:info');
  
  
            $a = json_decode(Redis::get('product:info'),false);
            $count = count($a);
       
            for($i = 0; $i< $count -2 ; )
            {
                echo $a[$i]->name ;
                echo '<hr>';
                $i = $i + 2;
            }
            
            //echo $a->id;
           // echo $a[0]['id'];
        
    }
}
