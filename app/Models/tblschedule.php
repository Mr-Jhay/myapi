<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class tblschedule extends Model
{
    
        use HasFactory, Notifiable, HasApiTokens;
        protected $table = 'tblschedule';
    
        protected $fillable = [
               'subject_id',
               'title',
            
               'quarter',
               'subjectname',
               'start',
            
               'end',
               
           ];
    
}
