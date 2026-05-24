<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Pest\Expectations\json;

class TicketApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::with('customer')->get();

        return response()->json($tickets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('tickets.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'text' => ['required', 'string', 'min:10'],
        ], [
            'customer_id.required' => 'Please, choose a Customer',
            'customer_id.exists' => 'Chosen Customer does not exists',
            'text.required' => 'Description field is required',
            'text.min' => 'Description must be at least 10 characters long',
        ]);

        Ticket::create($validatedData);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'New ticket successfully generated and added to the queue!');
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            // Статистика за весь час
            'all_time' => [
                'total'      => Ticket::count(),
                'new'        => Ticket::newTickets()->count(),
                'in_process' => Ticket::inProcess()->count(),
                'processed'  => Ticket::processed()->count(),
            ],
            // За останню добу (24 години)
            'today' => [
                'total'      => Ticket::createdToday()->count(),
                'new'        => Ticket::createdToday()->newTickets()->count(),
                'in_process' => Ticket::createdToday()->inProcess()->count(),
                'processed'  => Ticket::createdToday()->processed()->count(),
            ],
            // За останній тиждень (7 днів)
            'week' => [
                'total'      => Ticket::createdThisWeek()->count(),
                'new'        => Ticket::createdThisWeek()->newTickets()->count(),
                'in_process' => Ticket::createdThisWeek()->inProcess()->count(),
                'processed'  => Ticket::createdThisWeek()->processed()->count(),
            ],
            // За останній місяць (30 днів)
            'month' => [
                'total'      => Ticket::createdThisMonth()->count(),
                'new'        => Ticket::createdThisMonth()->newTickets()->count(),
                'in_process' => Ticket::createdThisMonth()->inProcess()->count(),
                'processed'  => Ticket::createdThisMonth()->processed()->count(),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('customer');

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $ticket->load('customer');

        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validatedData = $request->validate([
            'text' => ['required', 'string', 'min:10'],
            'status' => ['required', 'in:new,in process,processed'],
        ], [
            'text.required' => 'Description field is required',
            'text.min' => 'Description must be at least 10 characters long',
            'status.required' => 'Status is required',
            'status.in' => 'Wrong status',
        ]);

        $ticket->update($validatedData);

        return redirect()
            ->route('tickets.index')
            ->with('success', "Ticket #{$ticket->id} successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
