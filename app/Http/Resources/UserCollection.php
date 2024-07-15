<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
        'idnumber'=>$this->idnumber,
        'fname'=>$this->fname,
        'mname'=>$this->mname,
        'lname'=>$this->lname,
        'sex'=>$this->sex,
        'usertype'=>$this->usertype,
        'created_at'=>$this->created_at
        ];
    }
}
