<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class tblquestion extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
        protected $table = 'tblquestion';
    
        protected $fillable = [
               'tblschedule_id',
               'question_type',
               'question',
               
           ];
}
