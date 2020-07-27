<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-M-d h:i A',
        'updated_at' => 'datetime:Y-M-d h:i A',
    ];
}
