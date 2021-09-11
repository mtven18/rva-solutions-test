<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ForbiddenException extends Exception
{
    /**
     * ClientForbiddenException constructor.
     *
     * @param $message
     *
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report(): ?bool
    {
        return false;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json($this->message, JsonResponse::HTTP_FORBIDDEN);
    }
}
