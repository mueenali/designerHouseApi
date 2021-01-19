<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param int $ttl
     * @return mixed
     */
    public function handle($request, Closure $next, $ttl)
    {

        if($request->isMethod('post') || $request->isMethod('put')
            || $request->isMethod('delete'))
        {
            return $next($request);
        }

        $params = $request->query();
        unset($params['_method']);
        ksort($params);
        $key = md5(url()->current().'?'.http_build_query($params));

        if($request->get('_method') === 'purge')
        {
            Cache::forget($key);
        }

        if(Cache::has($key)){
            $cache = Cache::get($key);
            $response = response($cache['content']);
            $response->header('X-Proxy-Cache', 'HIT');
        } else {
            $response = $next($request);
            if(isset($response)){
                Cache::put($key, ['content' => $response->content()], $ttl);
            }
            $response->header('X-Proxy-Cache', 'MiSS');
        }
      return $response;
    }
}
