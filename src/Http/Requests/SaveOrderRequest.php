<?php

namespace Sankhya\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Sankhya\Http\Responses\SankhyaResponse;

class SaveOrderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/gateway/v1/mgecom/service.sbr?serviceName=CACSP.incluirNota&outputType=json';
    }

    protected function getPayloadHeader(): array
    {
        return array_map(fn ($value)  => ['$' => $value], $this->payload['header']);
    }

    protected function getPayloadItens(): array
    {
        return array_map(function ($item) {
            return array_map(fn ($value)  => ['$' => $value], $item);
        }, $this->payload['itens']);
    }

    protected function defaultBody(): array
    {
        return [
            'serviceName' => 'CACSP.incluirNota',
            'requestBody' => [
                'nota' => [
                    'cabecalho' => $this->getPayloadHeader(),
                    'itens' => [
                        'item' => $this->getPayloadItens()
                    ]
                ]
            ]
        ];
    }

    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->json('status') != 1 && $response->json('status') != 2;
    }

    public function createDtoFromResponse(Response $response): Response
    {
        return SankhyaResponse::fromSaveOrderResponse($response);
    }

}
