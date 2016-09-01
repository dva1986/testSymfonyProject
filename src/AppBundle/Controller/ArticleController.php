<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Classes\FormErrorParser;

class ArticleController extends FOSRestController
{
    /**
     * @return Response
     * @Method({"GET"})
     */
    public function getArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('AppBundle:Article')->findAll();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($data, 'json');

        return new JsonResponse(json_decode($jsonContent), 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function postArticlesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, array(
            'method' => 'POST',
        ));

        $serializer = SerializerBuilder::create()->build();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($article);
            $em->flush();
            $jsonContent = $serializer->serialize($article, 'json');

            return new JsonResponse(json_decode($jsonContent), 200);
        }
        $jsonContent = $serializer->serialize(FormErrorParser::parse($form), 'json');

        return new JsonResponse(json_decode($jsonContent), 422);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteArticlesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (!$article) {
            return new JsonResponse(['messages' => 'Entity not found'], 404);
        }

        $em->remove($article);
        $em->flush();

        return new JsonResponse(['messages' => 'Entity was deleted'], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function cgetArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (!$article) {
            return new JsonResponse(['messages' => 'Entity not found'], 404);
        }

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($article, 'json');

        return new JsonResponse(json_decode($jsonContent), 422);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function putArticlesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->find($id);

        if (!$article) {
            return new JsonResponse(['messages' => 'Entity not found'], 404);
        }

        $form = $this->createForm(ArticleType::class, $article, array('method' => 'PUT'));

        $serializer = SerializerBuilder::create()->build();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($article);
            $em->flush();
            $jsonContent = $serializer->serialize($article, 'json');

            return new JsonResponse(json_decode($jsonContent), 200);
        }
        $jsonContent = $serializer->serialize(FormErrorParser::parse($form), 'json');

        return new JsonResponse(json_decode($jsonContent), 422);
    }

}