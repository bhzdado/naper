<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Company extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'value' => $this->company_name,
            'data' => $this->fantasy_name,
        ];
    }

    public function toFormatComplete($request){
        return [
            'id' => $this->id,
            'value' => $this->company_name,
            'data' => $this->fantasy_name,
        ];
    }
}
