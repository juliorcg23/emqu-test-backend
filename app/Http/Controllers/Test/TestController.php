<?php

namespace App\Http\Controllers\Test;
use App\Http\Services\TestService;

use App\Http\Controllers\BaseController;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testList = Test::with('equipment')->get();

        return $this->sendResponse($testList, 'Test list retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TestService $testService)
    {
        $validator = Validator::make($request->all(), [
            'equipment.id' => 'required',
            'equipment.ip' => 'required|ipv4',
            'attempts' => 'numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $ip = $request->get('equipment')['ip'];
        $equipmentId = $request->get('equipment')['id'];
        $attempts = $request->get('attempts');

        $testResult = $testService->ping($ip)->setAttempts($attempts)->run();
        $testResult->raw = '';
        $testResult->sequence = '';

        $averageLatency = property_exists($testResult, 'latency') ? $testResult->latency * 1000 : 0;
        $minLatency = property_exists($testResult, 'rtt') ? $testResult->rtt->min * 1000 : 0;
        $maxLatency = property_exists($testResult, 'rtt') ? $testResult->rtt->max * 1000 : 0;
        $attemptsDone = property_exists($testResult, 'statistics') ? $testResult->statistics->packets_transmitted : 0;
        $attemptsFailed = property_exists($testResult, 'statistics') ? $testResult->statistics->packets_lost : 0;
        $successful = $testResult->host_status === 'Ok';

        $test = new Test();
        $test->average_latency = $averageLatency;
        $test->minimum_latency = $minLatency;
        $test->maximum_latency = $maxLatency;
        $test->attempts = $attemptsDone;
        $test->failed_attempts = $attemptsFailed;
        $test->equipment_id = $equipmentId;
        $test->successful = $successful;
        $test->save();

        return $this->sendResponse($test, 'Test done successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
