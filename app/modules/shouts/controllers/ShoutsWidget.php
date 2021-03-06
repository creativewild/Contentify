<?php namespace App\Modules\Shouts\Controllers;

use App\Modules\Shouts\Models\Shout;
use View, Widget;

class ShoutsWidget extends Widget {

    public function render($parameters = array())
    {
        $shouts = Shout::orderBy('created_at', 'desc')->with('creator')->take(10)->get();
        
        return View::make('shouts::widget', compact('shouts'))->render();
    }

}