<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'name' , 'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-M-d h:i A',
        'created_at' => 'datetime:Y-M-d h:i A',
        'updated_at' => 'datetime:Y-M-d h:i A',
        'deleted_at' => 'datetime:Y-M-d h:i A',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
