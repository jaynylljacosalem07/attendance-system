<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSheet;
use App\Models\Course;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AttendanceSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Attendance/Index',[
            "sheets" => Event::paginate(10),
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
    public function store(Request $request)
    {
        //
        $request->validateWithBag('addStudents', [
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course' => 'required',
            'year_level' => 'required',
         
         ]);
        //  dd($request);
         $user = Event::create([
            'name' => $request->name,
            'start_time' => ($request->start_time),
            'end_time' => ($request->end_time),
            'course_id' => ($request->course),
            'year_level' => ($request->year_level),
            'created_by' => auth()->id()
         ]);
 
        

         return Redirect::back();
       
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
    public function update(Request $request)
    {
        //
        $request->validateWithBag('addStudents', [
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'course' => 'required',
            'year_level' =>'required',
         
         ]);

         $event = Event::find($request->id);
         $event->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'course_id' => $request->course,
            'year_level' => $request->year_level,
            
            
         ]);
         return Redirect::back();
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
        Event::find($id)->update(["status"=>1]);
        return Redirect::back();

    }
}
