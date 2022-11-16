<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipmentList = Equipment::all();

        return $this->sendResponse($equipmentList, '');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ip' => 'required|ipv4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $equipment = Equipment::create([
            'name' => $request->get('name'),
            'ip' => $request->get('ip'),
            'user_id' => Auth::user()->id,
        ]);

        return $this->sendResponse($equipment, 'Equipment created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipment = Equipment::find($id);

        return $this->sendResponse($equipment, 'Data loaded');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ip' => 'required|ipv4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $equipment = Equipment::find($id)->update([
            'name' => $request->get('name'),
            'ip' => $request->get('ip'),
        ]);

        return $this->sendResponse($equipment, 'Equipment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = Equipment::with('tests')->find($id);

        if (count($equipment->tests) === 0) {
            $equipment->delete();

            return $this->sendResponse($equipment, 'Equipment deleted successfully');
        }

        return $this->sendError('Cannot delete equipment because it has tests', [], 400);
    }
}
