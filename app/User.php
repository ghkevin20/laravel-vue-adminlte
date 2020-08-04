<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, SoftDeletes, Notifiable, LogsActivity;

    /**
     * Log changes to all attributes
     *
     * @var bool
     */
    protected static $logFillable = true;

    /**
     * Ignore to log attributes
     *
     * @var string[]
     */
    protected static $logAttributesToIgnore = ['password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * Ignore changes to attributes
     *
     * @var string[]
     */
    protected static $ignoreChangedAttributes = ['remember_token', 'created_at', 'updated_at'];

    /**
     * Set Guard Name
     *
     * @var string
     */
    protected $guard_name = 'sanctum';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar', 'name', 'email', 'gender', 'password', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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

    /**
     * Get the user's temporary avatar.
     *
     * @param string $value
     * @return string
     */
    public function getDefaultAvatarAttribute()
    {
        return 'avatar-' . strtolower($this->gender) . '.png';
    }

    /**
     * Get the user's avatar.
     *
     * @param string $value
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?
            asset('storage/avatars/'. $this->avatar)
            :asset('storage/default/'. $this->default_avatar);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getRolesListAttribute()
    {
        return $this->roles()->pluck('name')->implode(',');
    }

}
