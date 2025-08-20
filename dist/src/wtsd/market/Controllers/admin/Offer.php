<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\market\Offer as cOffer;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Offer extends AdminController
{

    public function run()
    {
        $action = Request::parseUrl(2);
        if (in_array($action, array('browse', 'lst', 'listing', ''))) {
            return $this->listing();
        } elseif (in_array($action, array('frm', 'edit', 'form', 'add'))) {
            return $this->form();
        }
    }

    public function listing()
    {
        $obj = new cOffer();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        
        $filters = [
            'q' => Request::getGet('q'),
            'pcat_id' => Request::getGet('pcat_id'),
            'sortby' => Request::getGet('sortby'),
            'sortdir' => Request::getGet('sortdir'),
        ];
        $contentsArray = $obj->lst($page, $filters);
        
        $contentsArray['q'] = $filters['q'];
        $contentsArray['pcat_id'] = $filters['pcat_id'];

        $pcategory = new \wtsd\market\PCategory();
        $contentsArray['pcats'] = $pcategory->getAll();

        $this->template = 'market/lst-offer.tpl';
        return $contentsArray;
    }
    
    public function form($id = 0)
    {
        $obj = new cOffer();

        $id = Request::parseUrl(3);

        $pcategory = new \wtsd\market\PCategory();
        $vendor = new \wtsd\market\Vendor();

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['pcategories'] = $pcategory->getAll();;
        $contentsArray['vendors'] = $vendor->getAll();;
        $contentsArray['c_type'] = 'offer';
        $contentsArray['id'] = $id;

        $contentsArray['specs'] = [];//\wtsd\market\PSpec::getSpecsByPCat($contentsArray['obj']['pcat_id']);

        if ($id > 0) {
            $contentsArray['specs_info'] = \wtsd\market\PSpec::getSpecValsByOffer($id);
        }

        $contentsArray['photos'] = $obj->getPhotos($id);

        $this->template = 'market/frm-offer.tpl';
        return $contentsArray;
    }
            
    public function save()
    {
        $obj = new cOffer();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cOffer();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
}