<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

/**
 * ActiveRecord ORM
 *
 * So that we can seperate the business logic i.e DB work in a model
 */
require 'vendor/ActiveRecord.php'; 
require 'vendor/JsonApiMiddleware.php'; 
require 'vendor/JsonApiView.php'; 
require 'helper/utlity_functions.php'; 

ActiveRecord\Config::initialize(function($cfg) {
    $cfg->set_model_directory('models');
    $cfg->set_connections(array(
        'development' => 'mysql://web-db_dev-5:CBRTY#$%@@172.16.16.35/DEV_LAB5_DB_P518_TickerPix_APP'
    ));
});

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

function APIrequest()
{ 
    $app = \Slim\Slim::getInstance(); 
    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware()); 
}

/**
 * [signup route]
 * [Register a user in the database]
 * [request:username,password,email,devicetoken(optional)]
 * [response:id,username,email,devicetoken(optional),status,message]
 */
$app->post(
    '/api/signup','APIrequest',
    function () use ($app) {
        
        $result=array();
        $result['result']=$data;
        $app->render(200,$result);
    }
);


/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
