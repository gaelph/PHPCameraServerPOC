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

class PrePersistEventListener
{
    function onPrePersist(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof Change) {
            $change = new Change();
            $refection = new \ReflectionClass($entity);
            $props = $refection->getProperties($entity);
            $class = get_class($entity);
            $rowid = $entity->getId();

            foreach ($props as $prop) {
                $field = $prop->getName();

                $change->setTimestamp($entity->getTimestamp());

                $change->setTable($class);
                $change->setField($field);
                $change->setRowid($rowid);
            }
        }
    }
}