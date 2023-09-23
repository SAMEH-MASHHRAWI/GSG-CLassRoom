<?php

namespace App\Http\Controllers\webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
// use App\Http\Controllers\webhooks\StripeController;
use DragonCode\Contracts\Cashier\Http\Request as HttpRequest;

class StripeController extends Controller{
    public function __invoke(Request $request)
    {

    }
}
