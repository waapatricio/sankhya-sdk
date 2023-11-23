<?php

namespace Sankhya\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Sankhya\Http\Responses\SankhyaResponse;

class ExecuteQueryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $sql,
    ){
    }

    public function resolveEndpoint(): string
    {
        return '/gateway/v1/mge/service.sbr?serviceName=DbExplorerSP.executeQuery&outputType=json';
    }

    protected function defaultBody():array
    {
        return [
            'serviceName' => 'DbExplorerSP.executeQuery',
            'requestBody' => [
                'sql' => $this->sql
            ]
        ];
    }

    public function hasRequestFailed(Response $response): bool
    {
        return $response->json('status') != 1;
    }

    public function createDtoFromResponse(Response $response): SankhyaResponse
    {
        return SankhyaResponse::fromQueryResponse($response);
    }

}
