<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class studentexam extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'studentexam';

    protected $fillable = [
           'tblstudent_id',
           'tblschedule_id',
     
           
       ];
}
