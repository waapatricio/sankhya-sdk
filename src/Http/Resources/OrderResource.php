<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Requests\SaveOrderRequest;

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

    public function create(array $payload): Response
    {
        foreach (['NUNOTA', 'CODPARC', 'DTNEG', 'CODTIPOPER', 'CODTIPVENDA', 'CODVEND', 'CODEMP', 'TIPMOV'] as $key) {
            if (!isset($payload['header'][$key])) {
                throw new \InvalidArgumentException("Missing required keys: {$key} cannot be empty");
            }
        }

        return $this->connector->send(
            new SaveOrderRequest(payload: $payload)
        );
    }


}
