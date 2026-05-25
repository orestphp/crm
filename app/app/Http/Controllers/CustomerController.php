<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
        ]);

        $customer = new Customer();
        $customer->phone = $validated['phone'];

        // temporary data
        $customer->temp_name = $validated['name'];
        $customer->temp_email = $validated['email'];

        // will trigger static::creating() in Customer::booted()
        $customer->save();

        return response()->json([
            'message' => 'Customer and User created successfully with Role "customer"',
            'customer' => $customer->load('user')
        ], 201);
    }
}
