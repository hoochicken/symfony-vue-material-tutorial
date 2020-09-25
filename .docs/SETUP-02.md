## 2. Init server

### 2.1 Create Server

Here we initialise our proud project just by generating a new folder.  
We use the symfony/website-skeleton. 
The server will be available in your browser on http://127.0.0.1:8000

~~~cli
# Building server
# server -S 127.0.0.1:8000
composer create-project symfony/website-skeleton dungeon-server
# -S <addr>:<port> means Run with built-in web server.
# -t <docroot> specifies document root <docroot> for built-in web server.

# enter the dungeon-server
cd dungeon-server

# start the server
php -S 127.0.0.1:8000 -t public
~~~

### 2.2 First Routing Check

Generate first file, a demo controller

~~~php
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DemoController
{
    /**
     * @Route("/demo")
     */
    public function demonicAction()
    {
        return new JsonResponse([
            [
                'title' => 'The Real Demo',
                'description' => 'Some description',
                'state' => 1
            ]
        ]);
    }
}
~~~

Note the `@Route("/demo")` in the method's comment.  
This actually ist the route, that we can call.  
Go to <http://127.0.0.1:8000/demo>, and symfony will show you the result right away.
Should look a little like this:

~~~json
[{"title":"The Real Demo","description":"Some description","state":1}]
~~~
 
### 2.3 Extend A Little 

To simplifiy things a little (to avoid Returning JsonResponse() all the time, we generate a general src/Controller/ApiController.php.

~~~php
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }
}
~~~

... and we adjust our already existing DemoController.php

~~~php
<?php
namespace App\Controller;

class DemoController extends ApiController
{
    public function demonicAction()
    {
        return $this->respond([
             [
                'title' => 'The Real Demo',
                'description' => 'Some description',
                'state' => 1
            ]
        ]);
    }
}
~~~ 

### 2.4 Use proper Router

1. Strip @Route in comment from DemoController::demonicAction()) 

2. Add route ro dungeon-server/config/routes.yml

~~~json
index:
  path: /
  controller: App\Controller\DemoController::demonicAction

demoRoute:
  path: /demo
  controller: App\Controller\DemoController::demonicAction
~~~ 

Go to <http://127.0.0.1:8000/demo>, that should still work and return a proper json output.
