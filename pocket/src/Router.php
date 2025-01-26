<?php namespace Roocket\PocketCore;

class Router
{
    static private array $routesMap = [
        'get' => [],
        'post' => []
    ];
    protected array $routerFiles = [];
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    static public function get(string $url , $callback) : void
    {
        self::$routesMap['get'][$url] = $callback;
    }

    static public function post(string $url , $callback) : void
    {
        self::$routesMap['post'][$url] = $callback;
    }

    static public function view(string $url , $callback) : void
    {
        self::$routesMap['get'][$url] = $callback;
    }

    public function setRouterFile(string $path) : Router
    {
        $this->routerFiles[$path] = $path;

        return $this;
    }

    private function getCallbackFromDynamicRoute() 
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();

        $routes = self::$routesMap[$method];

        foreach($routes as $route => $callback) {
            $routeNames = [];

            if(! $route)
                continue;

            if(preg_match_all('/\{(\w+)(:[^}]+)?}/' , $route , $matches)) {
                $routeNames = $matches[1];
            }

            $routeRegex = $this->convertRouteToRegex($route);

            if(preg_match_all($routeRegex, $url , $matches)) {
                $values = [];

                unset($matches[0]);

                foreach($matches as $match) {
                    $values[] = $match[0];
                }

                $routeParams = array_combine($routeNames, $values);

                return [ 0 => $callback  , 1 => $routeParams];
            }

        }

        return false;
    }

    public function resolve()
    {
        foreach ($this->routerFiles as $file) {
            require_once $file;
        }


        $method = $this->request->getMethod();
        $url = $this->request->getUrl();

        $callback = self::$routesMap[$method][$url] ?? false;
        $params = [];

        if(! $callback) {
            $routeCallback = $this->getCallbackFromDynamicRoute();

            if(! $routeCallback) {
                throw new \Exception('not found');
            }

            $callback = $routeCallback[0];
            $params = $routeCallback[1];

        }

        if(is_string($callback)) {
            return (new View)->render($callback);
        }

        if(is_array($callback)) {
            $controllerMethod = new \ReflectionMethod($callback[0] , $callback[1]);
            $autoInjections = [];
            foreach ($controllerMethod->getParameters() as $key => $value) {
                if($value->getType()) {
                    $class = $value->getType()->getName();
                    if(class_exists($class)) {
                        $autoInjections[$value->getName()] = new $class;
                    }
                }
            }
            return $controllerMethod->invoke(new $callback[0] ,$autoInjections, $params);
        }


        return call_user_func($callback , ...array_values($params));
    }

    protected function convertRouteToRegex(string $route) : string
    {
        return "@^" . preg_replace_callback(
                '/\{\w+(:([^}]+))?}/',
                fn($m) => isset($m[2]) ? "({$m[2]})" : "([-\w]+)",
                $route
            ) . "$@";
    }
}
