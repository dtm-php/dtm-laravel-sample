<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class AbstractController extends BaseController
{
    protected string $serviceUri = 'http://127.0.0.1:8000/api';
}
