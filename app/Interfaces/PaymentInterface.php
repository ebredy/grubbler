<?php

namespace app\Interfaces;

interface PaymentInterface {

    /*
     * Create a user account
     */
    public function createAccount( array $data );

    /*
     * Retrieves the details of the account.
     */
    public function retrieveAccount( $account_id );

    /*
     * Updates an account by setting the values
     * of the parameters passed.
     */
    public function updateAccount( $account_id, array $data );

    /*
     * Charge a credit card
     */
    public function createCharge( array $data );

    /*
     * Retrieves the details of a charge that
     * has previously been created
     */
    public function retrieveCharge( $charge_id );

    /*
     * Updates the specified charge by setting
     * the values of the parameters passed
     */
    public function updateCharge( $charge_id, array $data );

    /*
     * Capture the payment of an existing, uncaptured, charge.
     * This is the second half of the two-step payment flow,
     * where first you created a charge with the capture option set to false
     */
    public function captureCharge( $charge_id, array $data = [] );

    /*
     * refund a charge that has previously been created
     * but not yet refunded
     */
    public function createRefund( $charge_id, array $data = [] );

    /*
     * Retrieve details about a specific refund stored on the charge.
     */
    public function retrieveRefund( $charge_id, $refund_id );

    /*
     * Updates the specified refund by setting the
     * values of the parameters passed.
     */
    public function updateRefund( $charge_id, $refund_id, array $data );

    /*
     * Create a new credit card
     */
    public function createCard( $customer_id, $token );

    /*
     * Retrieve details about a specific card
     */
    public function retrieveCard( $customer_id, $card_id );

    /*
     * Update a card's details
     */
    public function updateCard( $customer_id, $card_id, array $data );
    
    /*
     * create customer
     * 
     */
     public function createCustomer( $payment_token, $user_id );
    /*
     * Delete a card
     */
    public function deleteCard( $customer_id, $card_id  );

    /*
     * See a list of the cards belonging to $account_id
     */
    public function listCards( $customer_id, $limit = 10 );

}