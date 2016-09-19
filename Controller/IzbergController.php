<?php

namespace judicaelpaquet\IzbergBundle\Controller;

use Guzzle\Http\Message\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IzbergController extends Controller
{
    /**
     * @Route("/izberg/connector", name="izberg_connector")
     * @return Response
     */
    public function connectAction()
    {
        $this->get('izberg_connector')->connect(
            'judicael.paquet@batiwiz.com',
            'Judicael',
            'Paquet',
            '1ad59d8a-6e63-46c6-b643-a8e0d08fdbb1'
        );
        // connect as anonymous
        $this->get('izberg_connector')->connect();
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/users", name="izberg_users")
     * @return Response
     */
    public function usersAction()
    {
        $users = $this->get('izberg_user')->getUsers();
        dump($users);
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/users/{id}", name="izberg_user", requirements={"id":"\d+"})
     * @return Response
     */
    public function userAction(Request $request, int $id)
    {
        $user = $this->get('izberg_user')->getUser($id);
        dump($user);
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/users/schema", name="izberg_user_schema_action")
     * @return Response
     */
    public function userSchemaAction()
    {
        $userSchema = $this->get('izberg_user')->getUserSchema();
        dump($userSchema);
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/carts", name="izberg_carts")
     * @return Response
     */
    public function cartsAction()
    {
        $carts = $this->get('izberg_cart')->getCarts();
        dump($carts);
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/carts/{id}", name="izberg_cart", requirements={"id":"\d+"})
     * @return Response
     */
    public function cartAction(Request $request, $id)
    {
        $cart = $this->get('izberg_cart')->getCart($id);
        dump($cart);
        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/izberg/carts/schema", name="izberg_cart_schema_action")
     * @return Response
     */
    public function cartSchemaAction()
    {
        $cartSchema = $this->get('izberg_cart')->getCartSchema();
        dump($cartSchema);
        return $this->render('::base.html.twig');
    }
}
