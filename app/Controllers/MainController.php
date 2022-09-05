<?php


namespace App\Controllers;


use App\Config\Request;
use App\Helpers\Url;
use App\models\Link;

class MainController
{
    private $link = null;

    public function __construct()
    {
        $this->link = new Link();
    }

    public function index(Request $request)
    {
        $savedLink = $this->link->getLinkByShortLink($request->link);
        if (!is_null($savedLink)){
            return Url::redirectToUrl($savedLink['url']);
        }
        return ErrorController::pageNotFound();
    }
}