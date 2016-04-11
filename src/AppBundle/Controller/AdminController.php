<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Form\ImageFormType;
use AppBundle\Entity\Image;

/**
 * Controller Admin
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * List of exists items
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("")
     * @Route("/")
     *
     * @template
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Image');

        $list = $repo->findAll();

        return array(
            'list' => $list,
        );
    }

    /**
     * Add image to library
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add")
     *
     * @template
     */
    public function addAction(Request $request)
    {
        $form = $this->getAddForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->handleForm($form);

            $em->persist($form->getData());
            $em->flush();

            $this->addFlash('notice', 'Ajouté');

            return $this->redirectToRoute('app_admin_add');
        }

        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Image');
        $tags = $repo->getAllTags();
        asort($tags);

        return array(
            'form' => $form->createView(),
            'tags' => $tags,
        );
    }

    /**
     * Edit image to library
     *
     * @param Request $request
     * @param Image   $image
     *
     * @return Response
     *
     * @Route("/edit/{id}")
     *
     * @template
     */
    public function editAction(Request $request, Image $image)
    {
        $form = $this->getEditForm($image);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->handleForm($form);

            $em->flush();

            $this->addFlash('notice', 'Modifié');

            return $this->redirectToRoute('app_admin_index');
        }

        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Image');
        $tags = $repo->getAllTags();
        asort($tags);

        return array(
            'image' => $image,
            'form' => $form->createView(),
            'tags' => $tags,
        );
    }

    /**
     * Delete image to library
     *
     * @param Request $request
     * @param Image   $image
     *
     * @return Response
     *
     * @Route("/delete/{id}")
     *
     * @template
     */
    public function deleteAction(Request $request, Image $image)
    {
        $session = $request->getSession();

        $sessionKey = 'item_to_delete';
        $id = $image->getId();
        $itemToDelete = $session->get($sessionKey);

        if ('oui' === $request->query->get('confirm')) {
            if ($itemToDelete === $id) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($image);
                $em->flush();

                $this->addFlash('notice', 'FunkoPop supprimé');

                $session->remove($sessionKey);

                return $this->redirectToRoute('app_admin_index');
            } elseif ($itemToDelete) {
                $this->addFlash('error', 'Tu peux pas supprimer ce FunkoPop. Reessaye, ça devrait marcher');
            }
        }

        $session->set($sessionKey, $id);


        return array(
            'image' => $image,
        );
    }

    /**
     * Liste les tags en fonction de la saisie 
     *
     * @param Request $request
     *
     * @return JsonResponse
     * 
     * @Route("/tags", options={"expose"=true})
     */
    public function tagAutocompleteAction(Request $request)
    {
        $input = $request->query->get('input');

        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Image');
        $list = $repo->findTags($input);

        return new JsonResponse($list);
    }

    /**
     * Upload une image
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route(
     *     "/upload/{id}", 
     *     options={"expose"=true},
     *     requirements={"id" = "\d+"}, 
     *     defaults={"id" = null}
     * )
     */
    public function uploadAction(Request $request, Image $image = null)
    {
        $form = $this->getEditForm($image);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $image = $this->handleForm($form);

            $em->flush();

            $serializer = $this->get('jms_serializer');
            $data = $serializer->serialize($image, 'json');

            $response = new Response();
            $response->setContent($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        return new JsonResponse(['error' => 'Image invalide']);
    }

    /**
     * Get image form
     *
     * @param Image $image
     *
     * @return Form
     */
    protected function getForm(Image $image = null)
    {
        $form = $this->createForm(ImageFormType::class, $image);

        return $form;
    }

    /**
     * Get image add form
     *
     * @param Image $image
     *
     * @return Form
     */
    protected function getAddForm(Image $image = null)
    {
        $form = $this->getForm($image);

        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
            'label' => 'Ajouter',
        ));

        return $form;
    }

    /**
     * Get image edit form
     *
     * @param Image $image
     *
     * @return Form
     */
    protected function getEditForm(Image $image = null)
    {
        $form = $this->getForm($image);

        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
            'label' => 'Enregister',
        ));

        return $form;
    }

    /**
     * Handle form
     *
     * @param Form $form
     *
     * @return Image
     */
    protected function handleForm(Form $form)
    {
        $image = $form->getData();

        if ($image->getPath() instanceof UploadedFile) {
            $file = $image->getPath();

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
            $uploadableManager->markEntityToUpload($image, $file);
        }

        return $image;
    }
}
