<?php

namespace FunkoPopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FunkoPopBundle\Form\ImageFormType;

/**
 * Controller index
 *
 * @Route("")
 */
class IndexController extends Controller
{
    /**
     * List image from library
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/")
     * @Route("/list")
     * 
     * @template
     */
    public function indexAction(Request $request)
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('FunkoPopBundle:Image')->findAll();

        return array(
            'list' => $list,
        );
    }
}
