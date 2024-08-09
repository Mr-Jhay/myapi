<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class tblscheduleResource extends JsonResource
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
            'subject_id'=>$this->subject_id,
            'title'=>$this->title,
            'quarter'=>$this->quarter,
            'subjectname'=>$this->subjectname,
            'start'=>$this->start,
            'end'=>$this->end,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}
