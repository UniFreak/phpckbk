<?php
ob_start();

// Use Closure as Callback
$numbers = array_map(function($num) {
    return $num + 1;
}, [1, 2, 3]);
print_r($numbers);

// Attach Status
function enclosePerson($name) {
    return function ($doCommand) use ($name) {
        return sprintf('%s, %s', $name, $doCommand);
    };
}
$clay = enclosePerson('Clay');
// even Closure object is outside enclosePerson()
// it still remember $name
echo $clay('get me sweet tea!' . "\n");

// PHP Framework often use Closure to do Routing
// using bindTo()
class App {
    protected $routes = [];
    protected $responseStatus = '200 OK';
    protected $responseContentType = 'text/html';
    protected $responseBody = 'Hello World';

    public function addRoute($routePath, $routeCallback) {
        $this->routes[$routePath] = $routeCallback->bindTo($this, __CLASS__);
    }

    public function dispath($currentPath) {
        foreach ($this->routes as $routePath => $callback) {
            if ($routePath === $currentPath) {
                $callback();
            }
        }

        header('HTTP/1.1 ' . $this->responseStatus);
        header('Content-type: ' . $this->responseContentType);
        header('Content-length: ' . mb_strlen($this->responseBody));
        echo $this->responseBody;
    }
}

$app = new App();
$app->addRoute('/users/josh', function() {
    $this->responseContentType = 'application/json; charset=utf8';
    $this->responseBody = '{"name": "Josh"}';
});
$app->dispath('/users/josh');