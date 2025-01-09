<?php

namespace App\Http\Responses;

class ApiResponse
{
    public string $server_time;

    public bool $success;

    public ?string $message;

    public $data;

    public function __construct(string $serverTime, bool $success, ?string $message = null, $data = null)
    {
        $this->server_time = $serverTime;
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
}
