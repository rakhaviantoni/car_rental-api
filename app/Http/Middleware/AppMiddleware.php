<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Modules\v1\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AppMiddleware  extends Controller
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('x-access-token');
        if(!$request->header('x-access-token')) {
            return $this->response('Unauthorized',[],401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            if(!empty($credentials->expired)){
                if($credentials->expired < time()){
                    return $this->response('Token expired',[],403);
                }
            }
            else{
                return $this->response('Unauthorized',[],401);
            }
        } catch(ExpiredException $e) {
            return $this->response('Token expired',[],403);
        } catch(Exception $e) {
            return $this->response('Unauthorized',[],400);
        }
        
        $user = User::select(array(
                    'users.id',
                    'users.username',
                    'users.name',
                    'users.user_role_id',
                    'users.relation_code'
                ))
                ->where('users.id','=',$credentials->user_id)
                ->first();
        if($user){
            $request->user = $user;
            return $next($request);
        }
        else{
            return $this->response('Cannot find any user.',[],401);
        }
        
    }
}