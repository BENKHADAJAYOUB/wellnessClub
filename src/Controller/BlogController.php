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
use Symfony\Component\HttpClient\HttpClient;

class BlogController extends AbstractController
{
    /**
     * @Route("/show/feed", name= "wellness_link_list")
     */
    public function homepage(): Response
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.e-sante.fr/taxonomy/term/615089/feed');
        $statusCode = $response->getStatusCode();
        $xml=simplexml_load_string($response->getContent()) or die("Error: Cannot create object");
        $title1 = $xml->channel[0]->item[0]->title;
        $link1 = $xml->channel[0]->item[0]->link;
        $title2 = $xml->channel[0]->item[1]->title;
        $link2 = $xml->channel[0]->item[1]->link;
        $title3 = $xml->channel[0]->item[2]->title;
        $link3 = $xml->channel[0]->item[2]->link;
        $title4 = $xml->channel[0]->item[3]->title;
        $link4 = $xml->channel[0]->item[3]->link;

        $articles = [
          [$title1, $link1],
          [$title2, $link2],
          [$title3, $link3],
          [$title4, $link4],
        ];
        return $this->render('home/linkPage.html.twig',
                                array ('articles'=>$articles, 'fluxRSS'=>$response->getContent(),
                                'statusCode'=>$statusCode, 'title1'=>$title1)
                                );

    }

    /**
     * @Route("/show/{id}", name="wellness_get_article")
     */
    public function getArticle(string $id): Response
    {
        return $this->render('articles/articlePage.html.twig',
                              array ('id'=>$id));
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
