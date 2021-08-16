<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = ['id', 'description', 'answer', 'status', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
