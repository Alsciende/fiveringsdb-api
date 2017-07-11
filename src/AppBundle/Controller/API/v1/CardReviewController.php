<?php

namespace AppBundle\Controller\API\v1;

use AppBundle\Controller\API\BaseApiController;
use AppBundle\Entity\Card;
use AppBundle\Entity\Review;
use AppBundle\Form\ReviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ReviewController
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class CardReviewController extends BaseApiController
{
    /**
     * Create a review on a card
     * @Route("/cards/{cardCode}/reviews")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     * @ParamConverter("card", class="AppBundle:Card", options={"id" = "cardCode"})
     */
    public function postAction (Request $request, Card $card)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->submit(json_decode($request->getContent(), true), false);

        if($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser())->setCard($card);
            $this->getDoctrine()->getManager()->persist($review);
            $this->getDoctrine()->getManager()->flush();
            return $this->success($review);
        }

        return $this->failure('validation_error', $this->formatValidationErrors($form->getErrors()));
    }

    /**
     * List all reviews on a card
     * @Route("/cards/{cardCode}/reviews")
     * @Method("GET")
     * @ParamConverter("card", class="AppBundle:Card", options={"id" = "cardCode"})
     */
    public function listAction (Card $card)
    {
        $reviews = $this
            ->get('doctrine')
            ->getRepository(Review::class)
            ->findBy(['card' => $card]);
        return $this->success($reviews);
    }

    /**
     * Get a review on a card
     * @Route("/cards/{cardCode}/reviews/{id}")
     * @Method("GET")
     */
    public function getAction (Review $review)
    {
        return $this->success($review);
    }

    /**
     * Edit a review on a card
     * @Route("/cards/{cardCode}/reviews/{id}")
     * @Method("PATCH")
     * @Security("has_role('ROLE_USER')")
     */
    public function patchAction (Request $request, Review $review)
    {
        if ($this->getUser() !== $review->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->submit(json_decode($request->getContent(), true), false);

        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->success($review);
        }

        return $this->failure('validation_error', $this->formatValidationErrors($form->getErrors()));
    }
}