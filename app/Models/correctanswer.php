<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class correctanswer extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'correctanswer';

    protected $fillable = [
           'tblquestion_id',
           'addchoices_id',
           'correct_answer',
     
           
       ];
}

