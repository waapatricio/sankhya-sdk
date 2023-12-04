<?php

namespace Sankhya;

use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Contracts\Authenticator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Sankhya\Exceptions\SankhyaException;
use Sankhya\Http\Auth\SankhyaAuthenticator;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadViewRequest;
use Sankhya\Http\Resources\AddressResource;
use Sankhya\Http\Resources\CityResource;
use Sankhya\Http\Resources\CustomerResource;
use Sankhya\Http\Resources\NeighborhoodResource;
use Sankhya\Http\Resources\OrderResource;
use Sankhya\Http\Resources\ProductResource;
use Sankhya\Http\Resources\StateResource;
use Sankhya\Traits\HasLogging;
use Throwable;

class Sankhya extends Connector {

    use AcceptsJson;
    use HasLogging;
    use AlwaysThrowOnErrors;

    protected ?string $appkey = null;
    protected ?string $token = null;
    protected ?string $user = null;
    protected ?string $pass = null;

    public function __construct(?string $appkey = null, ?string $token = null, ?string $user = null, ?string $pass = null)
    {
        $this->appkey = $appkey ?? config('services.sankhya.appkey');
        $this->token = $token ?? config('services.sankhya.token');
        $this->user = $user ?? config('services.sankhya.user');
        $this->pass = $pass ?? config('services.sankhya.pass');
    }

    /**
     * Resolve the base URL of the service.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return (string) config('services.sankhya.url', 'https://api.sankhya.com.br');
    }

    /**
     * Define default headers
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [
            'timeout' => 60,
        ];
    }

    /**
     * Define default Authenticator
     *
     * @return Authenticator|null
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new SankhyaAuthenticator(
            $this->appkey,
            $this->token,
            $this->user,
            $this->pass
        );
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {

        if (array_key_exists('error', $response->json())) {
            return new SankhyaException($response->json('error.descricao'));
        }

        if (array_key_exists('status', $response->json()) && $response->json('status') == 0) {
            return new SankhyaException($response->json('statusMessage'));
        }

//        dd('ExceptionConnector', $response, $response->json());
        return parent::getRequestException($response, $senderException);
    }

//    public function paginate(Request $request, mixed ...$additionalArguments): Paginator
//    {
//        return new SankhyaPaginator($this, $request, 50);
//    }

    public function customers(): CustomerResource
    {
        return new CustomerResource($this);
    }

    public function products(): ProductResource
    {
        return new ProductResource($this);
    }

    public function orders(): OrderResource
    {
        return new OrderResource($this);
    }

    public function states(): StateResource
    {
        return new StateResource($this);
    }

    public function cities(): CityResource
    {
        return new CityResource($this);
    }

    public function neighborhoods(): NeighborhoodResource
    {
        return new NeighborhoodResource($this);
    }

    public function addresses(): AddressResource
    {
        return new AddressResource($this);
    }

    public function view(string $viewName, ?array $fields = null, ?string $where = null, ?array $orderby = null): Response
    {
        return $this->send(
            new LoadViewRequest(
                viewName: $viewName,
                fields: $fields,
                where: $where,
                orderby: $orderby
            )
        );
    }

    public function sql(string $sql): Response
    {
        return $this->send(
            new ExecuteQueryRequest(sql: $sql)
        );
    }

}
