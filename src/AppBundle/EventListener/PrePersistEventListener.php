<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 17:05
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\PreFlushEventArgs;

class PrePersistEventListener
{
    function onPrePersist(PreFlushEventArgs $eventArgs) {

    }
}