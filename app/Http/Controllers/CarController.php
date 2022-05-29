<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarFullResource;
use App\Http\Resources\CarShortResource;
use App\Models\Car;
use App\Models\CarMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response(200, CarShortResource::collection(Car::all()), 'Cars List Ready');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFull()
    {
        return $this->response(200, CarFullResource::collection(Car::all()), 'Cars List Ready');
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
        return $this->saveCar($request);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $car = Car::where('slug', $slug)->first();
        if ( ! $car) {
            return $this->response(404, [], 'Car Not Found');
        } else {
            return $this->response(200, new CarFullResource($car), 'Car Info Ready');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $car = Car::where('slug', $slug)->first();
        if ( ! $car) {
            return $this->response(404, [], 'Car Not Found');
        } else {
            return $this->saveCar($request, false, $car);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $car = Car::where('slug', $slug)->first();
        if ( ! $car) {
            return $this->response(404, [], 'Car Not Found');
        } else {
            try {
                if ($car->meta()) {
                    $car->meta()->delete();
                }
                $car->delete();

                return $this->response(201, [], 'Successfully deleted');
            } catch (\Exception $e) {
                return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
            }
        }
    }

    private function saveCar(Request $request, bool $isCreated = true, Car $car = null)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: Str::slug($request->input('name')) ?: '')
        ]);

        $request->validate([
            'slug'                 => 'required|string|max:255|unique:cars,slug'.($car ? ','.$car->id : ''),
            'name'                 => 'required|string|max:255',
            'fuel_type'            => 'required|in:gasoline,gas_oil',
            'plates'               => 'nullable|string|max:10',
            'limits-*-title'       => 'nullable|string|max:255',
            'limits-*-description' => 'nullable|string|max:511',
            'limits-*-value'       => 'nullable|float',
            'limits-*-measure'     => 'nullable|string|max:25',
        ]);

        $input_data = $request->all();
        $car_data   = [];

        foreach ($input_data as $key => $value) {
            $car_array_key = str_replace('-', '.', $key);
            Arr::set($car_data, $car_array_key, $value);
        }

        $successMessage = $car ? 'Successfully updated' : 'Successfully created';

        try {

            DB::beginTransaction();

            if ($isCreated) {
                $car = new Car();
            }
            $car->slug = $car_data['slug'];
            $car->name = $car_data['name'];
            $car->fuel_type = $car_data['fuel_type'];

            if ($isCreated) {
                $carMeta = CarMeta::create($car_data);
                if ($carMeta->_id) {
                    $car->car_meta_id = $carMeta->_id;
                }
            } else {
                $carMeta = $car->meta();
                $carMeta->plates = $car_data['plates'];
                $carMeta->limits = $car_data['limits'];
                $carMeta->save();
            }

            $car->save();

            DB::commit();

            return $this->response(201, [], $successMessage);

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');

        }
    }
}
