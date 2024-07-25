<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class tblteacherResource extends JsonResource
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
        'teacher_id'=>$this->teacher_id,
        'teacher_Position'=>$this->teacher_Position,
        'created_at'=>$this->created_at,
        'updated_at' => $this->updated_at,
        ];
    }
}
