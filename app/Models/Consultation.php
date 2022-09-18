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

    /**
     * get doctor of consultation.
     */
    public function user($targetRole)
    {
        $users = $this->consultUsers;
        foreach ($users as $user) {
            $role = $user->user->roles->first();
            
            if ($role->slug == $targetRole) {
                return $user->user;
            }        
        }
        
        return null;
    }
}
