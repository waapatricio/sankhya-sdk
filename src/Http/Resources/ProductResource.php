<?php

namespace Sankhya\Http\Resources;

use Illuminate\Support\Collection;
use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadRecordsRequest;
use Sankhya\Http\Responses\SankhyaResponse;

class ProductResource extends Resource implements ResourceContract
{
    public array $defaultFields = [
        'CODPROD',
        'DESCRPROD',
        'COMPLDESC',
        'AD_DESCRPROD_EC',
//        'AD_NAOUSA_EC',
        'CODGRUPOPROD',
        'CODCAT',
        'CODNAT',
        'CODVOL',
        'MARCA',
        'PESOBRUTO',
        'PESOLIQ',
        'ALTURA',
        'LARGURA',
        'ESPESSURA',
        'UNIDADE',
        'QTDEMB',
        'AGRUPMIN',
        'NCM',
        'REFERENCIA',
        'IMAGEM'
    ];

    public string $entity = 'Produto';

    public string $primaryKey = 'CODPROD';

    public function all(?array $fields = null): Response
    {
        $fields = implode(', ', $fields ?: $this->defaultFields);

        return $this->connector->send(
            new ExecuteQueryRequest("SELECT {$fields} FROM TGFPRO WHERE ATIVO = 'S' AND USOPROD = 'R'")
        );
    }

    public function wherein(array $skus, ?array $fields = null): Response
    {
        $batchs = array_chunk($skus, 1000);
        $fields = implode(', ', $fields ?: $this->defaultFields);

        $products = Collection::empty();

        foreach ($batchs as $batch)
        {
            $itens = implode(',', $batch);

            $response = $this->connector->send(
                new ExecuteQueryRequest("SELECT {$fields} FROM TGFPRO WHERE CODPROD IN ({$itens})")
            )->dto();

            $products = $products->merge($response->entities);
        }

        return new SankhyaResponse(
            total: $products->count(),
            hasMoreResult: false,
            offset: 0,
            entities: $products
        );
    }


}
