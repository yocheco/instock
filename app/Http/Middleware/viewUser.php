<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Follower;

class viewUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //extraemos los parametros necesarios
        $cadena = explode('/', $request->path(), 3);
        //id del usuario que se quiere ver
        $userView_id = $cadena[2];
        //user log
        $user = $request->user();

        //saber si el usuario es el mismo
        if($user->id == $userView_id) return $next($request);

        //user view
        $userView = User::findOrFail($userView_id);

        //detectar si el perfil es publico o no 
        if(!$userView->isPrivte()) return $next($request);

        //si es perfil privado, buscamos una relacion de follower con el usuario a ver 
        $follower = Follower::where('user_follower_id',$user->id)->where('user_following_id',$userView_id)->first();

        //si hay una relacion vemos si esta aceotada o no 
        if($follower){
            //si esta acepatada continua la peticion
            if($follower->isAcepted()) return $next($request);

            //si no es aceptado se rechaza 
            dd('su perfil es privado pero no ha aceptado tu solicidud');
        }

        //se rechasa el ver al usuario poque no hay ninguna peticion de follower
        dd('no se ha enviado solicitud de amistad');
        //return $next($request);
    }
}
