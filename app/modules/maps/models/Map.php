<?php namespace App\Modules\Maps\Models;

use SoftDeletingTrait, BaseModel;

class Map extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'game_id'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => 16]];

    protected $rules = [
        'title'     => 'required|min:3',
        'game_id'   => 'required|integer',
    ];

    public static $relationsData = [
        'game'      => [self::BELONGS_TO, 'App\Modules\Games\Models\Game'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}