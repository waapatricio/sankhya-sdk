<?php

namespace Sankhya\Http\Responses;


use Illuminate\Support\Collection;
use Saloon\Contracts\DataObjects\WithResponse;

use Saloon\Http\Response;
use Saloon\Traits\Responses\HasResponse;
use Sankhya\DataObjects\Record;


class SankhyaResponse extends Response implements WithResponse
{
    use HasResponse;

    public function __construct(
        readonly public int $total,
        readonly public bool $hasMoreResult,
        readonly public int $offset,
        readonly public array|Collection $entities
    )
    {
    }

    public static function fromResponse(Response $response): SankhyaResponse
    {
        $data = $response->json('responseBody.entities');
        $entities = Collection::empty();
        $total = intval($data['total']);

        if ($total === 1) {
            $entities->add($data['entity']);
        }

        if ($total > 1) {
            $entities = Collection::make($data['entity']);
        }

        if ($entities->count() >= 1)
        {
            $fields = $data['metadata']['fields']['field'];
            $fields =  (count($fields) > 1)
                ? array_column($fields, 'name')
                : array_values($fields);

            $entities = $entities->map(function($item) use ($fields) {
                unset($item['_rmd']);

                $newItem = array();

                foreach ($item as $key => $value) {
                    $newItem[$fields[substr($key,1)]] = current($value) ?: null;
                }

                return new Record($newItem);
            });
        }

        return new static(
            total: $total,
            hasMoreResult: $data['hasMoreResult'] === 'true',
            offset: intval($data['offset']),
            entities: $entities
        );
    }


    public static function fromViewResponse(Response $response): SankhyaResponse
    {
        $xml = $response->xml();
        $total = $xml->responseBody->records->children()->count();

        if ($total >= 1)
        {
            $entities = Collection::empty();

            foreach ($xml->responseBody->records->record as $record) {
                $entities->add(new Record($record));
            }

            return new static(
                total: $total,
                hasMoreResult: false,
                offset: 0,
                entities: $entities
            );
        }

        return new static(
            total: 0,
            hasMoreResult: false,
            offset: 0,
            entities: Collection::empty()
        );
    }

    public static function fromQueryResponse(Response $response): SankhyaResponse
    {
        $responseBody = $response->json('responseBody');
        $total = count($responseBody['rows']);

        if ($total >= 1)
        {
            $fields = array_column($responseBody['fieldsMetadata'], 'name');

            $entities = Collection::make($responseBody['rows'])->map(function ($item) use ($fields) {
                return new Record(array_combine($fields, $item));
            });

            return new static(
                total: $total,
                hasMoreResult: false,
                offset: 0,
                entities: $entities
            );
        }

        return new static(
            total: 0,
            hasMoreResult: false,
            offset: 0,
            entities: Collection::empty()
        );
    }
}
