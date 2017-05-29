<?php
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 15/05/2017
 * Time: 13:52
 */

namespace AppBundle\Controller;

use AppBundle\Entity\TestCase;
use AppBundle\Interfaces\TokenAuthtentifiedController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;


class TestCaseController extends FOSRestController implements TokenAuthtentifiedController
{
    /**
     * @param Request $request
     * @param $id
     *
     * @Rest\Get("/testcases/{id}")
     * @Rest\View()
     */
    function getTestCaseAction(Request $request, $id) {
        $repo = $this->getDoctrine()->getRepository('AppBundle:TestCase');

        $testCase = $repo->find($id);

        // Envoi de la "vue"
        $view = View::create($testCase, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Get("/testcases")
     * @Rest\View()
     */
    function getTestCasesAction(Request $request) {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:TestCase');

        $testCases = $repo->findAll();

        // Envoi de la "vue"
        $view = View::create($testCases, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Post("/testcases")
     * @Rest\View()
     */
    function postTestCasesAction(Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $testCaseObject = $request->request->all();

        $testCase = new TestCase();
        $testCase
            ->setNom($testCaseObject['nom'])
            ->setDescription($testCaseObject['description'])
            ->setNumero($testCaseObject['numero'])
            ->setDate($testCaseObject['date']);

        $manager->persist($testCase);
        $manager->flush();

        // Envoi de la "vue"
        $view = View::create($testCase, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Rest\Put("/testcases/{id}")
     * @Rest\View()
     */
    function putTestCasesAction(Request $request, $id) {
        $status = 404;
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('AppBundle:TestCase');

        if ($testCase = $repo->find($id)) {
            $status = 200;

            $testCaseObject = $request->request->all();

            $modifs = json_decode($testCaseObject['modifications'], true);

            if (!is_array($modifs)) {
                $modifs = [];
            }

            $testCase->setModifications($modifs);

            foreach ($testCaseObject as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if ($key !== 'modifications') {
                    $testCase->{$setter}($value);
                }
            }

            $logger = $this->get('logger');
            $logger->info(json_encode($testCase));

            $manager->flush();

            $testCase = $repo->find($testCase->getId());
        } else {
            $testCase = null;
        }

        // Envoi de la "vue"
        $view = View::create($testCase, $status);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @param $slug
     *
     * @Rest\Delete("/testcases/{id}")
     * @Rest\View()
     */
    function deleteTestCaseAction(Request $request, $id) {
        $status = 204;
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('AppBundle:TestCase');

        if ($testCase = $repo->find($id)) {
            $manager->remove($testCase);

            $manager->flush();
        } else {
            $status = 404;
            $testCase = null;
        }

        // Envoi de la "vue"
        $view = View::create($testCase, $status);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Options("/testcases");
     * @Rest\Options("/testcases/{key}");
     * @Rest\View()
     */
    function optionsTestCaseAction(Request $request, $key) {
        // Envoi de la "vue"
        $view = View::create(null, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }
}