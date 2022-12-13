<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSheet;
use App\Models\Course;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id,Request $request)
    {
        //
        // dd(Event::find($id)->sheets()->get()[0]->course->name);
        // dd(AttendanceSheet::whereEventId($id)->event()->get());
        // dd(Event::find($id)->sheets()->with('user')->with('course')->get());
        return Inertia::render('Reports/Index',[
            "data" => Event::find($id)->sheets()->with('user')->with('course')->get(),
            "event" => Event::find($id),
            "course" => Course::all()

        ]);

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
    public function filter($id,Request $request)
    {
     
        $event = Event::find($id)->sheets()->with('user')->with('course')->get();
        $filtered = $event->filter(function ($item) use($request) {
            return in_array($item->user->year_level, $request->year_level);
        });
     
        $filtered =$filtered->filter(function ($item) use($request) {
            return in_array($item->course->id, $request->course);
        });
        // dd( $filtered->values());
        return Inertia::render('Reports/Index',[
            "data" =>$filtered->values(),
            "event" => Event::find($id),
            "course" => Course::all(),
            "filtered" =>$filtered->values(),

        ]);
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
