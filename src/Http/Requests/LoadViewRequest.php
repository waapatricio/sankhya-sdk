<?php

namespace Sankhya\Http\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasXmlBody;
use Sankhya\Exceptions\SankhyaException;
use Sankhya\Http\Responses\SankhyaResponse;
use Spatie\ArrayToXml\ArrayToXml;
use Throwable;

class LoadViewRequest extends Request implements HasBody
{
    use HasXmlBody;

    protected Method $method = Method::POST;

    public function __construct(
        public string $viewName,
        public ?array $fields = null,
        public ?string $where = null,
        public ?array $orderby = null,
    ){
    }

    public function resolveEndpoint(): string
    {
        return '/gateway/v1/mge/service.sbr?serviceName=CRUDServiceProvider.loadView';
    }

    protected function getWhere(): ?string
    {
        return ($this->where) ?: null;
    }

    protected function getFields(): ?array
    {
        return ($this->fields) ?: null;
    }

    protected function getOrderby(): ?string
    {
        return ($this->orderby) ? implode(',', $this->orderby): null;
    }

    public function defaultBody(): ?string
    {
        $body = [
            'requestBody' => [
                'query' => [
                    '_attributes' => [
                        'viewName' => $this->viewName
                    ]
                ]
            ]
        ];

        if($this->orderby)
            $body['requestBody']['query']['_attributes']['orderBy'] = $this->getOrderby();

        if($this->fields)
          $body['requestBody']['query']['fields']['field'] = $this->getFields();

        if($this->where)
            $body['requestBody']['query']['where'] = $this->getWhere();

        return ArrayToXml::convert(
            array: $body,
            rootElement: [
                'rootElementName' => 'serviceRequest',
                '_attributes' => [
                    'serviceName' => 'CRUDServiceProvider.loadView'
                ]
            ]
        );
    }

    public function hasRequestFailed(Response $response): bool
    {
        $xml = $response->xml();

        return intval($xml->attributes()->status) != 1;
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        $xml = $response->xml();
        $message = utf8_encode(base64_decode($xml->statusMessage->__toString()));

        return new SankhyaException($message);
    }

    public function createDtoFromResponse(Response $response): SankhyaResponse
    {
        return SankhyaResponse::fromViewResponse($response);
    }
}
