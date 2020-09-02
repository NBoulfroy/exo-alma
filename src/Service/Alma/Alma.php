<?php
/**
 * Alma payment service class.
 *
 * @Project : alma
 * @File    : Alma.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 * @Update  : 2020/09/02
 */

namespace App\Service\Alma;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Routing\Router;

class Alma
{
    /**
     * Guzzle Client object.
     *
     * @var Client
     */
    private $client;

    /**
     * Alma API key.
     *
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * Basic URL to request API.
     *
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * CardController constructor.
     *
     * @param Client $client
     * @param string $apiKey
     *
     * @return void
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->baseUrl = $client->getConfig('base_uri')->__toString();
    }

    /**
     * Creates an estimation of payments and if it is possible.
     *
     * @param float $amount
     * @param int   $installmentsCount
     *
     * @return string
     *
     * @throws GuzzleException
     */
    public function checkEligibility(float $amount, int $installmentsCount): string
    {
        return $this->client->post(
            $this->baseUrl.'payments/eligibility',
            [
                'headers' => [
                    'Authorization' => 'Alma-Auth '.$this->apiKey,
                ],
                'body' => json_encode([
                    'payment' => [
                        'purchase_amount' => $amount,
                        'installments_count' => [$installmentsCount],
                    ]
                ]),
            ]
        )->getBody()->getContents();
    }

    /**
     * Creates a payment in Alma.
     *
     * @param float $purchaseAmount
     * @param int $installmentsCount
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phoneNumber
     * @param string $address
     *
     * @return string
     *
     * @throws GuzzleException
     */
    public function paymentProceeding(
        float $purchaseAmount,
        int $installmentsCount,
        string $firstName,
        string $lastName,
        string $email,
        string $phoneNumber,
        string $address
    ): string {
        $dataAddress = explode(', ', $address);

        return $this->client->post(
            $this->baseUrl.'payments',
            [
                'headers' => [
                    'Authorization' => 'Alma-Auth '.$this->apiKey,
                ],
                'body' => json_encode([
                    'payment' => [
                        'purchase_amount' => $purchaseAmount,
                        'installments_count' => $installmentsCount,
                        'return_url' => '',
                        'shipping_address' => [
                            'line1' => $dataAddress[0],
                            'postal_code' => $dataAddress[1],
                            'city' => $dataAddress[2],
                        ],
                        'customer' => [
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'phone' => $phoneNumber,
                        ]
                    ]
                ]),
            ]
        )->getBody()->getContents();
    }
}
