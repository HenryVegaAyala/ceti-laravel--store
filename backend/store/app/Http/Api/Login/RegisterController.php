<?php

namespace App\Http\Api\Login;

use App\Modules\Base\Controllers\ApiController;
use app\Modules\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegisterController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __invoke(Request $request): JsonResponse
    {
        # Primera forma de capturar los datos del request
        $name = $request->post('name');
        $email = $request->post('email');
        $password = $request->post('password');

        # Segunda forma de capturar los datos del request
        // $formData = $request->only(['name', 'email', 'password']);

        try {
            User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);
        } catch (\Exception $exception) {
            return $this->errorResponse(['message' => 'Error al registrar usuario']);
        }

        return $this->successResponse(['message' => 'Se ha registrado el usuario correctamente']);
    }
}
