<?php

namespace TinyPress\Adapters;

use TinyPress\Interfaces\ViewInterface;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use TinyPress\Services\ContainerService;

class SymfonyTemplatingAdapter implements ViewInterface {

    private $_engine;

    private $_vars = [];

    public function __construct() {

        $this->_engine = new PhpEngine(
            new TemplateNameParser(),
            new FilesystemLoader( BASE_PATH . '%name%' )
        );

        $this->_engine->addGlobal( '__assets', new PathPackage( '/', new EmptyVersionStrategy() ) );
        $this->_engine->addGlobal( '__session', ContainerService::get( 'core' )->get( 'session' ) );
        $this->_engine->addGlobal( '__helper', ContainerService::get( 'core' )->get( 'viewhelper' ) );
        $this->_engine->addGlobal( '__view', $this );

    }

    public function render( $template, array $vars = [] ) {

        $this->_vars = array_merge( $this->_vars, $vars );
        return $this->_engine->render( $template, $this->_vars );

    }

    public function addGlobal( $key, $value ) {

        $this->_engine->addGlobal( $key, $value );

    }

}
