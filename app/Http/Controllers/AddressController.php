<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'phone' => 'nullable|string',
        ]);
        Address::create($validated);
        return redirect()->route('addresses.index');
    }

    public function show(Address $address)
    {
        return view('addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'phone' => 'nullable|string',
        ]);
        $address->update($validated);
        return redirect()->route('addresses.index');
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.index');
    }
} 