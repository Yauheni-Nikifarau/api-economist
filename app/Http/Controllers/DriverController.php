<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriverResource;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response(200, DriverResource::collection(Driver::all()), 'Drivers List Ready');
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
        return $this->saveDriver($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return $this->response(200, new DriverResource($driver), 'Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        return $this->saveDriver($request, false, $driver);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        try {
            $driver->delete();

            return $this->response(201, [], 'Successfully deleted');
        } catch (\Exception $e) {
            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }

    private function saveDriver(Request $request, bool $isCreated = true, Driver $driver = null)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: '')
        ]);

        list('slug' => $slug, 'name' => $name) = $request->validate([
            'slug' => 'nullable|string|max:255|unique:drivers,slug' . ($driver ? ',' . $driver->id : ''),
            'name' => 'required|string|max:255'
        ]);

        $successMessage = $driver ? 'Successfully updated' : 'Successfully created';

        if (empty($slug)) {
            if (Driver::where('slug', $slug)->exists()) {
                return response('auto slug exists ('.$slug.')', 422);
            }
        }

        try {
            DB::beginTransaction();
            if ($isCreated) {
                $driver = new Driver();
            }
            $driver->slug = $slug;
            $driver->name = $name;
            $driver->save();
            DB::commit();

            return $this->response(201, [], $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->response(500, ['error' => $e->getMessage()], 'Error while processing');
        }
    }
}
