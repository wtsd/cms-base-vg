<?php
namespace wtsd\common\Controllers\Admin\Ajax;

use wtsd\common\Controllers\General\Ajax;
use wtsd\common\Request;
use wtsd\common\Template;
use wtsd\common\Market\PSpec;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Spec extends Ajax
{

    public function run()
    {
        $action = Request::parseUrl(3);
        $result = [];
        if ($action == 'getByPCat') {
            $pcat_id = Request::getPost('pcat_id');

            $result['status'] = 'ok';
            $result['specs'] = PSpec::getSpecsByPCat($pcat_id);
            
            $view = new Template('admin');
            $view->assign('specs', $result['specs']);

            $result['spec_html'] = $view->render('includes/specs.tpl');
        }
        $this->format = 'json';

        return $result;
    }
    
}
