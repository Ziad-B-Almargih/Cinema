<?php

namespace App\Http\Controllers;

use App\Enums\ConsumableType;
use App\Http\Requests\Consumable\StoreConsumableRequest;
use App\Http\Requests\Consumable\UpdateConsumableRequest;
use App\Models\Consumable;

class ConsumableController extends Controller
{
    public function index()
    {
        return view('consumables.index')->with([
            'consumables' => Consumable::all(),
            'types' => ConsumableType::values()
        ]);
    }


    public function store(StoreConsumableRequest $request)
    {
        Consumable::create($request->validated());
        return back()->with('success', 'Consumable created successfully');
    }


    public function update(UpdateConsumableRequest $request, Consumable $consumable)
    {
        $consumable->update($request->validated());
        return back()->with('success', 'Consumable updated successfully');
    }


    public function destroy(Consumable $consumable)
    {
        $consumable->delete();
        return back()->with('success', 'Consumable deleted successfully');
    }
}
