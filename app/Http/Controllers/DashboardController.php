<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSheet;
use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Inertia::render('Dashboard/Index',[
            "events" => Event::whereNull('status')->paginate(10)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // dd($request);
        
       $validator = Validator::make([],[]);

        // Add fields and errors
      
        $student = User::where("id_number",$request->id_number)->first();
        
        $check = AttendanceSheet::where([
            "id_number" => $request->id_number,
            "event_id" => $request->id
        ])->first();
        if($check !== null){
            $array_str = [];
                    
            if($check[$request->mode] == null)
            {
                $tmp = array_fill(0,$request->index+1,'');
                $tmp[$request->index] = $request->time;
                $array_str = [$request->mode => $tmp];
            }else{
                
                $tmp =  $check->out;
                // dd(array_key_exists($request->index, $tmp));
                if(array_key_exists($request->index, $tmp) === false)
                    $tmp[$request->index] = '';
                if(strlen($tmp[$request->index]) > 0)
                {
                    $validator->errors()->add('message', 'This entry is already recorded! Please try again. Thank you.');
                    throw new \Illuminate\Validation\ValidationException($validator);
                }  
                else{
                    $tmp[$request->index] = $request->time;
                    $array_str = [$request->mode=> $tmp];
                }

            }
        
            $check->update($array_str);

        }else{
            $tmp = array_fill(0,  $request->index+1, '');
            $tmp[$request->index] = $request->time;
            AttendanceSheet::create([
                "id_number" => $request->id_number,
                "event_id" => $request->id,
                $request->mode => $tmp,
                "user_id" => $student->id,
                "course_id" => $student->course_id,
                "year_level_id" => $student->year_level,
            ]);
        }

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
    }
}
