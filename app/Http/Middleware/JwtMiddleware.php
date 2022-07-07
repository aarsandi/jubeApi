<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();
        if(!$token) { return response()->json([ 'code' => 401, 'status' => 'error', 'data' => 'Unauthorized' ],401); }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $user = User::where('token', '=', $token)->get(); #jwt_edited
            if(count($user)) {        
                $request->auth = $user;
                return $next($request);

            }else{
                return response()->json([
                    'code' => 401,
                    'status' => 'error',
                    'data' => 'Unauthorized'
                ],401);
            }

        } catch(ExpiredException $e) {
            return response()->json([ 'code' => 401, 'status' => 'error', 'data' => 'Token is expired.' ],401);

        } catch(Exception $e) {
            return response()->json([ 'code' => 401, 'status' => 'error', 'data' => 'Unauthorized' ],401);
        }
    }
}