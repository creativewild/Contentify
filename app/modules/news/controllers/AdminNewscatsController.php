<?php namespace App\Modules\News\Controllers;

use ModelHandlerTrait;
use App\Modules\News\Models\Newscat;
use Hover, BackController;

class AdminNewscatsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'newspaper-o';

    public function __construct()
    {
        $this->modelName = 'Newscat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function($newscat)
            {
                Hover::modelAttributes($newscat, ['image', 'creator']);

                return [
                    $newscat->id,
                    raw(Hover::pull(), $newscat->title)
                ];
            }
        ]);
    }

}