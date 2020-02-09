<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class DefaultMiddleware  extends Controller
{
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->header('x-api-key') != env('API_KEY')) {
            return $this->response('Invalid API Key',[],401);
        }
        return $next($request);
    }
}