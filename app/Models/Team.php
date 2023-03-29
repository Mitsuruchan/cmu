<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    // Userテーブル側とのリレーション（従テーブル側）
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
     //userテーブルとのリレーション(多対多リレーション：参加者取得)
    public function members(){
        return $this->belongsToMany('App\Models\User')->withPivot('role');
    }
    
    
    
}





