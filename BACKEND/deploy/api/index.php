<?php
// php -S localhost:8080 -t api api/index.php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;
use \Firebase\JWT\JWT;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

const JWT_SECRET = "Skillou67JwtSecret";

$app = AppFactory::create();

function createJwT (Response $response) : Response {
    $issuedAt = time();
    $expirationTime = $issuedAt + 60;
    $payload = array(
    'userid' => '667',
    'email' => 'rayane@gmail.com',
    'pseudo' => 'Skillou',
    'iat' => $issuedAt,
    'exp' => $expirationTime
    );

    $token_jwt = JWT::encode($payload,JWT_SECRET, "HS256");
    $response = $response->withHeader("Authorization", "Bearer {$token_jwt}");
    return $response;
}

// GET
$app->get('{name}', function (Request $request, Response $response, $args) {
    $array = [];
    $array ["nom"] = $args ['name'];
    $response->getBody()->write(json_encode ($array));
    return $response;
});

$app->get('/api/user', function (Request $request, Response $response, $args) {   
    $data = array('nom' => 'toto', 'prenom' => 'titi','adresse' => '6 rue des fleurs', 'tel' => '0606060607');
    $response->getBody()->write(json_encode($data));

    return $response;
});

// POST

// APi d'authentification générant un JWT
$app->post('/api/login', function (Request $request, Response $response, $args) {   
    $err=false;
    $body = $request->getParsedBody();
    $login = $body ['login'] ?? "";
    $pass = $body ['pass'] ?? "";

    if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login))   {
        $err = true;
    }
    if (!preg_match("/[a-zA-Z0-9]{1,20}/",$pass))  {
        $err=true;
    }

    if (!$err) {
            $response = createJwT ($response);
            $data = array('nom' => 'Skillou', 'prenom' => 'Rayoux');
            $response->getBody()->write(json_encode($data));
     } else {          
            $response = $response->withStatus(401);
     }
    return $response;
});

///////////////////////////
// API Catalogue Produit //
//////////////////////////

// $filename = './assets/mock/produits.json';
// $data = file_get_contents($filename);
// $array = json_decode($data);

// $app->get('/api/catalogue', function (Request $request, Response $response){

//     $clientRepository = $entityManager->getRepository('Products');
//     return $response;
// });

// $app->get('/api/catalogue/{id}', function (Request $request, Response $response, $args) {
//     global $array;
//     $id = $args ['id'];
//     $array = $array[$id - 1];
//     $response->getBody()->write(json_encode ($array));
//     return $response;
// });

//////////////////////////////////////////////////////////
///////////////////// SLIM PRODUCT ///////////////////////
//////////////////////////////////////////////////////////


// Define a route to retrieve all products
$app->get('/products', function (Request $request, Response $response) use ($entityManager) {
    // Retrieve all products from the database
    $products = $entityManager->getRepository(Product::class)->findAll();

    // Return the products as JSON
    return $response->withJson($products);
});

// Define a route to retrieve a single product by ID
$app->get('/products/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the product from the database
    $product = $entityManager->find(Product::class, $args['id']);

    // If the product doesn't exist, return a 404 response
    if (!$product) {
        return $response->withStatus(404);
    }

    // Return the product as JSON
    return $response->withJson($product);
});

// Define a route to create a new product
$app->post('/products', function (Request $request, Response $response) use ($entityManager) {
    // Create a new product entity
    $product = new Product();
    $product->setName($request->getParsedBody()['name']);
    $product->setDescription($request->getParsedBody()['description']);
    $product->setPrice($request->getParsedBody()['price']);
    $product->setCategory($request->getParsedBody()['category']);
    $product->setImage($request->getParsedBody()['image']);
    $product->setSummary($request->getParsedBody()['summary']);

    // Persist the entity to the database
    $entityManager->persist($product);
    $entityManager->flush();

    // Return the new product as JSON
    return $response->withJson($product);
});

$app->put('/products/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the product from the database
    $product = $entityManager->find(Product::class, $args['id']);

    // If the product doesn't exist, return a 404 response
    if (!$product) {
        return $response->withStatus(404);
    }

    // Update the product's properties with data from request
    $product->setName($request->getParsedBody()['name']);
    $product->setDescription($request->getParsedBody()['description']);
    $product->setPrice($request->getParsedBody()['price']);
    $product->setCategory($request->getParsedBody()['category']);
    $product->setImage($request->getParsedBody()['image']);
    $product->setSummary($request->getParsedBody()['summary']);

    // Persist the changes to the database
    $entityManager->persist($product);
    $entityManager->flush();

    // Return the updated product as JSON
    return $response->withJson($product);
});


// Define a route to delete an existing product
$app->delete('/products/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the product from the database
    $product = $entityManager->find(Product::class, $args['id']);

    // If the product doesn't exist, return a 404 response
    if (!$product) {
        return $response->withStatus(404);
    }

    // Remove the product from the database
    $entityManager->remove($product);
    $entityManager->flush();

    // Return an empty response
    return $response->withStatus(204);
});

//////////////////////////////////////////////////////////
///////////////////// SLIM CLIENT ////////////////////////
//////////////////////////////////////////////////////////

// Define a route to retrieve all clients
$app->get('/clients', function (Request $request, Response $response) use ($entityManager) {
    // Retrieve all clients from the database
    $clients = $entityManager->getRepository(Client::class)->findAll();

    // Return the clients as JSON
    return $response->withJson($clients);
});

// Define a route to retrieve a single client by ID
$app->get('/clients/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the client from the database
    $client = $entityManager->find(Client::class, $args['id']);

    // If the client doesn't exist, return a 404 response
    if (!$client) {
        return $response->withStatus(404);
    }

    // Return the client as JSON
    return $response->withJson($client);
});

// Define a route to create a new client
$app->post('/clients', function (Request $request, Response $response) use ($entityManager) {
    // Create a new client entity
    $client = new Client();
    $client->setCivility($request->getParsedBody()['civility']);
    $client->setFirstName($request->getParsedBody()['firstName']);
    $client->setLastName($request->getParsedBody()['lastName']);
    $client->setEmail($request->getParsedBody()['description']);
    $client->setTelephone($request->getParsedBody()['telephone']);
    $client->setStreet($request->getParsedBody()['street']);
    $client->setCity($request->getParsedBody()['city']);
    $client->setZipCode($request->getParsedBody()['zipCode']);
    $client->setLogin($request->getParsedBody()['zipCode']);
    $client->setPassword($request->getParsedBody()['zipCode']);

    // Persist the entity to the database
    $entityManager->persist($client);
    $entityManager->flush();

    // Return the new client as JSON
    return $response->withJson($client);
});

$app->put('/clients/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the client from the database
    $client = $entityManager->find(Client::class, $args['id']);

    // If the client doesn't exist, return a 404 response
    if (!$client) {
        return $response->withStatus(404);
    }

    // Update the client's properties with data from request
    $client->setCivility($request->getParsedBody()['civility']);
    $client->setFirstName($request->getParsedBody()['firstName']);
    $client->setLastName($request->getParsedBody()['lastName']);
    $client->setEmail($request->getParsedBody()['description']);
    $client->setTelephone($request->getParsedBody()['telephone']);
    $client->setStreet($request->getParsedBody()['street']);
    $client->setCity($request->getParsedBody()['city']);
    $client->setZipCode($request->getParsedBody()['zipCode']);
    $client->setLogin($request->getParsedBody()['zipCode']);
    $client->setPassword($request->getParsedBody()['zipCode']);

    // Persist the changes to the database
    $entityManager->persist($client);
    $entityManager->flush();

    // Return the updated client as JSON
    return $response->withJson($client);
});


// Define a route to delete an existing client
$app->delete('/clients/{id}', function (Request $request, Response $response, $args) use ($entityManager) {
    // Retrieve the client from the database
    $client = $entityManager->find(Client::class, $args['id']);

    // If the client doesn't exist, return a 404 response
    if (!$client) {
        return $response->withStatus(404);
    }

    // Remove the client from the database
    $entityManager->remove($client);
    $entityManager->flush();

    // Return an empty response
    return $response->withStatus(204);
});


$options = [
    "attribute" => "token",
    "header" => "Authorization",
    "regexp" => "/Bearer\s+(.*)$/i",
    "secure" => false,
    "algorithm" => ["HS256"],
    "secret" => JWT_SECRET,
    "path" => ["/api"],
    "ignore" => ["/api/hello","/api/login","/api/createUser", "/api/products", "/api/clients"],
    "error" => function ($response, $arguments) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'Le token JWT est invalide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
];

$app->add(new Tuupola\Middleware\JwtAuthentication($options));
$app->run ();

// GET /contrats              -> Récupère tous les contrats
// POST /contrats             -> Crée un contrat 
// GET /contrats/12           -> Récupère le contrat identifié par 12
// PUT /contrats/12           -> Modifie le contrat 12  
// DELETE /contrats/12        -> Supprime le contrat 1