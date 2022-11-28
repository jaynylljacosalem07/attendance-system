<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Team;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Inertia::render('Students/Index',[
            "students" => User::whereNull('role')->paginate(10),
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
        // dd($request->course);
        $request->validateWithBag('addStudents', [
            'name' => 'required',
            'password' => 'required|confirmed',
            'email' => 'required|email|unique:users,email|min:8|max:255',
            'parent_name' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'course' => 'required',
            'year_level' => 'required',
         
         ]);
 
         $user = User::create([
             'name' => strip_tags($request->name),
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'parent_name' => strip_tags($request->parent_name),
             'birthday' => strip_tags($request->birthday),
             'address' => strip_tags($request->address),
             'gender' => strip_tags($request->gender),
             'phone_number' => $request->phone_number,
             'course_id' => $request->course,
             'year_level' =>(int) $request->year_level,
             'id_number' => $this->addIdNumber(User::count()+1)
         ]);
 
         $user->ownedTeams()->save(Team::forceCreate([
             'user_id' => $user->id,
             'name' => explode(' ', $user->name, 2)[0]."'s Team",
             'personal_team' => true,
         ]));

         return Redirect::back();
    }

    /**
     * add zeros to id number
     * 
     */
    private function addIdNumber($id)
    {
        $len = strlen($id);
        return str_repeat("0",8-$len) . $id;

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
        $request->validateWithBag('addStudents', [
            'name' => 'required',
            'parent_name' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'course' => 'required',
            'year_level' => 'required',
         
         ]);
         $user = User::find($request->id);
         $user->update([
             'name' => strip_tags($request->name),
             'parent_name' => strip_tags($request->parent_name),
             'birthday' => strip_tags($request->birthday),
             'address' => strip_tags($request->address),
             'gender' => strip_tags($request->gender),
             'phone_number' => $request->phone_number,
             'course_id' => $request->course,
             'year_level' => (int) $request->year_level,
         ]);
         return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
      * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = User::whereId($request->id)->first();
        if($data !== null){
            $data->delete();
            return Redirect::back();
        }
    }
}
