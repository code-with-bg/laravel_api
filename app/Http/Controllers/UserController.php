<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\image;
use Faker\Provider\UserAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psy\Readline\Userland;

class UserController extends Controller
{
    // get all users data through this method 
    // get all users data through this method 
    public function index(){
         $users = User::all();

         return response()->json([
            'message' =>  count($users),
            'data' => $users,
            'status' =>  true
         ]);
    }

    // get the singal user data through this function 
    // get the singal user data through this function 
    public function show($id){
         $users = User::find($id);
     if($users != null){
        return response()->json([
          'message' => 'Record found',
          'data' => $users,
          'status'=> true
        ],200);
     }else{
         return response()->json([
         'message'=> 'Record not found',
          'data' => [],
           'status'=> true

         ],200);
     }
    }

    // create api store data into database 
    // create api store data into database 
    public function store(Request $request){
       $validation =  Validator::make( $request->all(), [
         'name' => 'required',
         'email' => 'required|email',
         'password' => 'required'
        ]);
        if($validation->fails()){
             return response()->json([
                'message' => 'Masum rohan pahele error ko fix karo',
                'errors' => $validation->errors(),
                'status' => false
             ],200);
        }

        $user = new User;
         $user->name = $request->name;
         $user->email = $request->email;
         $user->password = $request->password;
         $user->save();

         return response()->json([
            'message' => 'User added successfully',
            'errors' => $validation->errors(),
            'status' => true
         ],200);

    }


    // Create update api data from database
    // Create update api data from database
public function update(Request $request, $id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if ($user == null) {
        return response()->json([
            'message' => 'User not found',
            'status' => false
        ], 404); // Return a 404 status code for not found
    }

    // Validate the request data
    $validation = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email'
    ]);

    // Check if validation fails
    if ($validation->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validation->errors(),
            'status' => false
        ], 400); // Return a 400 status code for bad request
    }

    // Update the user's name and email
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
        'status' => true
    ], 200);
}

// Create delete data through api from database 
// Create delete data through api from database 
public function destroy($id){
     $user = User::find($id);
     if ($user == null) {
        return response()->json([
            'message' => 'User not found',
            'status' => false
        ], 404); // Return a 404 status code for not found
    }

    $user->delete();
    return response()->json([
        'message' => 'User deleted successfully',
        'status' => true
    ], 200);
}

// upload the images inside database through api 
// upload the images inside database through api 
public function upload(Request $request){
     $validation = Validator::make($request->all(),[
        'image' => 'required|mimes:png,jpg,jpeg,gif'
     ]);
     // Check if validation fails
    if ($validation->fails()) {
        return response()->json([
            'message' => 'Please fix the error message',
            'status' => false,
             'errors' => $validation->errors()
        ], 400); // Return a 400 status code for bad request
    }

      $img = $request->image;
       $ext = $img->getClientOriginalExtension();
       $imageName = time().'.'.$ext; 
       $img->move(public_path().'/uploads/',$imageName);
        $image = new Image;
        $image->image = $imageName;
        $image->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
            'status' => true,
            'path' => asset('uploads/'.$imageName),
            'data' => $image
        ],200);
}
}

