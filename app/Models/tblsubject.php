<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class tblsubject extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;
    protected $table = 'tblsubject';

     protected $fillable = [
           'teacher_id',
           'subjectname',
           'yearlevel',
           'strand',
           'semester',
           'gen_code',
           'up_img',
           
       ];
}

