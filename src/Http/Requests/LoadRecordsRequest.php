<?php

namespace Sankhya\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Sankhya\Http\Responses\SankhyaResponse;

class LoadRecordsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $resource,
//        public array $include = [],
        public int $page = 0,
        public int $limit = 20,
        public bool $showRelationship = false,
        public array $fields = [],
        public ?string $where = null
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/gateway/v1/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords&outputType=json';
    }

    protected function getResource(): string
    {
        return $this->resource;
    }

    protected function getFields(): string
    {
        return implode(',', $this->fields);
    }

    protected function getWhere(): string
    {
        return $this->where ?: '';
    }

    protected function defaultBody(): array
    {
        return [
            'serviceName' => 'CRUDServiceProvider.loadRecords',
            'requestBody' => [
                'dataSet' => [
                    'rootEntity' => $this->getResource(),
                    'includePresentationFields' => $this->showRelationship ? 'S' : 'N',
                    'offsetPage' => $this->page,
                    'criteria' => [
                        'expression' => [
                            '$' => $this->getWhere()
                        ]
                    ],
                    'entity' => [
                        'fieldset' => [
                            'list' => $this->getFields()
                        ]
                    ]
                ]
            ]
        ];
    }

    public function hasRequestFailed(Response $response): ?bool
    {
        return $response->json('status') != 1;
    }

    public function createDtoFromResponse(Response $response): Response
    {
        return SankhyaResponse::fromResponse($response);
    }


}
