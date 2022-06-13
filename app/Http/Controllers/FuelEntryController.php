<?php

namespace App\Http\Controllers;

use App\Http\Resources\FuelEntryResource;
use App\Models\FuelEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response(200, FuelEntryResource::collection(FuelEntry::all()), 'Fuel Entries List Ready');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->saveFuelEntry($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FuelEntry  $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function show(FuelEntry $fuelEntry)
    {
        return $this->response(200, new FuelEntryResource($fuelEntry), 'Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FuelEntry  $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FuelEntry $fuelEntry)
    {
        return $this->saveFuelEntry($request, false, $fuelEntry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FuelEntry  $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(FuelEntry $fuelEntry)
    {
        try {
            $fuelEntry->delete();

            return $this->response(201, [], 'Successfully deleted');
        } catch (\Exception $e) {
            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }

    private function saveFuelEntry(Request $request, bool $isCreated = true, FuelEntry $fuelEntry = null)
    {
        list('fuel_type' => $fuelType, 'amount' => $amount) = $request->validate([
            'fuel_type' => 'required|in:gasoline,gas_oil',
            'amount'    => 'required|int'
        ]);

        $successMessage = $fuelEntry ? 'Successfully updated' : 'Successfully created';

        try {
            DB::beginTransaction();
            if ($isCreated) {
                $fuelEntry = new FuelEntry();
            }
            $fuelEntry->fuel_type = $fuelType;
            $fuelEntry->amount    = $amount;
            $fuelEntry->save();
            DB::commit();

            return $this->response(201, [], $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }
}
