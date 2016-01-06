<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Entity\Directory;

final class HomeController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        $this->flash->addMessage('info', 'Sample flash message');

        $this->view->render($response, 'home/index.phtml');
        
        return $response;
    }

    public function data(Request $request, Response $response, $args)
    {
        $data = $this->getResource('App\Resource\DirectoryResource')->selectRandom();
        return $response->write(json_encode($data));
    }
    
    public function populate(Request $request, Response $response, $args)
    {
        $data = $this->buildArray();
        return $response->write(json_encode($data));
    }
    
    public function buildArray() 
    {
        $directory  = new \RecursiveDirectoryIterator('assets');
        $iterator   = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);
    
        $tree = [];
        foreach ($iterator as $info) {
            //if (in_array($info->getFilename(), ['.', '..'])) continue;
            if ($iterator->getDepth() >= 2 || !$iterator->isDir() || $iterator->isDot()) {
                continue;
            }
            if ($iterator->getDepth() == 1) {
                $path = $info->isDir() ? [$iterator->getDepth()-1 => $info->getFilename()] : [$info->getFilename()];
            } else {
                $path = $info->isDir() ? [$info->getFilename() => []] : [$info->getFilename()];
            }
            for ($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--) {
                $path = [$iterator->getSubIterator($depth)->current()->getFilename() => $path];
            }
            $tree = array_merge_recursive($tree, $path);
        }
        
        $data = array();
        foreach ($tree as $category => $children) {
            foreach($children as $index => $value) {
                $data[$category][] = $value;
            }
        }

        foreach ($data as $category => $children) {
            $parentId = $this->addEntry($category, '0');
            foreach($children as $index => $value) {
                $this->addEntry($value, $parentId);
            }
        }
    
        return $data;
    }
    
    public function addEntry($name, $parentId)
    {
        $entity = new Directory();
        $entity->setName($name);
        $entity->setParentId($parentId);
        
        $em = $this->getEntityManager();
        
        $em->persist($entity);
        $em->flush();
        
        return $entity->getId();
    }
}
