<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('resources')
    ->exclude('storage')
    ->exclude('public')
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers(array('trailing_spaces', 'encoding'))
    ->finder($finder);
