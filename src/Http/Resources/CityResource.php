<?php

namespace Sankhya\Http\Resources;

use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Requests\SaveRecordRequest;
use Sankhya\Http\Responses\SankhyaResponse;

class CityResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'CODCID',
        'NOMECID',
        'UF',
        'CODREG'
    ];

    public string $entity = 'Cidade';

    public string $primaryKey = 'CODCID';

    public function create(array $payload, array $fields = null): Response
    {
        foreach (['NOMECID', 'UF'] as $key) {
            if (!isset($payload[$key])) {
                throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
            }
        }

        return $this->connector->send(
            new SaveRecordRequest(
                resource: $this->entity,
                payload: $payload,
                fields: $fields ?: $this->defaultFields,
                showRelationship: true
            )
        );
    }

    public function search(string $city, string $uf): Response
    {
        $city = mb_convert_case($city, MB_CASE_UPPER, 'UTF-8');
        $uf = mb_convert_case($uf, MB_CASE_UPPER, 'UTF-8');

        return $this->connector->send(
            new ExecuteQueryRequest("SELECT TSICID.CODCID, TSICID.NOMECID, TSIUFS.CODUF, TSIUFS.UF
                                         FROM TSICID INNER JOIN TSIUFS
                                            ON (TSICID.UF = TSIUFS.CODUF)
                                         WHERE ((UPPER(NOMECID) LIKE '$city') AND (UPPER(TSIUFS.UF) LIKE '$uf'))")
        );

    }



}
