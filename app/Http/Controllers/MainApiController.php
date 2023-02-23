<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Class MainApiController
 * @package App\Http\Controllers\CRM\v1
 */
abstract class MainApiController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    public $status = 'success';

    public $errors = [];

    public $message = '';

    public $statusCode = 200;

    public $redirectTo = false;

    public $optionsParams = '';

    /**
     * MainController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @return mixed
     */
    protected function error($message = 'Validation Failed', $data = null, $statusCode = 400)
    {
        $arr = [
            'status' => 'error',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ];
        return json_encode($arr);
    }

    /**
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @return mixed
     */
    protected function success($message = '', $data = null, $statusCode = 200)
    {
        $arr = [
            'status' => 'success',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ];
        return json_encode($arr);
    }


    /**
     * @param string $url
     * @param array $postDataArray
     * @return mixed
     */
    public function httpPost(string $url, array $postDataArray)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-trackbox-username' => $postDataArray['email'],
            'x-trackbox-password' => $postDataArray['password'],
            'x-api-key' => $postDataArray['api_key']
        ])->post($url, $postDataArray);

        return $response;
    }

}
