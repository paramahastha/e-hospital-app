<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'photo',
        'gender',
        'address',        
        'phone_number',
        'identity_number',
        'sip_number',
        'date_of_birth',
        'photo_id',
        'position',
        'description'
    ];

    public static function boot() {
        parent::boot();
        
        self::creating(function($model) {            
            if (!empty($model->user->roles)) {
                $role = $model->user->roles[0];
                $code = strtoupper(substr($role->slug, 0, 3)) . 
                    $model->user->id . date("dmy") . rand(0,99);
                $model->code = $code;               
            }                        
        });        
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
