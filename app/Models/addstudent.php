<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class addstudent extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;
    protected $table = 'addstudent';
    protected $fillable = [
        'subject_id',
        'student_id',

        
    ];
}
