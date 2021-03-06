<?php

namespace Chursina\Titlor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class Title extends Model
{
    protected $table = 'titles';

    public $timestamps = false;
    protected $fillable = ['uri', 'title'];

    /**
     * @return array - registered uris
     */
    public static function getAvailableUris()
    {
        $availableUris = array();
        $routes = Route::getRoutes();
        foreach($routes as $route) {
            // exclude titlor route and post routes
            if($route->uri() !== 'titlor' && $route->getMethods()[0] !== 'POST') {
                array_push($availableUris, '/'.ltrim($route->uri(), '/'));
            }
        }

        return $availableUris;
    }

    /**
     * @param $uri
     * @return bool - if exists
     */
    public static function uriExists($uri)
    {
        $routes = Route::getRoutes();
        $request = Request::create($uri);
        try {
            $routes->match($request);
            return true;
        }
        catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e){
            return false;
        }
    }

    /**
     * @param $uri
     * @return mixed - title for uri
     */
    public static function getTitleByUri($uri)
    {
        $title = Title::where('uri', '=', $uri)->first();
        if(!$title) {
            return '';
        }
        return $title['title'];
    }
}