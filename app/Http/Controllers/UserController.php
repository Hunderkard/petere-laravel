<?php

namespace App\Http\Controllers;
//ti USE
    use App\Models\User;
use Exception;
use Illuminate\Http\Request;

    //el Contraseñas, validadores y los JWT.
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
//ti --

class UserController extends Controller
{

//hi URL /login.
public function login(Request $request) {
    
    $credentials = $request->only('email', 'password');

    try {
        $usuario = User::whereEmail($credentials['email'])->first();
        $misClaims = [
                        'level' => $usuario->level,
                        'nombre' => $usuario->name
        ];


        if (! $token = JWTAuth::claims($misClaims)->attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }   //ps Intenta crear el token.

    catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }   //ps Error 500, no se pudo crear.

    // return response()->json(compact('token'));
    return $this->respondWithToken($token);
    
}

//ps Devuelve los datos del usuario, pero estoy metiendo la información necesaria en los JWTs.
// public function getAuthenticatedUser(){
//     try {
//         if (!$user = JWTAuth::parseToken()->authenticate()) {
//                 return response()->json(['user_not_found'], 404);
//         }
//     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
//             return response()->json(['token_expired'], $e->getStatusCode());
//     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
//             return response()->json(['token_invalid'], $e->getStatusCode());
//     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
//             return response()->json(['token_absent'], $e->getStatusCode());
//     }
//         return response()->json(compact('user'));
// }

//hi URL /register.
//fu Esta función sólo creará el usuario. 
//fu No funciona bien cuando devuelve token para iniciar sesión.
public function register(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]); //ps Esto lo comprobaré en Angular, pero nunca viene mal otro vistazo.

    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }

    $user = User::create([
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
        'level' => 2
    ]);

    $token = JWTAuth::fromUser($user);

    // return response()->json(compact('token'));
    return $this->respondWithToken($token);
}

//AUXILIARES
    protected function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 120
        ]);
    }
//--

//hi GET usuarios
public function index(){return User::all();}

//hi POST usuarios
public function store(Request $req){
    $usu = new User;
    $usu->name = $req->name;
    $usu->email = $req->email;
    $usu->level = $req->level;
    $usu->password = Hash::make($req->password);
    $usu->save();
}
//hi GET usuarios/{id}
public function show($id){return User::find($id);}

//hi PUT usuarios/{id}
public function update(Request $req, $id){
    $usu = User::find($id);
    $usu->name = $req->name;
    $usu->mesa = $req->mesa;
    $usu->email = strtolower($req->email);
    $usu->level = $req->level;
    // $usu->password = Hash::make($req->password);
    $usu->save();
}
//hi PUT sentar
public function sentar(Request $req){
    $usuario = User::where('name', $req->name)->get()[0];
    $usuario->mesa = $req->mesa;
    $usuario->save();
}

public function mesa_de(Request $req){
    try{
    $mesa = User::where('name', $req->nombre_usuario)->get()[0]->mesa;
      return response()->json([$mesa]);
    } 
    catch(Exception  $e){
          return response()->json([null]);
    }
  
}

//hi DELETE usuarios/{id}
public function destroy($id){User::find($id)->delete();}
}
