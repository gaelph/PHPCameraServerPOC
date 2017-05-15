<?php
namespace AppBundle\Controller;
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 10:41
 */

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use AppBundle\Interfaces\TokenAuthtentifiedController;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as REST;
use JWT;
use Symfony\Component\HttpFoundation\Request;
use Doctrine;


class UsersController extends FOSRestController implements TokenAuthtentifiedController
{
    public function getUsersAction() {
        $manager = $this->getDoctrine()->getManager();

        $responseBody = $manager->getRepository('AppBundle:User')->findAll();
        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    public function getUserAction(Request $request, $slug) {
        $manager = $this->getDoctrine()->getManager();

        $user = $manager->getRepository('AppBundle:User')->find($slug);

        $responseBody = [];
        $responseBody['user'] = $user;
        $responseBody['requestedBy'] = $request->attributes->get('user');
        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param $user
     * @return mixed
     *
     * @Rest\Post("/users/authenticate")
     * @Rest\View()
     */
    public function authenticateUsersAction() {
        $responseBody = [];

        $request = Request::createFromGlobals();

        $user = json_decode($request->getContent(), true);

        $localUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($user['username']);

        if ($localUser) {
            $decoded = [
                'username' => $localUser->getUsername(),
                'password' => $localUser->getPassword(),
                'iat' => time(),
                'exp' => time() + 1 * 60 * 60
            ];

            $token = JWT::encode($decoded, 'ilovecocks', 'HS256');

            $responseBody['status'] = 'Succes';
            $responseBody['token'] = $token;
        } else {
            $localUser = new User();

            $localUser->setUsername($user['username']);
            $localUser->setPassword($user['password']);
            $localUser->setAdmin(1);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($localUser);
            $manager->flush();

            $responseBody['message'] = "Created a user";
        }


        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Options("/users");
     * @Rest\Options("/users/{key}");
     * @Rest\View()
     */
    function optionsUsersAction(Request $request, $key) {
        // Envoi de la "vue"
        $view = View::create(null, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }
}