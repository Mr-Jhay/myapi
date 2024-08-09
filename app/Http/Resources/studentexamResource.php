<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class studentexamResource extends JsonResource
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
            'tblstudent_id'=>$this->tblstudent_id,
            'tblschedule_id'=>$this->tblschedule_id,
            'created_at'=>$this->created_at,
            'updated_at' => $this->updated_at,
            ];
    }
}

