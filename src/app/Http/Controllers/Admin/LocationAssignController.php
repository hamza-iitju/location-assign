<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LocationAssign;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LocationAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('location_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $locations = LocationAssign::all();

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('location_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location = LocationAssign::create($request->all());
        return redirect()->route('admin.locations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LocationAssign  $location
     * @return \Illuminate\Http\Response
     */
    public function show(LocationAssign $location)
    {
        abort_if(Gate::denies('location_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LocationAssign  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(LocationAssign $location)
    {
        abort_if(Gate::denies('location_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LocationAssign  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LocationAssign $location)
    {
        $location->update($request->all());

        return redirect()->route('admin.locations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LocationAssign  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(LocationAssign $location)
    {
        abort_if(Gate::denies('location_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $location->delete();

        return back();
    }

    public function massDestroy(MassDestroyLocationRequest $request)
    {
        Folder::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function allLocations()
    {
        $locations = LocationAssign::pluck('location');
        return view('admin.locations.show-all', compact('locations'));
    }
}
