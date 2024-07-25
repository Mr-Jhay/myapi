<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class tblsubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'subjectname'=>$this->subjectname,
            'yearlevel'=>$this->yearlevel,

            'strand'=>$this->strand,
            'semester'=>$this->semester,
            'gen_code'=>$this->gen_code,
            'up_img'=>$this->up_img,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}
