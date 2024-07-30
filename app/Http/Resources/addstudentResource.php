<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class addstudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id'=>$this->id,
            'subject_id'=>$this->subject_id,
            'student_id'=>$this->student_id,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}
