<?php
namespace AppBundle\Controller;
/**
 * Created by PhpStorm.
 * User: gaelph
 * Date: 13/05/2017
 * Time: 14:08
 */

use AppBundle\Interfaces\TokenAuthtentifiedController;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;

class PhotoController extends FOSRestController implements TokenAuthtentifiedController
{
    /**
     * @param Request $request
     * @Rest\View()
     * @Rest\Get("/photos")
     */
    function getPhotosAction(Request $request) {
        $manager = $this->getDoctrine()->getManager()->getRepository('AppBundle:Photo');

        $responseBody = $manager->findBy(['user' => $request->attributes->get('user')]);

        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @param $key
     * @Rest\View()
     * @Rest\Get("/photos/{key}")
     */
    function getPhotoAction(Request $request, $key) {
        $manager = $this->getDoctrine()->getManager()->getRepository('AppBundle:Photo');

        $responseBody = $manager->findOneBy([
            'key' => $key,
            'user' => $request->attributes->get('user')
        ]);

        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @Rest\View()
     * @Rest\Post("/photos")
     */
    function postPhotoAction(Request $request) {
        $content = $request->request->all();
        $key = $content['key'];

        $manager = $this->getDoctrine()->getManager();

        $photo = new Photo();

        $filepath = getcwd() . '/uploads/' . $key . '.jpg';

        $pathToStore = $this->base64ToJpeg($content['value'], $filepath);

        $this->value = '/uploads/' . $key . '.jpg';

        $photo->setKey($content['key']);
        $photo->setValue($pathToStore);
        $photo->setUser($request->attributes->get('user'));
        $photo->setTimestamp(time());

        $manager->persist($photo);
        $manager->flush();

        // Envoi de la "vue"
        $view = View::create($photo, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @param $key
     *
     * @Rest\View()
     * @Rest\Put("/photos/{key}")
     */
    function putPhotoAction(Request $request, $key) {
        $content = $request->request->all();

        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('AppBundle:Photo');

        $status = 200;

        if ($photo = $repo->findOneBy([
            'key' => $key,
            'user' => $request->attributes->get('user')
        ])) {
            $photo->setKey($content['key']);
            $photo->setValue($content['value']);
            $photo->setUser($request->attributes->get('user'));
            $photo->setTimestamp(time());

            $manager->flush();
        } else {
            $status = 404;

            $photo = null;
        }

        // Envoi de la "vue"
        $view = View::create($photo, $status);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @param $key
     * @Rest\View()
     * @Rest\Delete("/photos/{key}")
     */
    function deletePhotoAction(Request $request, $key) {
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('AppBundle:Photo');
        $status = 204;

        if ($photo = $repo->findOneBy([
            'key' => $key,
            'user' => $request->attributes->get('user')
        ])) {
            $manager->remove($photo);
        } else {
            $status = 404;
        }

        // Envoi de la "vue"
        $view = View::create(null, $status);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * @param string $base64_string
     * @param string $output_file
     * @return mixed
     * @link http://stackoverflow.com/a/15153931
     */
    private function base64ToJpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' );

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }
}