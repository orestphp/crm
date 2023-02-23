<?php
namespace App\Http\Controllers\CRM\v1;

use App\Http\Controllers\MainApiController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends MainApiController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', $validator->errors(), 422);
        }

        // Auth
        if (! $token = auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return $this->error('Unauthorized', ['error' => 'Unauthorized'], 401);
        }

        // Response
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()) {
            // Errors
            return $this->error('Validation Failed', $validator->errors(), 400);
        }

        $arrUser = array_merge(
            $request->all(),
            ['password' => bcrypt($request->input('password'))]
        );
        // Save
        $crmUser = User::create($arrUser);

        // Response
        return $this->success('User successfully registered', ['crmUser' => $crmUser] ,201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->success('User successfully signed out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return $this->success('', ['crmUser' => auth()->user()]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return $this->success('', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'crmUser' => auth()->user()
        ]);
    }
}
