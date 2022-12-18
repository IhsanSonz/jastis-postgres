<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register', 'unauthorized']]);
  }

  /**
   * Get a JWT token via given credentials.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    if ($valid = $this->superValidation($request, User::VALIDATION_RULES)) {
      return $valid;
    }
    $credentials = $request->only('email', 'password');
    $token = Auth::attempt($credentials);
    if (!$token) {
      return $this->unauthorized();
    }

    $user = Auth::user();
    return response()->json([
      'success' => true,
      'message' => 'Successfully logged in',
      'token'   => $token,
      'data'    => [
        'user' => $user,
      ],
    ]);

  }

  /**
   * Create New User then get a JWT token via given credentials.
   *
   * @param  \Illuminate\Http\Request  $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request)
  {
    $rules = User::VALIDATION_RULES;
    $rules['name'] = 'required|string|max:255';
    $rules['email'] .= '|unique:users';
    if ($valid = $this->superValidation($request, $rules)) {
      return $valid;
    }

    $user = User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = Auth::login($user);
    return response()->json([
      'success' => true,
      'message' => 'User created successfully',
      'token'   => $token,
      'data'    => [
        'user' => $user,
      ],
    ]);
  }

  /**
   * Log the user out (Invalidate the token)
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    Auth::logout();
    return response()->json([
      'success' => true,
      'message' => 'Successfully logged out',
    ]);
  }

  /**
   * Get the authenticated User
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function profile()
  {
    $user = Auth::user();

    return response()->json([
      'success' => true,
      'message' => 'get data success',
      'data'    => [
        'user' => $user,
      ],
    ]);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return response()->json([
      'success' => true,
      'message' => 'Token refreshed',
      'token'   => Auth::refresh(),
      'data'    => [
        'user' => Auth::user(),
      ],
    ]);
  }

  /**
   * Return unauthorized message.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function unauthorized()
  {
    return response()->json([
      'success' => false,
      'message' => 'unauthorized',
    ], Response::HTTP_UNAUTHORIZED);
  }
}
