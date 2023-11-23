<?php

namespace Sankhya\Http\Auth;

use Sankhya\Http\Requests\AuthRequest;
use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

class SankhyaAuthenticator implements Authenticator
{
    public function __construct(
        public string $appkey,
        public string $token,
        public string $user,
        public string $pass
    ) {
    }

    /**
     * Apply the authentication to the request.
     */
    public function set(PendingRequest $pendingRequest): void
    {
        if ($pendingRequest->getRequest() instanceof AuthRequest) {
            return;
        }

        $response = $pendingRequest->getConnector()->send(
            new AuthRequest($this->appkey, $this->token, $this->user, $this->pass)
        );

        $pendingRequest->headers()->add('Authorization', 'Bearer ' . $response->json('bearerToken'));
    }
}
