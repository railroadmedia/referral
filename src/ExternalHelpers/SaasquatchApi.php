<?php

namespace Railroad\Referral\ExternalHelpers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class SaasquatchApi
{
    CONST BASE_URI = 'https://app.referralsaasquatch.com';

    /**
     * @var Client
     */
    protected $httpClient;

    protected $saasquatchApiKey;
    protected $saasquatchTenantAlias;

    /**
     * SaasquatchApi constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => self::BASE_URI]);

        $this->saasquatchApiKey = config('referral.saasquatch_api_key');
        $this->saasquatchTenantAlias = config('referral.saasquatch_tenant_alias');
    }

    public function createUser($userId)
    {
        $method = 'POST';
        $pathFormat = '/api/v1/%s/open/account/%s/user/%s';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias, $userId, $userId);

        $requestJsonBody = [
            'id' => $userId,
            'accountId' => $userId,
            // 'referralCodes' => [
            //     'referral-program-Id' => $userId
            // ]
        ];

        return $this->sendRequest($method, $path, $requestJsonBody);
    }

    public function getUsers()
    {
        $method = 'GET';
        $pathFormat = '/api/v1/%s/users';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias);

        return $this->sendRequest($method, $path);
    }

    public function sendRequest($method, $path, $requestJsonBody = null, $requestBody = null)
    {
        $requestData = [RequestOptions::AUTH => ['', $this->saasquatchApiKey]];

        if (!empty($requestJsonBody)) {
            $requestData[RequestOptions::JSON] = $requestJsonBody;
        } else if (!empty($requestBody)) {
            $requestData[RequestOptions::BODY] = $requestBody;
        }

        $response = $this->httpClient->request(
            $method,
            $path,
            $requestData
        );

        return $response->getBody()->getContents();
    }
}
