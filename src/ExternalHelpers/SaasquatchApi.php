<?php

namespace Railroad\Referral\ExternalHelpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Railroad\Referral\Exceptions\NotFoundException;
use Railroad\Referral\Exceptions\ReferralException;
use Railroad\Referral\Exceptions\SaasquatchException;
use Railroad\Referral\Exceptions\SaasquatchUserExistsException;

class SaasquatchApi
{
    CONST BASE_URI = 'https://app.referralsaasquatch.com';

    /**
     * @var Client
     */
    protected $httpClient;

    protected $saasquatchApiKey;
    protected $saasquatchTenantAlias;
    protected $saasquatchReferralProgramId;

    /**
     * SaasquatchApi constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => self::BASE_URI]);

        $this->saasquatchApiKey = config('referral.saasquatch_api_key');
        $this->saasquatchTenantAlias = config('referral.saasquatch_tenant_alias');
        $this->saasquatchReferralProgramId = config('referral.saasquatch_referral_program_id');
    }

    /**
     * @param int $userId
     *
     * @return object
     *
     * @throws ReferralException
     * @throws SaasquatchException
     * @throws SaasquatchUserExistsException
     */
    public function createUser($userId)
    {
        $method = 'POST';
        $pathFormat = '/api/v1/%s/open/account/%s/user/%s?fields=';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias, $userId, $userId);

        $requestJsonBody = [
            'id' => $userId,
            'accountId' => $userId,
        ];

        if (!empty($this->saasquatchReferralProgramId)) {
            $requestJsonBody['referralCodes'] = [$this->saasquatchReferralProgramId => $userId . '-' . $this->saasquatchReferralProgramId];
        }



        return $this->sendRequest($method, $path, $requestJsonBody);
    }

    /**
     * @param int $userId
     *
     * @return object
     *
     * @throws NotFoundException
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function getUser($userId)
    {
        $method = 'GET';
        $pathFormat = '/api/v1/%s/open/account/%s/user/%s?fields=';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias, $userId, $userId);

        $user = $this->sendRequest($method, $path);

        return $this->sendRequest($method, $path);
    }

    /**
     * @return object
     *
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function getUsers()
    {
        $method = 'GET';
        $pathFormat = '/api/v1/%s/users';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias);

        return $this->sendRequest($method, $path);
    }

    /**
     * @param int $userId
     *
     * @throws NotFoundException
     * @throws ReferralException
     * @throws SaasquatchException
     */
    public function removeUser($userId)
    {
        $method = 'DELETE';
        $pathFormat = '/api/v1/%s/open/account/%s/user/%s';
        $path = sprintf($pathFormat, $this->saasquatchTenantAlias, $userId, $userId);

        $this->sendRequest($method, $path);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $requestJsonBody
     * @param string $requestBody
     *
     * @return object
     *
     * @throws NotFoundException
     * @throws ReferralException
     * @throws SaasquatchException
     * @throws SaasquatchUserExistsException
     */
    public function sendRequest($method, $path, $requestJsonBody = null, $requestBody = null)
    {
        $requestData = [RequestOptions::AUTH => ['', $this->saasquatchApiKey]];

        if (!empty($requestJsonBody)) {
            $requestData[RequestOptions::JSON] = $requestJsonBody;
        } else if (!empty($requestBody)) {
            $requestData[RequestOptions::BODY] = $requestBody;
        }

        $result = null;

        try {
            $response = $this->httpClient->request(
                $method,
                $path,
                $requestData
            );

            $result = json_decode($response->getBody()->getContents());
        } catch (ClientException $cex) {

            if ($cex->getCode() == 404) {
                $message = 'Entity not found';

                if ($cex->getResponse() instanceof Response) {
                    try {
                        $responseContents = $cex->getResponse()->getBody()->getContents();
                        $responseData = json_decode($responseContents);
                        $message = $responseData->message;
                    } catch (Exception $e) {
                    }
                }

                throw new NotFoundException($message);
            } if ($cex->getCode() == 400 && $cex->getResponse() instanceof Response) {
                $message = null;

                try {
                    $responseContents = $cex->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseContents);
                    $message = $responseData->message;
                } catch (Exception $e) {
                }

                if ($message == 'User already exists') {
                    throw new SaasquatchUserExistsException();
                }
            }

            throw new ReferralException($cex->getMessage(), $cex->getCode());

        } catch (ServerException $serEx) {

            $message = $serEx->getMessage();

            if ($serEx->getResponse() instanceof Response) {
                try {
                    $responseContents = $serEx->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseContents);
                    $message = $responseData->message;
                } catch (Exception $e) {
                }
            }

            throw new SaasquatchException($message, $serEx->getCode());

        } catch (Exception $ex) {
            throw new ReferralException($ex->getMessage(), $serEx->getCode());
        }

        return $result;
    }
}
