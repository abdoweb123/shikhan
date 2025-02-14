<?php

namespace App\Services;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PayService
{

  protected $client;
  protected $integrationID;
  protected $authenticationToken;
  protected $amount_cents;

  public function __construct()
  {
      $this->client = new Client();
      $this->integrationID = config('paymob.integration_id_credit');
  }

  // 01
  public function getAuthenticationToken()
  {
      // https://accept.paymobsolutions.com/api/auth/tokens
      $response = $this->client->request('POST', config('paymob.authentication_token_endpoint'), [
          'json' => [
              "api_key" => config('paymob.api_key'),
          ]
      ]);

      $this->authenticationToken = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
      // return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
  }

  // 02
  public function makeOrder($orderId)
  {
      // $authToken = $this->getAuthenticationToken();
      // $merchantId = $this->getAuthenticationToken()['profile']['id'];
      $this->getAuthenticationToken();
      $token = $this->authenticationToken['token'];

      // https://accept.paymobsolutions.com/api/ecommerce/orders
      $response = $this->client->request('POST', config('paymob.create_order_endpoint'), [
          'json' => [
              'auth_token' => $token,
              'delivery_needed' => 'false',
              'amount_cents' => 100, //  1000 egy
              'currency' => 'EGP',
              // 'merchant_id' => $merchantId,      // merchant_id obtained from step 1
              // 'merchant_order_id' => 11,
              // 'notify_user_with_email' => true,
              'items' => [],
          ]
      ]);

      return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

  }

  // 03
  public function createPaymentKeyToken($orderId,$billing_data)
  {

      $orderId = $this->makeOrder($orderId)['id'];
      $token = $this->authenticationToken['token'];

      // https://accept.paymobsolutions.com/api/acceptance/payment_keys
      $response = $this->client->request('POST', config('paymob.payment_key_token_endpoint'), [
          'json' => [
              "auth_token" => $token,
              "amount_cents" => 200,
              "expiration" => 36000,
              "order_id" => $orderId,    // id obtained in step 2
              "currency" => 'EGP',
              "integration_id" => $this->integrationID, // card integration_id will be provided upon signing up,
              "lock_order_when_paid" => "true",
              "billing_data" => $billing_data,

          ]
      ]);
      return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)['token'];
  }


  public function createPayRequest($order_id,$billing_data,$model)
  {

      $translations=[];
      $token = $this->createPaymentKeyToken($order_id,$billing_data);
      return view('payment.paymob')
                ->with('token',$token);
                // ->with('lecture',$lecture)
                // ->with('translations',$translations);
  }







}
