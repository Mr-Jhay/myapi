<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class answeredQuestionResource extends JsonResource
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
            'tblquestion_id'=>$this->tblquestion_id,
            'correctanswer_id'=>$this->correctanswer_id,
            'tblstudent_id'=>$this->tblstudent_id,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}

