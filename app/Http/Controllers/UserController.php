<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  /**
   * Display the specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getLectures(Request $request)
  {
    if ($valid = $this->superValidation(
      $request,
      ['level' => 'numeric']
    )) {
      return $valid;
    }
    $level = $request->level ?? 3;
    $lectures = User::find(Auth::user()->id)->lectures()->wherePivot('level', '=', $level)->get();

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
  public function joinLecture(Request $request)
  {
    if ($valid = $this->superValidation(
      $request,
      [
        'code'  => 'required',
        'level' => 'numeric',
      ]
    )) {
      return $valid;
    }

    $lecture = Lecture::where('code', '=', $request->code)->firstOrFail();
    $user = User::find(Auth::user()->id);
    if (!($level = $user->lectures()->where('lectures.id', $lecture->id)->first())) {
      $user->lectures()->attach($lecture->id, ['level' => $request->level]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'already joined the lecture as ' . Lecture::LEVEL_DESC[$level->pivot->level],
        'data'    => $lecture,
      ], Response::HTTP_BAD_REQUEST);
    }

    return response()->json([
      'success' => true,
      'message' => 'post data success',
      'data'    => $lecture,
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function leaveLecture(Request $request)
  {
    if ($valid = $this->superValidation(
      $request,
      ['code' => 'required']
    )) {
      return $valid;
    }

    $user = User::find(Auth::user()->id);
    $lecture = Lecture::where('code', '=', $request->code)->firstOrFail();
    $user->lectures()->detach($lecture->id);

    return response()->json([
      'success' => true,
      'message' => 'post data success',
      'data'    => null,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateLevel(Request $request, $id)
  {
    if ($valid = $this->superValidation(
      $request,
      ['level' => 'required|numeric']
    )) {
      return $valid;
    }

    $user = User::find(Auth::user()->id);
    $user->lectures()->updateExistingPivot($id, ['level' => $request->level]);

    return response()->json([
      'success' => true,
      'message' => 'put/patch data success',
      'data'    => $user->lectures,
    ]);
  }
}
