<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultFormAnswer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function form(){
        return  $this->belongsTo(DynamicForm::class,'dynamic_form_id');
    }
    public function user(){
        return  $this->belongsTo(DefaultUser::class,'default_user_id');
    }
}

