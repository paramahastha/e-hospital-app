<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',        
        'duration',
        'price',
        'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

    public function schedules()
    {
        return $this->hasMany(ConsultRuleSchedule::class);
    } 
}
