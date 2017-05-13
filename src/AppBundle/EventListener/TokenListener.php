<?php
namespace AppBundle\EventListener;
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 13:05
 */
use Symfony\Component\Security\Acl\Exception\Exception;
use AppBundle\Interfaces\TokenAuthtentifiedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use JWT;

class TokenListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthtentifiedController && $event->getRequest()->getPathInfo() !== '/users/authenticate') {
            $token = $event->getRequest()->headers->get('x_access_token');
            if (!$token) {
                throw new AccessDeniedHttpException('This action needs a valid token!');
            } else {
                try {
                    $decoded = JWT::decode($token, 'ilovecocks', ['HS256']);
                    $event->getRequest()->attributes->set("user", $decoded->username);
                } catch (Exception $e) {
                    throw new AccessDeniedHttpException('This action needs a valid token!');
                }
            }
        }


    }
}