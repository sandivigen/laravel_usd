<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sleep2;

class SleepController2 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $take_data_totime = strtotime('-7 day', time()); // возьмем даты 7 дней назад в unix коде
        $take_data = strftime("%Y-%m-%d", $take_data_totime); // приведем unix код к формату

        $events = Sleep2::where('start_date', '>', $take_data)->orderBy('start_date')->get();

        return view('sleep2', array(
            'events' => $events,
            'title' => 'sleep2'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $heading = 'Добавить сон';
        return view('create_sleep2', array('heading' => $heading));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
        $event = new Sleep2;



        $event->start_date = $request->input('start-date');
        $event->end_date = $request->input('end-date');
        $event->start_time = $request->input('start-time');
        $event->end_time = $request->input('end-time');
        $event->event_category = $request->input('event-category');
        $event->save();

        return back();

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
