<?php

namespace Sankhya\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Sankhya\DataObjects\Record;
use Sankhya\Http\Responses\SankhyaResponse;

class SaveRecordRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $resource,
        protected array $payload,
        protected ?array $key = null,
        protected array $fields = [],
        protected bool $showRelationship = false
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/gateway/v1/mge/service.sbr?serviceName=CRUDServiceProvider.saveRecord&outputType=json';
    }

    protected function getResource(): string
    {
        return $this->resource;
    }

    protected function getFields(): string
    {
        return implode(',', $this->fields);
    }

    protected function getPayload(): array
    {
        return array_map(fn ($value)  => ['$' => $value], $this->payload);
    }

    protected function getKey(): array
    {
        return array_map(fn ($value)  => ['$' => $value], $this->key);
    }

    protected function defaultBody(): array
    {
        $body = [
            'serviceName' => 'CRUDServiceProvider.saveRecord',
            'requestBody' => [
                'dataSet' => [
                    'rootEntity' => $this->getResource(),
                    'includePresentationFields' => $this->showRelationship ? 'S' : 'N',
                    'dataRow' => [
                        'localFields' => $this->getPayload()
                    ],
                    'entity' => [
                        'fieldset' => [
                            'list' => $this->getFields()
                        ]
                    ]
                ]
            ]
        ];

        if ($this->key)
            $body['requestBody']['dataSet']['dataRow']['key'] = $this->getKey();

        return $body;
    }

    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->json('status') != 1;
    }

    public function createDtoFromResponse(Response $response): Response
    {
        return SankhyaResponse::fromSaveResponse($response);
    }

}
