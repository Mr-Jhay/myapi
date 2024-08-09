<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class tblquestionResource extends JsonResource
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
            'tblschedule_id'=>$this->tblschedule_id,
            'question_type'=>$this->question_type,
            'question'=>$this->question,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}
