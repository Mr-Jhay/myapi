<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class answeredQuestion extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'answered_question';

    protected $fillable = [
           'tblquestion_id',
           'correctanswer_id',
           'tblstudent_id',
     
           
       ];
}
