<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 17:43
 */

namespace AppBundle\EventListener;
use Doctrine\ORM\Event\OnFlushEventArgs;

class OnFlushListener
{
    public function onFlush(OnFlushEventArgs $eventArgs) {

    }
}