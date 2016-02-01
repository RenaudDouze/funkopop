<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @Route("/add/")
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

            $this->addFlash('notice', 'FunkoPop ajouté');

            return $this->redirectToRoute('app_admin_add');
        }

        return array(
            'form' => $form->createView(),
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
     * @Route("/edit/{id}/")
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

            $this->addFlash('notice', 'FunkoPop modifié');

            return $this->redirectToRoute('app_admin_index');
        }

        return array(
            'image' => $image,
            'form' => $form->createView(),
        );
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

        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
            'label' => 'Ajoute le',
        ));

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
            'label' => 'Ajoute le',
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
            'label' => 'Modifie le',
        ));

        return $form;
    }

    /**
     * Handle form
     *
     * @param  Form $form
     */
    protected function handleForm(Form $form)
    {
        $image = $form->getData();

        if ($image->getPath() instanceof UploadedFile) {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
            $uploadableManager->markEntityToUpload($image, $image->getPath());
        }
    }
}
