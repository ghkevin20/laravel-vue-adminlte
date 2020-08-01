<?php

namespace App\Http\Controllers\API;

use App\ActivityLog;
use App\Helpers\Datatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin role always granted in all permissions
         * Check User if there's a permission
         */
//        $this->middleware('permission:Browse Activity Log', ['only' => ['index']]);
//        $this->middleware('permission:View Activity Log', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatable::make(
            ActivityLog::class,
            ['id', 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'created_at'], // searchFields
            ['id', 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'created_at'], // allowedFields
            ['id', 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'causer_type', 'created_at'], // allowedSorts
            [] // allowedAppends
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ActivityLog::with(['subject','causer'])->findOrFail($id);

        return response([
            'data' => $data
        ], 200);
    }

}
