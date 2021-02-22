<?php

class Cart
{

    protected $cartId = "ezyVet";

    /**
     * A collection of cart items.
     *
     * @var array
     */
    private $items = [];

    /**
     * Initialize cart.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!session_id()) {
            session_start();
        }

        $this->read();
    }

    /**
     * Get items in  cart.
     *
     * @return array
     */
    public function getItems()
    {

        return $this->items;
    }

    /**
     * Check if the cart is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {

        if (sizeof($this->items) == 0){
            return true;
        }else{
            return false;
        }

    }


    /**
     * Get the sum price of items in the cart.
     *
     * @return int
     */
    public function getAttributeTotal()
    {
        $total = 0;

        foreach ($this->items as $items) {


            $total += $items['price'] * $items['quantity'];

        }

        return $total;
    }


    /**
     * Add item to cart.
     *
     * @param string $name
     * @param int    $price
     *
     * @return bool
     */
    public function add($name,$price)
    {

        // If we have already added this product we just increase its quantity
        if (array_key_exists($name, $this->items)) {

            $this->items[$name]["quantity"] += 1;

        }else{
            // If we have not added this product before we set the quantity to 1
            $this->items[$name] = array("name" => $name, "price" => $price, "quantity" =>1);

        }

        $this->write();

        return true;
    }


    /**
     * Remove item from cart.
     *
     * @param string name
     *
     * @return bool
     */
    public function remove($name)
    {
        if (!isset($this->items[$name])) {
            return false;
        }

        // If we have only one quantity of an specific item then remove it from cart
        if ($this->items[$name]["quantity"] == 1){

            unset($this->items[$name]);
        }else{

            // If we have more than one quantity of an specific item then we remove only one
            $this->items[$name]["quantity"] -=1;
        }
        $this->write();

        return true;

    }



    /**
     * Read items from cart session.
     */
    private function read()
    {
        $this->items = json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
    }

    /**
     * Write changes into cart session.
     */
    private function write()
    {

        $_SESSION[$this->cartId] = json_encode(array_filter($this->items));

    }
}
