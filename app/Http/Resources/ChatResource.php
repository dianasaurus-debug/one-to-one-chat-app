<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'message' => $this->messages['content'],
          'id' => $this->id,
            'type' => $this->type,
            'read_at' => $this->read_at_timing($this),
            'send_at' => $this->created_at->format('H:i')
        ];
    }
    public function read_at_timing($_this){
        $read_at = $_this->type == 0 ? $_this->read_at : null;
        return $read_at ? Carbon::parse($read_at)->format('H:i') : null;
    }
}
