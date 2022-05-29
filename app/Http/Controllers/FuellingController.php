<?php

namespace App\Http\Controllers;

use App\Http\Resources\FuellingResource;
use App\Models\Fuelling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuellingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response(200, FuellingResource::collection(Fuelling::all()), 'Fuellings List Ready');
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
        return $this->saveFuelling($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fuelling  $fuelling
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Fuelling $fuelling)
    {
        return $this->response(200, new FuellingResource($fuelling), 'Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fuelling  $fuelling
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fuelling $fuelling)
    {
        return $this->saveFuelling($request, false, $fuelling);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fuelling  $fuelling
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fuelling $fuelling)
    {
        try {
            $fuelling->delete();

            return $this->response(201, [], 'Successfully deleted');
        } catch (\Exception $e) {
            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }

    private function saveFuelling(Request $request, bool $isCreated = true, Fuelling $fuelling = null)
    {
        $request->validate([
            'driver_id' => 'required|int|exists:App\Models\Driver,id',
            'car_id'    => 'required|int|exists:App\Models\Car,id',
            'fuel_type' => 'required|in:gasoline,gas_oil',
            'amount'    => 'required|int',
        ]);

        try {

            DB::beginTransaction();

            if ($isCreated) {
                $fuelling = new Fuelling();
            }
            $fuelling->driver_id = $request->input('driver_id');
            $fuelling->car_id    = $request->input('car_id');
            $fuelling->fuel_type = $request->input('fuel_type');
            $fuelling->amount    = $request->input('amount');

            $fuelling->save();

            DB::commit();

            return $this->response(201, [], 'Successfully processed');

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }
}
