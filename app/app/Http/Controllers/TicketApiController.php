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
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $tickets = [];
        if ($user->hasRole('customer')) {
            $tickets = Ticket::query()
                ->when($user->hasRole('customer'), function ($query) use ($user) {
                    return $query->where('customer_id', $user->id);
                })->get();
        } else {
            $tickets = Ticket::with('customer')->get();
        }

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
     * Save: Ticket and Files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:5',
            'attachments.*' => 'sometimes|file|max:10240', // max 10MB per file
        ]);

        $ticket = Ticket::create([
            'text' => $request->text,
            'customer_id' => $request->user()->id,
            'status' => 'new'
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $ticket->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }

        return response()->json([
            'message' => 'Ticket created successfully',
            'ticket'  => $ticket->load('media')
        ], 201);
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'all_time' => [
                'total'      => Ticket::count(),
                'new'        => Ticket::newTickets()->count(),
                'in_process' => Ticket::inProcess()->count(),
                'processed'  => Ticket::processed()->count(),
            ],

            'today' => [
                'total'      => Ticket::createdToday()->count(),
                'new'        => Ticket::createdToday()->newTickets()->count(),
                'in_process' => Ticket::createdToday()->inProcess()->count(),
                'processed'  => Ticket::createdToday()->processed()->count(),
            ],

            'week' => [
                'total'      => Ticket::createdThisWeek()->count(),
                'new'        => Ticket::createdThisWeek()->newTickets()->count(),
                'in_process' => Ticket::createdThisWeek()->inProcess()->count(),
                'processed'  => Ticket::createdThisWeek()->processed()->count(),
            ],

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

        return response()->json($ticket->load('media'));
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
        $user = $request->user();

        $validated = $request->validate([
            'text' => 'required|string|min:5',
            'status' => 'sometimes|string|in:new,in process,processed',
            'attachments.*' => 'sometimes|file|max:10240', // max 10MB per file
        ]);

        // Check Permissions
        if ($user->hasRole('customer')) {
            if ($ticket->customer_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            // customer can't change status
            unset($validated['status']);
        } else {
            // Admin or manager edits the ticket. They usually only change the status.
            unset($validated['text']);

        }

        // Update ticket
        $ticket->update($validated);

        // Files (via Edit)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $ticket->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }

        // Response
        return response()->json([
            'message' => 'Ticket updated successfully',
            'ticket'  => $ticket->load('media')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
