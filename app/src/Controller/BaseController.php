<?php

namespace App\Controller;

use Slim\Container;

class BaseController
{
    protected $view;

    protected $logger;

    protected $flash;

    protected $em;

    public function __construct(Container $c)
    {
        $this->view     = $c->get('view');
        $this->logger   = $c->get('logger');
        $this->flash    = $c->get('flash');
        $this->em       = $c->get('em');
    }
    
    public function getEntityManager()
    {
        return $this->em;
    }
    
    public function getResource($resource)
    {
        return new $resource($this->em);
    }
    
}
