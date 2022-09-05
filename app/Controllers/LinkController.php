<?php


namespace App\Controllers;

use App\Config\Controller;
use App\Config\Request;
use App\Helpers\Url;
use App\models\Link;
use App\Validator\LinkValidator;

class LinkController extends Controller
{
    private $link = null;
    public function __construct()
    {
        $auth = new AuthController();
        if(!$auth->isAuthenticated()){
            ErrorController::notAuthorized();
        }
        $this->link = new Link();
    }

    public function store(){
        $url = $_POST['url'] ?? null;
        if(!LinkValidator::link($url)){
            return ErrorController::urlInvalid();
        }
        $shortLink = Url::createShortLink(6);
        while (!is_null($this->link->getLinkByShortLink($shortLink))){
            $shortLink = Url::createShortLink(6);
        }
        if(!$this->link->create($shortLink,$url)){
            return ErrorController::storeLinkFailed();
        }
        $this->redirect(201,'Created',["message"=> "لینک با موفقیت ساخته شد","shortLink"=>"{$_ENV['BASE_URL']}/{$shortLink}"]);
    }

    public function update(Request $request){
        if (is_null($this->link->getLinkById($request->linkId))){
            return ErrorController::pageNotFound();
        }

        $url = $this->getPutArgument();

        if(!LinkValidator::link($url)){
            return ErrorController::urlInvalid();
        }

        $updatedLink = $this->link->update($request->linkId,$url);
        if(is_null($updatedLink)){
            return ErrorController::updateLinkFailed();
        }
        $this->redirect(200,'OK',["message"=>"لینک با موفقیت ویرایش شد" , "shortLink"=>"{}{$updatedLink['short_link']}"]);
    }

    public function delete(Request $request){
        if (is_null($this->link->getLinkById($request->linkId))){
            ErrorController::pageNotFound();
        }
        if(!$this->link->delete($request->linkId)){
            ErrorController::deleteLinkFailed();
        }
        $this->redirect(200,'OK',["message"=>"لینک با موفقیت حذف شد"]);
    }

    private function getPutArgument(){
        $dataInput = file_get_contents("php://input") ?? null;
        $data = [];
        parse_str($dataInput,$data);
        $cleanDataInput = reset($data);
        $matches = [];
        preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $cleanDataInput, $matches);
        return $matches[0];
    }


}