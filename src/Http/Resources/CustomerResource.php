<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\LoadRecordsRequest;

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

//    public function create(array $payload, array $fields = null): SankhyaResponse
//    {
//        foreach (['CGC_CPF', 'TIPPESSOA', 'NOMEPARC', 'CODCID'] as $key) {
//            if (!isset($payload[$key])) {
//                throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
//            }
//        }
//
//        return $this->connector->send(
//            new SaveRecordRequest(
//                entity: $this->entity,
//                fields: $fields ?: $this->fields,
//                payload: $payload
//            )
//        )->dto();
//    }

}
