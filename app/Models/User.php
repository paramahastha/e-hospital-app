<?php

namespace App\Models;

use Orchid\Platform\Models\User as OrchidUserModel;

class User extends OrchidUserModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'email',        
        'password',
        'permissions',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'code',
        'email',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];

    /**
     * get userInfo associated with the user.
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }

    /**
     * get activities associated with the user.
     */
    public function activities()
    {
        return $this->hasMany(UserActivities::class);
    }

    /**
     * get consultRule associated with the user.
     */
    public function consultRule()
    {
        return $this->hasOne(ConsultRule::class)->with('schedules');
    }

    /**
     * get transactions associated with the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * get consultations associated with the user.
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'consultation_users', 'user_id', 'consultation_id');
    } 

    //Add the below function
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
