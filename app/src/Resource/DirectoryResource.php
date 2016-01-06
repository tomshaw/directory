<?php

namespace App\Resource;

use App\Resource\AbstractResource;

class DirectoryResource extends AbstractResource
{
    public function getQueryForResources()
    {        
        $em = $this->getEntityManager();
    
        $query = $em->createQuery('
            SELECT p.id, p.name, p.parentId 
            FROM App\Entity\Directory p 
            ORDER BY p.name DESC
        ');
    
        return $query->getResult();
    }
    
    public function selectRandom($amount = 20)
    {
        $em = $this->getEntityManager();
        
        $rows = $em->createQuery('SELECT COUNT(u.id) FROM App\Entity\Directory u')->getSingleScalarResult();
        
        $offset = max(0, rand(0, $rows - $amount - 1));
        
        $query = $em->createQuery('
            SELECT u.id, u.name, p.name AS parent_name 
            FROM App\Entity\Directory u 
            JOIN u.parentId p
            WHERE u.parentId > 0
        ')->setMaxResults($amount)->setFirstResult($offset);
        
        return $query->getArrayResult();
    }
    
    public function getCategories()
    {
        $em = $this->getEntityManager();
    
        $query = $em->createQueryBuilder()
            ->select('d')
            ->from('App\Entity\Directory', 'd')
            ->where('d.parentId = :parent')
            ->setParameter('parent', 0);
    
        return $query->getQuery()->getResult();
    }
    
    public function findAll()
    {
        return $this->getEntityManager()->getRepository('App\Entity\Directory')->findAll();
    }
    
    public function getQueryForSlug($slug)
    {
        return $this->getEntityManager()->getRepository('App\Entity\Directory')->findOneBy(array('name' => $slug));
    }
}
