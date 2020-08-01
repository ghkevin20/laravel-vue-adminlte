<?php

namespace App;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
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

    /**
     * Get properties
     *
     * @param string $value
     * @return string
     */
    public function getPropertiesAttribute($value)
    {
        return json_decode($value,true);
    }

}
