<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 17:07
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Change;
use Monolog\Logger;

class PreUpdateEventListener
{
    function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getEntity();

        $table = get_class($entity);
        $reflection = new \ReflectionClass($entity);
        $rowid = $entity->getId();
        $oldTimestamp = $entity->getTimestamp();

        $now = time();

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $field = $property->getName();
            $changes = Change::getChangesAfter($table, $field, $rowid, $oldTimestamp, $this->logger);

            if (count($changes) === 0) {
                $change = new Change($this->logger);
                $change->setTimestamp($now);
                $change->setTable($table);
                $change->setField($field);
                $change->setRowid($rowid);

                $change->persist();
            } else {
                $this->logger->info("Changes Found for $field");
                $this->logger->info(json_encode($args->getEntityChangeSet()));
                if (isset($args->getEntityChangeSet()[$field])) {
                    $setter = 'set' . ucfirst($field);
                    $entity->{$setter}($args->getOldValue($field));
                }
            }


        }

        $entity->setTimestamp($now);
    }
}