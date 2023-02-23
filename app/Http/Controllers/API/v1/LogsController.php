<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainApiController;
use App\Models\CrmClient;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 * @package App\Http\Controllers\API\v1
 */
class LogsController extends MainApiController
{

    /**
     * Register a Client / Customer.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            'password' => 'string|min:6',
            'api_key' => 'required|string|min:30',
            'crm_url' => 'required|string|min:10',
            //'integration_id',// crm id
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Failed',$validator->errors()->toArray());
        }

        // Save client
        $arrLog = array_merge(
            $validator->valid(),
            [
                'json_data' => json_encode($request->input('request')),
            ]
        );


        // Send data to CRM
        $endpoint = ($request->has('register')) ? 'register' : 'login';
        $response = $this->httpPost( env('MIX_CRM_URL') .'/crm/'. $endpoint, $request->all());
        $response = $response->collect()->toArray();

        // Save Logs
        if(!$request->has('register') && $response['status'] == 'success') {
            // TODO: after second submit need to provide - "integration_id" to not save second time
            $logs = Logs::create($arrLog);
            $response['logs'] = collect($logs)->toArray();
            $data = array_merge($response['data'], ['logs' => $response['logs']]);
        } else {
            $data = $response['data'];
        }

        // Success
        if($response['status'] == 'success') {
            return $this->success(
                ($endpoint == 'register') ? 'Registered '.$request->email.' at CRM' : 'Response from CRM',
                $data,
                201
            );
        } else {
            return $this->error(
                $response['message'],
                $response['data'],
                $response['statusCode']
            );
        }
    }

}
