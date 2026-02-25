<?php

namespace App\Modules\Base\Controllers;

use App\Traits\JsonResponseTrait;

class ApiController extends Controller
{
    use JsonResponseTrait;

    public function __construct()
    {
    }

}
