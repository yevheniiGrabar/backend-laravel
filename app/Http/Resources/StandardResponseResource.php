<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StandardResponseResource extends JsonResource
{

    /** @var string */
    protected $message;

    /** @var mixed|null */
    protected $data;

    public function __construct($message, $data = null)
    {
        parent::__construct($message);
        $this->message = $message;
        $this->data = $data;
    }

    public function toArray($request)
    {
        return $this->removeMissingValues([
            'message' => $this->message,
            'data' => $this->when(!empty($this->data), $this->data)
        ]);
    }
}
