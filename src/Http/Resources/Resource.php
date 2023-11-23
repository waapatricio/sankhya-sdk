<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Connector;
use Saloon\Http\Response;
use Sankhya\Http\Requests\LoadRecordsRequest;

class Resource
{

    public string $entity;

    public string $primaryKey;

    public array $defaultFields;

    public function __construct(
        readonly protected Connector $connector
    ){}

    public function list(
        int $page = 0,
        ?int $limit = null,
        ?array $fields = null,
        bool $showRelationship = false,
    ): Response
    {
        return $this->where(
            fields: $fields,
            page: $page,
            limit: $limit,
            showRelationship: $showRelationship
        );
    }

    public function get(
        int $id,
        ?array $fields = null,
        bool $showRelationship = false
    ): Response
    {
        return $this->where(
            where: "{$this->primaryKey} = '$id'",
            fields: $fields,
            showRelationship: $showRelationship
        );
    }

    public function find(
        string $value,
        string $key,
        ?array $fields = null,
        ?int $page = null,
        ?int $limit =  null,
        bool $showRelationship = false
    ): Response
    {
        return $this->where(
            where: "$key = '$value'",
            fields: $fields,
            page: $page,
            limit: $limit,
            showRelationship: $showRelationship
        );
    }

    public function where(
        ?string $where = null,
        ?array $fields = null,
        ?int $page = null,
        ?int $limit = null,
        bool $showRelationship = false
    ): Response
    {
        $fields = $fields ?: $this->defaultFields;
        $page = $page ?: 0;
        $limit = $limit ?: 20;

        return $this->connector->send(
            new LoadRecordsRequest(
                resource: $this->entity,
                page: $page,
                limit: $limit,
                showRelationship: $showRelationship,
                fields: $fields,
                where: $where
            )
        );
    }

}
