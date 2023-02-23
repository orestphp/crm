<?php

namespace App\Http\Controllers\CRM\v1;

use App\Http\Controllers\MainApiController;
use App\Models\CrmClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 * @package App\Http\Controllers\API\v1
 */
class ClientController extends MainApiController
{

    /**
     * Register a Client / Customer.
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'userip' => 'ip|nullable',
            'firstname' => 'string|between:2,100|nullable',
            'lastname' => 'string|between:2,100|nullable',
            'phone' => 'numeric|digits_between:1,15|nullable',
            'email' => 'required|string|email|max:100|unique:crm_clients',// required
            'password' => 'required|string|min:6',// required
        ]);

        if($validator->fails()){
            // Error
            return $this->error('Validation Failed',$validator->errors()->toArray(), 400);
        }

        // Save client
        $client = CrmClient::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->input('password')),
                'client_json' => json_encode($request->all()),
            ]
        ));

        // Success
        return $this->success(
            'Client successfully registered',
            ['client' => $client],
            201
        );
    }

}
