<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DragonCode\Contracts\Cashier\Auth\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AccessTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['sometimes', 'nullable'],
            'abilities' => ['array']
        ]);

        // Auth::guard('sanctum')
        //     ->attempt(['email']);

        $user = User::whereEmail($request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $name = $request->post('device_name', $request->userAgent());
            $abilities = $request->post('abilities', ['*']);
            $token = $user->createToken($name, $abilities, now()->addDays(90));

            return Response::json([
                'token' =>  $token->plainTextToken,
                'user' => $user,
            ], 201);
        }
        return Response::json([
            'message' => __('Invalid Credentials.')
        ], 401);
    }
}
