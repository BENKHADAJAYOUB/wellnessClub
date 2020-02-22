<?php


namespace App\Controller;

use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class BlogController extends AbstractController
{
    /**
     * @Route("/show/feed", name= "wellness_link_list")
     */
    public function homepage(): Response
    {

        $liens = array("article 1","article 2","article 3 ");

        return $this->render('home/linkPage.html.twig',
                                array ('articles'=>$liens)
                                );

    }

    /**
     * @Route("links/create", name= "wellness_link_create")
     */
    public function create(Request $request) : Response
    {
        $link = new Link();
        $form = $this->createFormBuilder($link)
            ->add('name', TextType::class)
            ->add('path', UrlType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitButton::class)
            ->getForm()
        ;
        if ($form->isSubmitted()){
            dd($form);
        }
        return $this->render(':home:create.html.twig',[
            'form' =>$form->createView()
        ]);
    }

}