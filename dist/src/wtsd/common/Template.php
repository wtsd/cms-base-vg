<?php
namespace wtsd\common;

use wtsd\common\Register;
/**
* Defines the templating mechanism to avoid engine dependency so it could be
* changed only in one place, when it`s needed.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Template
{
    protected $engine;
    protected $template;
    protected $engineType;

    
    public function __construct($template = '', $engineType = 'Smarty')
    {
        $this->tmp_dir = ROOT . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
        $this->view_dir = ROOT . DIRECTORY_SEPARATOR;// . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
        
        $this->engineType = $engineType;

        if ($this->isAvailable($template)) {
            $this->setTemplate($template);
        } else {
            $this->setTemplate($this->getDefaultTemplate());
        }

        $this->init();

    }
    
    protected function init()
    {
        if ($this->engineType == 'Smarty') {
            $this->engine = new \Smarty();
            $this->engine->caching = 0;
            $this->engine->config_dir = $this->view_dir . 'config';
            $this->engine->compile_dir = $this->tmp_dir;
            $this->engine->template_dir = $this->view_dir . $this->template;
            $this->engine->cache_dir = $this->tmp_dir;
        }
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    protected function isAvailable($template)
    {
        return (file_exists($this->view_dir . $template)) && (is_dir($this->view_dir . $template));
    }

    public function getDefaultTemplate()
    {
        $config = Register::get('config');

        return $config['template'];
    }

    public function get()
    {
        return $this->engine;
    }
    
    public function assignAll($content)
    {
        if (is_array($content)) {
            foreach ($content as $field => $value) {
                $this->engine->assign($field, $value);
            }
        }
    }

    public function assign($field, $value)
    {
        $this->engine->assign($field, $value);
    }
        
    public function fetch($page)
    {
        return $this->render($page);
    }
    
    public function render($page)
    {
        return $this->engine->fetch($page);
    }
    
    public function display($page)
    {
        $this->engine->display($page);
    }
}
