<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Models\Equipment;
use App\Models\Test;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $haveErrors = Equipment::whereRelation('tests', 'tests.successful', '=' , '0')
            ->withCount(['tests as failed_tests' => function(Builder $query) {
                $query->where('successful', false);
            }])
            ->get();

        $doNotHaveErrors = Equipment::whereRelation('tests', 'tests.successful', '=' , '1')
            ->withCount(['tests as done_tests' => function(Builder $query) {
                $query->where('successful', true);
            }])
            ->get();

        $equipmentStatistics = Equipment::has('tests')->with('tests')->get();

        return $this->sendResponse([
            'haveErrors' => $haveErrors,
            'doNotHaveErrors' => $doNotHaveErrors,
            'equipmentStatistics' => $equipmentStatistics,
        ], 'Data loaded');
    }
}
