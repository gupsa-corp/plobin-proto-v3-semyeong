<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected int $status;
    protected ?array $errors;
    protected array $data;

    public function __construct(
        string $message, 
        int $status = 400, 
        ?array $errors = null, 
        array $data = [], 
        ?Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->status = $this->validateStatusCode($status);
        $this->errors = $errors;
        $this->data = $data;
    }

    private function validateStatusCode(int $status): int
    {
        return ($status >= 100 && $status < 600) ? $status : 400;
    }

    public function render(): JsonResponse
    {
        $response = $this->buildResponse();
        return response()->json($response, $this->status);
    }

    private function buildResponse(): array
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage()
        ];

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        if (!empty($this->data)) {
            $response['data'] = $this->data;
        }

        return $response;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getData(): array
    {
        return $this->data;
    }

    // 정적 생성자 메서드들
    public static function validation(string $message, ?array $errors = null): self
    {
        return new self($message, 422, $errors);
    }

    public static function notFound(string $message = '리소스를 찾을 수 없습니다.'): self
    {
        return new self($message, 404);
    }

    public static function unauthorized(string $message = '인증이 필요합니다.'): self
    {
        return new self($message, 401);
    }

    public static function forbidden(string $message = '권한이 없습니다.'): self
    {
        return new self($message, 403);
    }

    public static function serverError(string $message = '서버 오류가 발생했습니다.'): self
    {
        return new self($message, 500);
    }

    public static function badRequest(string $message, array $data = []): self
    {
        return new self($message, 400, null, $data);
    }

    public static function tooManyRequests(string $message = '요청 횟수를 초과했습니다.'): self
    {
        return new self($message, 429);
    }

    public static function conflict(string $message = '리소스 충돌이 발생했습니다.'): self
    {
        return new self($message, 409);
    }
}