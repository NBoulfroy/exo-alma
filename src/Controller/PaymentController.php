<?php
/**
 * PaymentController class.
 *
 * @Project : alma
 * @File    : PaymentController.php
 * @Author  : Nicolas BOULFROY
 * @Create  : 2020/08/29
 * @Update  : 2020/09/02
 */

namespace App\Controller;

use App\Entity\Order;
use App\Service\Alma\Alma;
use App\Type\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Exception\GuzzleException;

class PaymentController extends AbstractController
{
    /**
     * Payment service
     *
     * @var Alma
     */
    private $alma;

    /**
     * PaymentController constructor.
     *
     * @param Alma       $alma
     *
     * @return void
     */
    public function __construct(Alma $alma)
    {
        $this->alma = $alma;
    }

    /**
     * @Route("/payment/eligibility", methods={"GET"}, name="payment_elegibility_read")
     *
     * @return Response
     */
    public function paymentEligibilityReadAction(): Response
    {
        return $this->render('default/payment_eligibility.html.twig');
    }

    /**
     * AJAX route to check if the selected value for due payment is valid.
     *
     * @Route("/payment/eligibility-check", methods={"POST"}, name="ajax_payment_elegibility_check")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws GuzzleException
     */
    public function ajaxPaymentEligibilityCheckAction(Request $request): JsonResponse
    {
        // Checks if XMLHttpRequest is received
        if ($request->isXmlHttpRequest()) {
            // Checks if amount and installmentsCounts values has been send in POST request method.
            if ($request->request->has('amount') and $request->request->has('installmentsCounts')) {
                return new JsonResponse(
                    $this->alma->checkEligibility(
                        $request->request->get('amount'),
                        (int) $request->request->get('installmentsCounts')[0]
                    ),
                    Response::HTTP_OK,
                    [],
                    true
                );
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * AJAX route to store selected payment due in session for the finalisation payment step.
     *
     * @Route("/payment/eligibility-save", methods={"POST"}, name="ajax_payment_eligibility_save")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxPaymentEligibilitySaveAction(Request $request): JsonResponse
    {
        $session = $request->getSession();

        // Checks if XMLHttpRequest is received
        if ($request->isXmlHttpRequest()) {
            // Checks if purchaseAmount and paymentPlan values has been send in POST request method.
            if ($request->request->has('purchaseAmount') and $request->request->has('paymentPlan')) {
                // Checks if order exists in session.
                if (!$session->has('order')) {
                    $session->set('order', new Order());
                }

                // Defines new value for amount & paymentPlan attributes in Order entity object.
                $session->get('order')
                    ->setAmount((float) $request->request->get('purchaseAmount'))
                    ->setPaymentPlan((int) $request->request->get('paymentPlan'))
                ;

                return new JsonResponse([], Response::HTTP_OK);
            }
        }

        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Displays finalisation payment.
     *
     * @Route("/payment", methods={"GET", "POST"}, name="payment_create")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws GuzzleException
     */
    public function paymentCreateAction(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('order')) {
            return $this->redirectToRoute('card_read');
        }

        $order = $session->get('order');

        // Creates Type object.
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $response = json_decode($this->alma->paymentProceeding(
                $order->getAmount(),
                $order->getPaymentPlan(),
                $order->getFirstName(),
                $order->getLastName(),
                $order->getEmail(),
                $order->getPhoneNumber(),
                $order->getAddress()
            ), true);

            if (isset($response['id']) and null !== $response['id']) {
                $order->setAlmaId($response['id']);
                return $this->redirectToRoute('payment_resume');
            }
        }

        return $this->render('default/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays payment resume content.
     *
     * @Route("/payment/resume", methods={"GET"}, name="payment_resume")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function paymentResumeReadAction(Request $request): Response
    {
        $session = $request->getSession();

        // Checks if order exists in session and if order have an Alma transaction ID.
        if (!$session->has('order') or is_null($session->get('order')->getAlmaId())) {
            return $this->redirectToRoute('card_read');
        }

        return $this->render('default/payment_resume.html.twig');
    }
}
