<?php

namespace phcode\infra;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
 
class EntityManagerCreator
{

    private $teste;

    public static function getEntityManager()
    {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        //$useSimpleAnnotationReader = false;
        // para configurar por atribuites
        $config = ORMSetup::createAttributeMetaDataConfiguration(
                    [__DIR__."/src"], 
                    $isDevMode, 
                    $proxyDir, 
                    $cache
                );
        // se quiser configurar com comentarios /** @variavel = aaaa */
        //$config = ORMSetup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
        // or if you prefer YAML or XML
        // $config = ORMSetup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
        // $config = ORMSetup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../database/db.sqlite',
        );

        // obtaining the entity manager
        return EntityManager::create($conn, $config);
    }

}