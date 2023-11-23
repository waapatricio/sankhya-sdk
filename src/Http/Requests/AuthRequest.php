<?php

namespace Sankhya\Http\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Sankhya\Exceptions\AuthenticationException;
use Throwable;

class AuthRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected string $appkey,
        protected string $token,
        protected string $user,
        protected string $pass
    ) {
    }

    protected function defaultHeaders(): array
    {
        return [
            'token' => $this->token,
            'appkey' => $this->appkey,
            'username' => $this->user,
            'password' => $this->pass,
        ];
    }

    public function resolveEndpoint(): string
    {
        return '/login';
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        if ($response->json('error.descricao')) {
            return new AuthenticationException($response->json('error.descricao'));
        }

        return parent::getRequestException($response, $senderException);
    }

}
