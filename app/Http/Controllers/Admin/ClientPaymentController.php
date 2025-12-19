<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreClientPaymentRequest;
use App\Models\Client;
use App\Services\ClientAccountService;

class ClientPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:edit-Client')->only(['create', 'store']);
    }

    public function create(string $Id)
    {
        $client = Client::findOrFail($Id);
        return view('admin.clients.pay', compact('client'));
    }

    public function store(StoreClientPaymentRequest $request, string $Id)
    {
        $client = Client::findOrFail($Id);
        $validated = $request->validated();
        $amount = (float) $validated['amount'];

        (new ClientAccountService)->ClientPayment($client, $amount, $validated['description']);

        return redirect()
            ->route('admin.clients.show', $client->id)
            ->with('success', 'Payment recorded and balance updated.');
    }
}


