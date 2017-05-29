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

/**
 * Class PhotoController
 *
 * @package AppBundle\Controller
 */
class PhotoController extends FOSRestController implements TokenAuthtentifiedController
{
    /**
     * Renvoies toutes les photos
     * @param Request $request
     * @Rest\View()
     * @Rest\Get("/photos")
     */
    function getPhotosAction(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Photo');

        $responseBody = $repository->findBy(['user' => $request->attributes->get('user')]);

        // Envoi de la "vue"
        $view = View::create($responseBody, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * Renvoie la photo pour un idnetifiant perticulier
     * @param Request $request
     * @param mixed $key              l'identifiant de la photo
     * @Rest\View()
     * @Rest\Get("/photos/{key}")
     */
    function getPhotoAction(Request $request, $key) {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Photo');

        $responseBody = $repository->findOneBy([
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
     * Ajoute une nouvelle photo
     * @param Request $request
     * @Rest\View()
     * @Rest\Post("/photos")
     */
    function postPhotoAction(Request $request) {
        $content = $request->request->all();
        $key = $content['key'];

        $manager = $this->getDoctrine()->getManager();

        $photo = new Photo();

        $filePath = getcwd() . '/uploads/' . $key . '.jpg';

        $this->base64ToJpeg($content['value'], $filePath);

        $photo->setKey($content['key'])
            ->setValue('uploads/' . $key . '.jpg')
            ->setUser($request->attributes->get('user'));

        $manager->persist($photo);
        $manager->flush();

        // Envoi de la "vue"
        $view = View::create($photo, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * Mets à jour une photo
     * @param Request $request
     * @param mixed $key                 L'identifiant de la photo
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
            $photo->setKey($content['key'])
                ->setValue($content['value'])
                ->setUser($request->attributes->get('user'));

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
     * Supprime une photo
     * @param Request $request
     * @param mixed $key              L'idnetifiant de la photo
     * @Rest\View()
     * @Rest\Delete("/photos/{key}")
     */
    function deletePhotoAction(Request $request, $key) {
        $logger = $this->get('logger');
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('AppBundle:Photo');
        $status = 204;

        if ($photo = $repo->findOneBy([
            'key' => $key,
            'user' => $request->attributes->get('user')
        ])) {
            unlink(getcwd() . DIRECTORY_SEPARATOR . $photo->getValue());
            $manager->remove($photo);
            $manager->flush();
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
     * Réponse à une requête OPTIONS
     * @param Request $request
     *
     * @Rest\Options("/photos");
     * @Rest\Options("/photos/{key}");
     * @Rest\View()
     */
    function optionsPhotosAction(Request $request, $key) {
        // Envoi de la "vue"
        $view = View::create(null, 200);
        $viewHandler = $this->get('fos_rest.view_handler');

        $view->setFormat('json');

        return $viewHandler->handle($view);
    }

    /**
     * Écrit un fichier image à partir de la chaîne base64
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