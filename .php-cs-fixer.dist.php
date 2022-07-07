<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('bootstrap')
    ->exclude('storage')
    ->exclude('vendor')
    ->in(__DIR__);

$config = (new PhpCsFixer\Config())
    ->setRules([
                  '@PSR12' => true
              ])
    ->setFinder($finder);

return $config;
