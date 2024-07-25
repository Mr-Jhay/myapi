<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class tblteacher extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'tblteacher';

    protected $fillable = [
           'user_id',
           'teacher_Position',
           
       ];
   

}
