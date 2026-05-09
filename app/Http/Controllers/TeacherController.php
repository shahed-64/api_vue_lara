<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{   
    return response()->json([
        'status' => true,
        'teachers' => Teacher::all()
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation

      // Validation
    $request->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:teachers,email',
        'age'   => 'required',
        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);


    $photoName = null;

    if ($request->hasFile('photo')) {

        $photo = $request->file('photo');
        $extension = $photo->getClientOriginalExtension();

        $photoName = md5(time() . rand(1000,9999)) . '.' . $extension;

        $photo->move(public_path('media'), $photoName);
    }

        

        $teacher = Teacher::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'age' => $request -> age,
            'photo' => $photoName
        ]);

        return response() -> json([

        'status' => true,
        'message' => 'Teacher Created Successfully',
        'teacher' => $teacher

        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //Single teacher
        return response() -> json([
        'status' => true,
        'teacher' => $teacher
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
{
    $request->validate([
        'name'   => 'required',
        'age'    => 'required',
        'status' => 'required|boolean',
        'photo'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    // Photo update (optional)
    if ($request->hasFile('photo')) {

        // পুরাতন file delete (optional but best practice)
        if ($teacher->photo && file_exists(public_path('media/' . $teacher->photo))) {
            unlink(public_path('media/' . $teacher->photo));
        }

        $photo = $request->file('photo');
        $extension = $photo->getClientOriginalExtension();

        $photoName = md5(time() . rand(1000,9999)) . '.' . $extension;

        $photo->move(public_path('media'), $photoName);

        $teacher->photo = $photoName;
    }

    // Update fields
    $teacher->name   = $request->name;
    $teacher->email  = $request->email;
    $teacher->age    = $request->age;
    $teacher->status = $request->status;

    $teacher->save();

    return response()->json([
        'status'  => true,
        'message' => 'Teacher Updated Successfully',
        'teacher' => $teacher
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
        $teacher -> delete();
        return response() -> json([
        'status' => true,
        'teacher' => $teacher
        ]);
    }
}
