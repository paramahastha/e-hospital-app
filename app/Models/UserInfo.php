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
        'photo_id'
    ];

    public static function boot() {
        parent::boot();
        
        self::creating(function($model) {            
            $model->code = uniqid();
        });        
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
