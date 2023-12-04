<?php

namespace Sankhya\Http\Resources;

use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Requests\SaveRecordRequest;
use Sankhya\Http\Responses\SankhyaResponse;

class StateResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'CODUF',
        'UF',
        'DESCRICAO',
    ];

    public string $entity = 'UnidadeFederativa';

    public string $primaryKey = 'CODUF';

    public function create(array $payload, array $fields = null): Response
    {
        foreach (['UF', 'DESCRICAO'] as $key) {
            if (!isset($payload[$key])) {
                throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
            }
        }

        return $this->connector->send(
            new SaveRecordRequest(
                resource: $this->entity,
                payload: $payload,
                fields: $fields ?: $this->defaultFields
            )
        );
    }



}
