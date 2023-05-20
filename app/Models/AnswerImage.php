<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerImage extends Model
{
    use HasFactory;

    public function answer() {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
