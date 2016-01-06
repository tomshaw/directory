<?php

namespace App\Resource;

use Doctrine\ORM\EntityManager;

abstract class AbstractResource
{
    protected $entityManager = null;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
