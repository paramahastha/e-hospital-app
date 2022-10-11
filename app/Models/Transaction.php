<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'consultation_id',
        'code',
        'description',
        'prescription',
        'proof_of_payment',
        'payment_status',
        'payment_reject_reason',
        'total_price',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class, 'id', 'consultation_id')->with('consultUsers');
    }
}
