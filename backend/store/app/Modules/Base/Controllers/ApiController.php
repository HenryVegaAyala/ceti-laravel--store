<?php

namespace App\Modules\Base\Controllers;

use App\Traits\JsonResponseTrait;

class ApiController extends Controller
{
    use JsonResponseTrait;

    /**
     * Aplica el middleware auth:api a todos los métodos del controlador.
     * Los controladores hijos pueden excluir métodos públicos pasando un array
     * de métodos a $except en su propio constructor antes de llamar a parent::__construct().
     *
     * @param array $except Métodos que NO requieren autenticación.
     */
    public function __construct(array $except = [])
    {
        $this->middleware('auth:api')->except($except);
    }

}
