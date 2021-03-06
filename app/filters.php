<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    //
});


App::after(function($request, $response)
{
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('admin', function()
{
    if (! Sentry::check()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            Session::set('redirect', Request::path());
            return View::make('backend.auth');
        }
    }

    if (! user()->hasAccess('backend')) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('backend.no_access');
        }
    }
});

Route::filter('auth', function()
{
    if (! Sentry::check()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            Session::set('redirect', Request::path());
            return Redirect::to('auth/login');
        }
    }
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::token() !== Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }

    /* 
     * Spam protection: Forms that have set a value for _created_at
     * are protected against mass submitting.
     * WARNING: Not sending the field will not trigger the verification!
     */
    if ($time = Input::get('_created_at'))
    {
        $time = Crypt::decrypt($time);

        if (is_numeric($time)) {
            $time = (int) $time;
            
            if ($time <= time() - 3) return;
        }

        throw new MsgException(trans('app.spam_protection'));
    }
});