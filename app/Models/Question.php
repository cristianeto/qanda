<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = ['id', 'description', 'answer', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAllByUser($user)
    {
        return Question::select('id','description', 'status')->where('user_id', $user->id)->get();
    }

    public static function getAnsweredByUser($user)
    {
        return Question::where('status','!=','NOT ANSWERED')->where('user_id', $user->id)->get();
    }

    public static function getCorrectByUser($user)
    {
        return Question::where('status','CORRECT')->where('user_id', $user->id)->get();
    }
}
