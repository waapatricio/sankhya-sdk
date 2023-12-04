<?php

namespace Sankhya\Http\Resources;

use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Requests\SaveRecordRequest;
use Sankhya\Http\Responses\SankhyaResponse;

class NeighborhoodResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'CODBAI',
        'NOMEBAI',
        'DESCRICAOCORREIO',
        'CODREG'
    ];

    public string $entity = 'Bairro';

    public string $primaryKey = 'CODBAI';

    public function create(array $payload, array $fields = null): Response
    {
        if (!isset($payload['NOMEBAI'])) {
            throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
        }

        return $this->connector->send(
            new SaveRecordRequest(
                resource: $this->entity,
                payload: $payload,
                fields: $fields ?: $this->defaultFields
            )
        );
    }

    public function search(string $neighborhood, array $fields = null): Response
    {
        $neighborhood = mb_convert_case($neighborhood, MB_CASE_UPPER, 'UTF-8');

        return $this->connector->send(
            new ExecuteQueryRequest("SELECT CODBAI, NOMEBAI, CODREG FROM TSIBAI
                                  WHERE (UPPER(NOMEBAI) LIKE '$neighborhood')")
        );
    }



}
