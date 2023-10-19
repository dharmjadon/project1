<?php

namespace App\Http\Controllers\Publisher\dashboard;

use App\Http\Controllers\Controller;
use App\Models\It;
use Illuminate\Http\Request;

class PubITController extends Controller
{

    function __construct()
    {
        $this->middleware('role_or_permission:Admin|publisher-user-it', ['only' =>
      ['index','edit','update','destroy']]);


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function show(It $it)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function edit(It $it)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, It $it)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\It  $it
     * @return \Illuminate\Http\Response
     */
    public function destroy(It $it)
    {
        //
    }
}
