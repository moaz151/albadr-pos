<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ClientRegistrationEnum;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\SignupRequest;
use App\Http\Requests\Api\V1\ProfileRequest;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;
use App\Http\Resources\V1\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $client = Client::where('email', $request->email)->first();
        if (!$client || !password_verify($request->password, $client->password)) {
            return $this->apiErrorMessage("Invalid credentials", 401);
        }

        $token = $client->createToken("api_token")->plainTextToken;

        $cartSynced = false;
        $cartData = null;
        $warnings = [];

        // Optional local cart items sent from frontend for auto-sync on login
        $localCartItems = $request->input('local_cart_items');

        if (is_array($localCartItems) && !empty($localCartItems)) {
            $cartSyncService = app(\App\Services\CartSyncService::class);
            $result = $cartSyncService->syncLocalCartToOnline($client, $localCartItems);

            $cartSynced = true;
            $cartData = new CartResource($result['cart']);
            $warnings = $result['warnings'];
        }

        return $this->responseApi([
            'token' => $token,
            'client' => new ClientResource($client),
            'cart_synced' => $cartSynced,
            'cart' => $cartData,
            'warnings' => $warnings,
        ], "Client logged in successfully");
    }

    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        $client = Client::create($data);
        $client->password = bcrypt($data['password']);
        $client->status = ClientStatusEnum::active->value;
        $client->registered_via = ClientRegistrationEnum::app->value;
        $client->save();
        
        $token = $client->createToken("api_token")->plainTextToken;
        return $this->responseApi([
            'token' => $token,
            'client' => new ClientResource($client)
        ], "client Registered Successfully", 201);
    }

    public function getProfile()
    {
        $client = auth('api')->user()->load('orders');
        return $this->responseApi(new ClientResource($client), "client profile retrieved successfully");
    }

    public function updateProfile(ProfileRequest $request)
    {
        $client = auth('api')->user();
        $data = $request->validated();
        if(isset($data['password']))
        {
            $data['password'] = bcrypt($data['password']);
        }
        $client->update($data);
        return $this->responseApi(new ClientResource($client), "Client profile updated successfully");
    }
}
