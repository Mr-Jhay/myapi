<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class addchoices extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'addchoices';

    protected $fillable = [
           'tblquestion_id',
           'choices',   
       ];

       

}
