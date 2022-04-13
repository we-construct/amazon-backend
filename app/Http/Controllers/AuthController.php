<?php
namespace App\Http\Controllers;
use App\Jobs\SendEmailJob;
use App\Mail\SendEmail;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Cookie;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login','confirmEmail','register','forgotPassword']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $email = $request->email;
        $isUserExist = User::where('email', $email)->first();
        if ($isUserExist && $isUserExist->email_verified_at === null) {
            $isUserExist->delete();
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $magicCode =strval(rand(1,9999));
        Storage::put('my_info/'.$request->email, $magicCode);


        $confirmationCodes = [$magicCode,strval(rand(1000,9999)),strval(rand(1000,9999))];
        SendEmailJob::dispatch($magicCode, $email)->delay(now()->addSeconds(55));
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'confirmationCodes'=>$confirmationCodes,
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function confirmEmail(Request $request,$id)
    {
       $code = Storage::get('my_info/'.$request->email);
       $confirmed = boolval($request->code == ($code));
       $user = User::find($id);
       if ($confirmed) {
           $user->update([
              'email_verified_at' => Carbon::now()
           ]);

           return response()->json(['confirmed' => true]);
       } else {
           $user->delete();
           return \response()->json(['message' => 'Try to register again','confirmed' => false]);
       }

    }
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function forgotPassword(Request $request){

    }
}
