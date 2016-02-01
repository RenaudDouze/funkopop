<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Form\ImageFormType;

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
     * @param string  $style
     *
     * @return Response
     *
     * @Route("", defaults={"style" = "carousel"})
     * @Route("/{style}")
     */
    public function indexAction(Request $request, $style)
    {
        $list = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Image')->findAll();

        return $this->render($this->getTemplate($style), array(
            'list' => $list,
        ));
    }

    /**
     * Get template
     *
     * @param  string $style
     *
     * @return string
     */
    private function getTemplate($style)
    {
        $tpl = [
            'carousel' => 'carousel',
            'grille' => 'grid'
        ];

        if (empty($tpl[$style])) {
            throw new NotFoundHttpException("Template doesn't exist");
              
        }

        return sprintf('AppBundle:Index:%s.html.twig', $tpl[$style]);
    }
}
