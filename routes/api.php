<?php

use App\Http\Middleware\EnsureHasTeam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\ActivityController;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/app/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect.'
        ], 401);
    }

    // Create access token
    $accessToken = $user->createToken($request->device_name);

    return response()->json([
        'access' => $accessToken->plainTextToken,
    ]);
});


Route::middleware([
    'auth:sanctum',
    'verified',
    EnsureHasTeam::class
])->group(function () {
    Route::prefix('app')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('app-user');






        //get activities
        //get activity
        //update enrollment
        //create feedback

        //get meetings
        //get meeting
        //update enrollment
        //create feedback
        //get mentors/mentees/parents info
        //get goals
        //get lms stuff
        //get course
        //get course lessons
        //get lesson
        //get enrolled
        //done
        //quiz answer

        // Option 1: Full Resource Routes
        Route::apiResource('activities', ActivityController::class);
    });
});
