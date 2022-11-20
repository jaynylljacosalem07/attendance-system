<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Inertia::render('Settings/Index',[
            "school" => School::find(1),
            "courses" => Course::paginate(10),
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // $path = $request->file('logo')->store('logo');
 
      
        $school = School::find($request->id);
        $school->update($request->except(['id','logo']));
        return Redirect::back();
    }

    /**
     * 
     * 
     */

     public function updateCourses(Request $request){
        
        foreach($request->courses as $course ){
            $course = (object) $course;
            if(isset($course->id))
                Course::find($course->id)->update(["name" => $course->name]);
            else
                Course::create(["name" => $course->name]);
        }
        return Redirect::back();
     }

     /**
      * 
      *
      */
     public function deleteCourse(Request $request)
     {
         $data = Course::whereId($request->id)->first();
         if($data !== null){
             $data->delete();
             return Redirect::back();
         }
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
