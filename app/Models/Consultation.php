<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'session_start',
        'session_end',
        'status'
    ];

    /**
     * get users associated with the consultation.
     */
    public function consultUsers()
    {
        return $this->hasMany('App\Models\ConsultationUser', 'consultation_id', 'id')->with('user');
    }
}
