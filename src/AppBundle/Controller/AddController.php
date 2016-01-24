<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Form\ImageFormType;

/**
 * Controller add
 *
 * @Route("/add")
 */
class AddController extends Controller
{
    /**
     * Add image to library
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
        $form = $this->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $image = $form->getData();

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
            $uploadableManager->markEntityToUpload($image, $image->path);
            
            $em->persist($image);
            
            $em->flush();

            $this->addFlash('notice', 'FunkoPop ajouté');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Get image form
     *
     * @return Form
     */
    protected function getForm()
    {
        $form = $this->createForm(ImageFormType::class);

        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
        ));

        return $form;
    }
}
