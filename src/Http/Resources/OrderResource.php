<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\LoadRecordsRequest;

class OrderResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'NUNOTA',
        'CODEMP',
        'NUMNOTA',
        'SERIENOTA',
        'DTNEG',
        'DTFATUR',
        'DTENTSAI',
        'DTMOV',
        'CODPARC',
        'CODTIPOPER',
        'DANFE',
        'CHAVENFE',
        'STATUSNFE',
    ];

    public string $entity = 'CabecalhoNota';

    public string $primaryKey = 'NUNOTA';

//    public function create(array $payload): Record
//    {
//        foreach (['NUNOTA', 'CODPARC', 'DTNEG', 'CODTIPOPER', 'CODTIPVENDA', 'CODVEND', 'CODEMP', 'TIPMOV'] as $key) {
//            if (!isset($payload['header'][$key])) {
//                throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
//            }
//        }
//
//        $response = $this->connector->send(
//            new SaveOrderRequest(payload: $payload)
//        )->object();
//
//        if ($response->status != 1)
//            return throw new \Exception($response->statusMessage);
//
//        return new Record([ 'nunota' => $response->responseBody->pk->NUNOTA->{'$'} ]);
//
//    }


}
