<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 17:05
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Change;
use Doctrine;
use Monolog\Logger;

class PostPersistEventListener
{
    private $logger;
    function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    function postPersist(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof Change) {
            $change = new Change($this->logger);
            $refection = new \ReflectionClass($entity);
            $props = $refection->getProperties(\ReflectionProperty::IS_PRIVATE);
            $class = get_class($entity);
            $rowid = $entity->getId();

            foreach ($props as $prop) {
                $field = $prop->getName();

                $change->setTimestamp($entity->getTimestamp());

                $change->setTable($class);
                $change->setField($field);
                $change->setRowid($rowid);

                $change->persist();
            }
        }
    }
}