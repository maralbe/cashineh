<?php


namespace thirdparty\cashineh;


use Exception;
use GuzzleHttp\Client;
use thirdparty\cashineh\Exception\ApiResponseException;

/**
 * Class CashinehGuzzleHttpClient
 *
 * @package thirdparty\cashine
 */
class CashinehGuzzleHttpClient implements iCashinehAPIClient
{
    /**
     * @var array
     */
    protected $default_config = [
        'save_order_info_url' => 'https://api.cashineh.com/saveOrderInfo',
        'website_url'         => 'https://www.azki.com/',
    ];

    /**
     * @var array
     */
    private $config;
    /**
     * @var string
     */
    private $token;
    /**
     * @var Client
     */
    private $http_client;
    /**
     * @var int
     */
    private $shopping_trip_id;
    /**
     * @var int
     */
    private $total_amount;
    /**
     * @var int
     */
    private $order_id;
    /**
     * @var int
     */
    private $shopping_status;
    /**
     * @var string
     */
    private $order_date;

    /**
     * total_amount => required in Toman
     * shopping_status => [0 => pending, 1 => success, 2 => canceled]
     * order_date => format (Y-m-d)
     *
     * CashinehGuzzleHttpClient constructor.
     *
     * @param string $token
     * @param int    $shopping_trip_id
     * @param int    $total_amount
     * @param int    $order_id
     * @param int    $shopping_status
     * @param string $order_date
     * @param array  $config
     */
    public function __construct(string $token, int $shopping_trip_id, int $total_amount, int $order_id, int $shopping_status, string $order_date, array $config = [])
    {
        $this->config = $config;
        $this->token = $token;
        $this->http_client = new Client();
        $this->shopping_trip_id = $shopping_trip_id;
        $this->total_amount = $total_amount;
        $this->order_id = $order_id;
        $this->shopping_status = $shopping_status;
        $this->order_date = $order_date;
    }

    function saveOrderInfo()
    {
        return $this->postRequest($this->getConfig('save_order_info_url'), [
                'url'              => $this->config['website_url'] ?? $this->default_config['website_url'],
                'shopping_trip_id' => $this->shopping_trip_id,
                'token'            => $this->token,
                'total_amount'     => $this->total_amount,
                'order_id'         => $this->order_id,
                'shopping_status'  => $this->shopping_status,
                'orderAt'          => $this->order_date,
            ]
        );
    }

    /**
     * @param string $key
     * @return string
     */
    public function getConfig(string $key)
    {
        return $this->config[$key] ?? $this->default_config[$key];
    }

    /**
     * @param string $url
     * @param array  $request_body
     * @return mixed
     * @throws ApiResponseException
     */
    private function postRequest(string $url, array $request_body)
    {
        $headers = [
            'Content-Type'  => 'application/json'
        ];

        try {
            $response = $this->http_client->request(
                'POST',
                $url,
                [
                    'headers'     => $headers,
                    'json'        => $request_body
                ]
            );

            return $response->getBody()->getContents();
        } catch (Exception $e) {

            throw new ApiResponseException(sprintf('Exception class : %s , Exception Message is : %s', get_class($e), $e->getMessage()));
        }
    }
}