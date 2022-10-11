<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultRuleSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consult_rule_id',
        'day',
        'start_time',
        'end_time',
        'active'
    ];

    public function consultRule()
    {
        return $this->belongsTo(ConsultRule::class);
    } 
}
