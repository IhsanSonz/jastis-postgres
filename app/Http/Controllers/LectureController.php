<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $lectures = Lecture::with('users')->latest()->get();

    return response()->json([
      'success' => true,
      'message' => 'get data success',
      'data'    => $lectures,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ($valid = $this->superValidation(
      $request,
      Lecture::VALIDATION_RULES + ['user_id' => 'numeric']
    )) {
      return $valid;
    }

    $faker = Faker::create();
    $code = \Str::random(5);
    $user_id = $request->user_id ?? Auth::user()->id;

    $lecture = new Lecture;
    $lecture->fill($request->all() + [
      'code'  => $code,
      'color' => ltrim($faker->hexcolor, '#'),
    ]);
    $lecture->save();
    $lecture->users()->attach($user_id, ['level' => 1]);

    return response()->json([
      'success' => true,
      'message' => 'post data success',
      'data'    => $lecture,
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
    $lectures = Lecture::with('users')->find($id);

    return response()->json([
      'success' => true,
      'message' => 'get data success',
      'data'    => $lectures,
    ]);
  }

  /**
   * Display the lecturers of the lecture.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function getLecturers($id)
  {
    $lecturers = Lecture::find($id)->users()->wherePivot('level', '=', '1')->get();

    return response()->json([
      'success' => true,
      'message' => 'get data success',
      'data'    => $lecturers,
    ]);
  }

  /**
   * Display the students of the lecture.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function getStudents($id)
  {
    $students = Lecture::find($id)->users()->wherePivot('level', '=', '3')->get();

    return response()->json([
      'success' => true,
      'message' => 'get data success',
      'data'    => $students,
    ]);
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
    if ($valid = $this->superValidation(
      $request,
      Lecture::VALIDATION_RULES
    )) {
      return $valid;
    }

    $lecture = Lecture::find($id);
    $lecture->fill($request->only(['name', 'desc']));
    $lecture->save();

    return response()->json([
      'success' => true,
      'message' => 'post data success',
      'data'    => $lecture,
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $lecture = lecture::findOrFail($id);
    $lecture->users()->detach();
    $lecture->delete();

    return response()->json([
      'success' => true,
      'message' => 'delete data success',
      'data'    => null,
    ]);
  }
}
