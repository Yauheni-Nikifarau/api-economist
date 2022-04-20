<?php

namespace App\Http\Controllers;

use App\Http\Resources\FuelEntryResource;
use App\Models\FuelEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class FuelEntryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response( FuelEntryResource::collection( FuelEntry::all() ), 200 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request ) {
        list('fuel_type' => $fuel_type, 'amount' => $amount) = $request->validate( [
            'fuel_type' => 'required|in:gasoline,gas_oil',
            'amount'    => 'required|int'
        ] );

        try {
            DB::beginTransaction();
            $fuelEntry = new FuelEntry();
            $fuelEntry->fuel_type = $fuel_type;
            $fuelEntry->amount = $amount;
            $fuelEntry->save();
            DB::commit();
            return response('', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response('error', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\FuelEntry $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function show( FuelEntry $fuelEntry ) {
        return response( FuelEntryResource::collection( $fuelEntry ), 200 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\FuelEntry $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, FuelEntry $fuelEntry ) {
        list('fuel_type' => $fuel_type, 'amount' => $amount) = $request->validate( [
            'fuel_type' => 'required|in:gasoline,gas_oil',
            'amount'    => 'required|int'
        ] );

        try {
            DB::beginTransaction();
            $fuelEntry->fuel_type = $fuel_type;
            $fuelEntry->amount = $amount;
            $fuelEntry->save();
            DB::commit();
            return response('', 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response('error', 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\FuelEntry $fuelEntry
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( FuelEntry $fuelEntry ) {
        $fuelEntry->delete();
        return response('', 204);
    }
}
