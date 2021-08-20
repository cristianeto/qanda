<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    /**
     * @param $email
     * @return user
     */
    public static function createByEmail($email){
        $user = User::where('email', $email)->first();
        if(!isset($user)) {
            $separatedEmail = explode("@", $email);
            $user = User::create([
                'name' => ucfirst($separatedEmail[0]),
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
        }
        return $user;
    }

    /**
     * @param $email
     * @return mixed
     */
    public static function getByEmail($email)
    {
        return User::where('email', $email)->firstOrFail();
    }
}
