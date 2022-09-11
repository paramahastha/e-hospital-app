<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationUser extends Model
{
    use HasFactory;


    protected $table = 'consultation_users';

    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'consultation_id'        
    ];

     /**
     * get users associated with the consultation.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    } 

     /**
     * get consultation associated with the users.
     */
    public function consult()
    {
        return $this->belongsTo('App\Models\Consultation', 'consultation_id', 'id');
    } 

}
