<?php
/**
 * CardController class.
 *
 * @Project : alma
 * @File    : CardController.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 */

namespace App\Controller;

use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * Displays card content which contains in session & updates product quantity.
     *
     * @Route("/card", methods={"GET", "POST"}, name="card_read")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function cardReadAction(Request $request): Response
    {
        /** @var Card|null $cardFromSession */
        $cardFromSession = $request->getSession()->has('card') ? $request->getSession()->get('card') : null;

        // Checks if POST methods has been send in route.
        if (null !== $cardFromSession and $request->getMethod() === 'POST') {
            // Checks if quantity and amount values is contained in POST data.
            if ($request->request->has('quantity') and $request->request->has('unitAmount')) {
                $quantity = $request->request->get('quantity');

                // Updates card in session.
                $cardFromSession->setQuantity($quantity);
                $cardFromSession->setAmount($quantity * $request->request->get('unitAmount'));
            }
        }

        return $this->render('default/card.html.twig');
    }
}
