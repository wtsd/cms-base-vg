<?php
$finder = PhpCsFixer\Finder::create()->in([__DIR__.'/dist/src', __DIR__.'/dist/web'])->exclude('vendor');
return (new PhpCsFixer\Config())->setRules(['@PSR12'=>true]);
