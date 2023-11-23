<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\DataObjects\Record;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Requests\SaveRecordRequest;

class CustomerResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'CODPARC',
        'NOMEPARC',
        'EMAIL',
        'TELEFONE',
        'CGC_CPF',
        'CEP',
        'CODCID',
        'CODBAI',
        'CODEND',
        'NUMEND',
        'COMPLEMENTO',
        'CODREG'
    ];

    public string $entity = 'Parceiro';

    public string $primaryKey = 'CODPARC';

    public function create(array $payload, array $fields = null): Response
    {
        foreach (['CGC_CPF', 'TIPPESSOA', 'NOMEPARC', 'CODCID', 'CLASSIFICMS'] as $key) {
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

    public function update(int $id, array $payload, array $fields = null): Response
    {
        return $this->connector->send(
            new SaveRecordRequest(
                resource: $this->entity,
                payload: $payload,
                key: [$this->primaryKey => $id],
                fields: $fields ?: $this->defaultFields
            )
        );

    }

}
