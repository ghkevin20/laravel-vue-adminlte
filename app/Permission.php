<?php

namespace App;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use LogsActivity;

    /**
     * Log changes to all attributes
     *
     * @var bool
     */
    protected static $logFillable = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-M-d h:i A',
        'updated_at' => 'datetime:Y-M-d h:i A',
    ];

    public function getRolesListAttribute()
    {
        return $this->roles()->pluck('name')->implode(',');
    }
}
