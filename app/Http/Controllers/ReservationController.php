<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Consumable;
use App\Models\Payment;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        return view('reservation.index')->with([
            'reservations' => Auth::user()->reservations()->with(['consumables', 'movie.hall'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();

        if($data['vip_seats'] == 0 && $data['standard_seats'] == 0)
            return back()->with('error', 'You must reserve one seat at least');
        if(isset($data['consumables']))
        {
            $consumables = $data['consumables'];
            unset($data['consumables']);
        }
        $data['user_id'] = Auth::id();
        $reservation = Reservation::create($data);
        if(isset($consumables)){
            $consumables = array_filter($consumables, fn ($consumable) => $consumable['quantity'] > 0);
            $reservation->consumables()->attach(
                array_map(fn($consumable) => [
                    'consumable_id' => $consumable['id'],
                    'quantity' => $consumable['quantity'],
                    'price' => Consumable::find($consumable['id'])->price
                ], $consumables)
            );
        }
        if($reservation->total_price > Auth::user()->balance){
            $reservation->delete();
            return back()->with('error', 'You do not have enough balance');
        }
        Auth::user()->decrement('balance', $reservation->total_price);
        Payment::create([
           'user_id' => Auth::id(),
           'amount'  => $reservation->total_price
        ]);
        return back()->with('success', 'Reservation created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    public function downloadImage(Reservation $reservation)
    {
        $pdf = PDF::loadView('reservation_photo', compact('reservation'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('reservation_' . $reservation->id . '.pdf');
    }
}
