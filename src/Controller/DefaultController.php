<?php
/**
 * DefaultController class.
 *
 * @Project : alma
 * @File    : DefaultController.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 * @Update  : 2020/09/01
 */

namespace App\Controller;

use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Displays website home page.
     *
     * @Route("/", name="index", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $session = $request->getSession();
        $order = new Card();

        // Checks if card exists in session.
        if (!$session->has('card')) {
            $session->set('card', $order);
        }

        return $this->render('default/index.html.twig');
    }

    /**
     * AJAX route to add product and quantity in card which contains in session.
     *
     * @Route("/add-product", methods={"POST"}, name="ajax_add_product_to_card")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxAddProductToCardAction(Request $request): JsonResponse
    {
        /** @var Card|null $cardFromSession */
        $cardFromSession = ($request->getSession()->has('card')) ? $request->getSession()->get('card') : null;

        // Checks if XMLHttpRequest is received and if card in session is not null.
        if ($request->isXmlHttpRequest() and null !== $cardFromSession) {
            // Checks if quantity and amount values has been send in POST request method.
            if ($request->request->has('quantity') and $request->request->has('amount')) {
                $quantity = $request->request->get('quantity');

                // Adds new quantity to the current quantity.
                $cardFromSession->addQuantity($quantity);
                // Adds new amount to the current total amount contains in card.
                $cardFromSession->addAmount($quantity *  $request->request->get('amount'));

                return new JsonResponse([], Response::HTTP_OK);
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }
}
