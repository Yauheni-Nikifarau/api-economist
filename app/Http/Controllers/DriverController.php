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
        return response( DriverResource::collection( Driver::all() ), 200 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: '')
        ]);

        list('slug' => $slug, 'name' => $name) = $request->validate( [
            'slug' => 'nullable|string|max:255|unique:drivers,slug',
            'name'    => 'required|string|max:255'
        ] );

        if (empty($slug)) {
            if (Driver::where('slug', $slug)->exists()) {
                return response('auto slug exists', 422);
            }
        }

        try {
            DB::beginTransaction();
            $driver = new Driver();
            $driver->slug = strtolower($slug);
            $driver->name = $name;
            $driver->save();
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
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return response( DriverResource::collection( $driver ), 200 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: '')
        ]);

        list('slug' => $slug, 'name' => $name) = $request->validate( [
            'slug' => 'nullable|string|max:255|unique:drivers,slug',
            'name'    => 'required|string|max:255'
        ] );

        if (empty($slug)) {
            if (Driver::where('slug', $slug)->exists()) {
                return response('auto slug exists (' . $slug . ')', 422);
            }
        }

        try {
            DB::beginTransaction();
            $driver->slug = strtolower($slug);
            $driver->name = $name;
            $driver->save();
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
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return response('', 204);
    }
}
