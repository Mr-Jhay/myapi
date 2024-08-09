<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class correctanswerResource extends JsonResource
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
            'addchoices_id'=>$this->addchoices_id,
            'correct_answer'=>$this->correct_answer,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}
