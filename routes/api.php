<?php

use Dingo\Api\Routing\Router;
use Illuminate\Http\Request ;
/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
        $api->post('getbalanceapi', function(Request $request) {
          // error_log(print_r($request, true)); getaccountaddress
          //$blockHash = '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f';
         $blockBalance = bitcoind()->getBalance($request->email);
         return response()->json(['balance' => $blockBalance,"mail"=>$request->email] );

        });
        $api->post('getnewaddressapi', function(Request $request) {
          // error_log(print_r($request, true)); getaccountaddress
          //$blockHash = '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f';
         $blockInfo = bitcoind()->getNewAddress($request->email);
         return response()->json(['address' => $blockInfo,"mail"=>$request->email] );

        });
        $api->post('getaccountaddressapi', function(Request $request) {
          // error_log(print_r($request, true)); getaccountaddress
          //$blockHash = '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f';
         $blockInfo = bitcoind()->getAccountAddress($request->email);
         return response()->json(['address' => $blockInfo,"mail"=>$request->email] );

        });

        $api->post('sendtoaddressapi', function(Request $request) {
          // error_log(print_r($request, true)); getaccountaddress
          //$blockHash = '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f';
         $blockInfo = bitcoind()->sendToAddress($request->bitcoinaddress,
         $request->amount,$request->commentForSender,$request->commentForReciever);
         return response()->json(['message' => $blockInfo,"mail"=>$request->email] );

        });


    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
    $api->get('getBitCoin', function() {

      //$blockHash = '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f';
     $blockInfo = bitcoind()->getDifficulty();
     return response()->json($blockInfo);

    });

});
