<?php

namespace App\Http\Api\Login;

use App\Modules\Base\Controllers\ApiController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        // El endpoint de login es público, no requiere autenticación previa.
        parent::__construct(['__invoke']);
    }

    public function __invoke(Request $request)
    {
        $formData = $request->only(['email', 'password']);

        Auth::attempt($formData);

        $userAuth = $request->user();
        $token = $userAuth->createToken('auth_token');

        return $this->successResponse([
           'message' => 'Usuario autenticado correctamente',
           'token' => $token->accessToken
        ]);
    }
}
