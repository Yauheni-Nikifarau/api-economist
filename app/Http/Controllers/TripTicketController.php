<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripTicketResource;
use App\Http\Resources\TripTicketsMassResource;
use App\Models\TripTicket;
use App\Models\TripTicketMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TripTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response( 200, TripTicketsMassResource::collection( TripTicket::all() ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->saveTripTicket($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function show(TripTicket $tripTicket)
    {
        if ( ! $tripTicket ) {
            return $this->response( 404, [], 'Trip Ticket Not Found' );
        } else {
            return $this->response( 200, new TripTicketResource( $tripTicket ), 'Found' );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TripTicket $tripTicket)
    {
        if ( ! $tripTicket ) {
            return $this->response( 404, [], 'Trip Ticket Not Found' );
        } else {
            return $this->saveCar($request, false, $tripTicket);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TripTicket  $tripTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(TripTicket $tripTicket)
    {
        if ( ! $tripTicket ) {
            return $this->response( 404, [], 'Trip Ticket Not Found' );
        } else {
            try {
                if ($tripTicket->meta()) {
                    $tripTicket->meta()->delete();
                }
                $tripTicket->delete();

                return $this->response( 204, [], 'Successfully deleted' );
            } catch ( \Exception $e ) {
                return $this->response( 500, [ 'error' => $e->getMessage() ], 'Error while processing' );
            }
        }
    }

    private function saveTripTicket (Request $request, bool $isCreated = true, TripTicket $tripTicket = null) {

        $request->validate( [
            'driver_id' => 'required|int|exists:App\Models\Driver,id',
            'car_id'    => 'required|int|exists:App\Models\Car,id',
            'approved_actions-*-key' => 'nullable|int',
            'approved_actions-*-quantity' => 'nullable|int',
            'approved_actions-*-description' => 'nullable|string',
            'approved_actions-*-approver' => 'nullable|string|max:255',
        ] );

        $input_data = $request->all();
        $tripTicketData = [];

        foreach ($input_data as $key => $value) {
            $tripTicket_array_key = str_replace('-', '.', $key);
            Arr::set($tripTicketData, $tripTicket_array_key, $value);
        }

        try {

            DB::beginTransaction();

            if ($isCreated) {
                $tripTicket = new TripTicket();
            }
            $tripTicket->driver_id = $tripTicketData['driver_id'];
            unset($tripTicketData['driver_id']);
            $tripTicket->car_id = $tripTicketData['car_id'];
            unset($tripTicketData['car_id']);

            if ($isCreated) {
                $tripTicketMeta = TripTicketMeta::create($tripTicketData);
                if ($tripTicketMeta->_id) {
                    $tripTicket->trip_ticket_meta_id = $tripTicketMeta->_id;
                }
            } else {
                $tripTicket->meta()->save($tripTicketData);
            }
            $tripTicket->save();
            DB::commit();

            return $this->response( 201, [], 'Successfully processed' );

        }  catch (\Exception $e) {

            DB::rollBack();

            return $this->response( 500, [ 'error' => $e->getMessage() ], 'Error while processing' );

        }
    }
}
