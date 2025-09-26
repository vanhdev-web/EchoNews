<?php

namespace App\Core;

/**
 * Router Class
 * Xử lý routing và dispatch requests
 */
class Router
{
    protected $routes = [];
    protected $middlewares = [];
    protected $currentRoute = null;
    
    /**
     * Add GET route
     */
    public function get($uri, $controller, $method = 'index')
    {
        $this->addRoute('GET', $uri, $controller, $method);
        return $this;
    }
    
    /**
     * Add POST route
     */
    public function post($uri, $controller, $method = 'store')
    {
        $this->addRoute('POST', $uri, $controller, $method);
        return $this;
    }
    
    /**
     * Add PUT route
     */
    public function put($uri, $controller, $method = 'update')
    {
        $this->addRoute('PUT', $uri, $controller, $method);
        return $this;
    }
    
    /**
     * Add DELETE route
     */
    public function delete($uri, $controller, $method = 'destroy')
    {
        $this->addRoute('DELETE', $uri, $controller, $method);
        return $this;
    }
    
    /**
     * Add route với multiple HTTP methods
     */
    public function match($methods, $uri, $controller, $method = 'index')
    {
        foreach ($methods as $httpMethod) {
            $this->addRoute(strtoupper($httpMethod), $uri, $controller, $method);
        }
        return $this;
    }
    
    /**
     * Add middleware to current route
     */
    public function middleware($middleware)
    {
        if ($this->currentRoute) {
            $this->routes[$this->currentRoute]['middleware'][] = $middleware;
        }
        return $this;
    }
    
    /**
     * Add route to routes array
     */
    protected function addRoute($httpMethod, $uri, $controller, $method)
    {
        $routeKey = $httpMethod . ':' . $uri;
        
        $this->routes[$routeKey] = [
            'method' => $httpMethod,
            'uri' => $this->normalizeUri($uri),
            'controller' => $controller,
            'action' => $method,
            'middleware' => [],
            'parameters' => []
        ];
        
        $this->currentRoute = $routeKey;
    }
    
    /**
     * Dispatch request
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->getRequestUri();
        
        $route = $this->matchRoute($requestMethod, $requestUri);
        
        if (!$route) {
            $this->handleNotFound();
            return;
        }
        
        // Execute middlewares
        foreach ($route['middleware'] as $middleware) {
            $this->executeMiddleware($middleware);
        }
        
        // Execute controller action
        $this->executeController($route);
    }
    
    /**
     * Match route with request
     */
    protected function matchRoute($method, $uri)
    {
        foreach ($this->routes as $routeKey => $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = $this->convertToRegex($route['uri']);
            
            if (preg_match($pattern, $uri, $matches)) {
                // Extract parameters
                array_shift($matches); // Remove full match
                $route['parameters'] = $matches;
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Convert URI to regex pattern
     */
    protected function convertToRegex($uri)
    {
        // Convert {param} to regex groups
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $uri);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Execute controller action
     */
    protected function executeController($route)
    {
        $controllerClass = $route['controller'];
        $action = $route['action'];
        $parameters = $route['parameters'];
        
        // Add namespace if not present
        if (strpos($controllerClass, '\\') === false) {
            $controllerClass = 'App\\Controllers\\' . $controllerClass;
        }
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $action)) {
            throw new \Exception("Method {$action} not found in {$controllerClass}");
        }
        
        // Call controller method with parameters
        call_user_func_array([$controller, $action], $parameters);
    }
    
    /**
     * Execute middleware
     */
    protected function executeMiddleware($middleware)
    {
        $middlewareClass = 'App\\Middlewares\\' . $middleware;
        
        if (class_exists($middlewareClass)) {
            $middlewareInstance = new $middlewareClass();
            $middlewareInstance->handle();
        }
    }
    
    /**
     * Get current request URI
     */
    protected function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove base path (OnlineNewsSite)
        $basePath = '/OnlineNewsSite';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        return $this->normalizeUri($uri);
    }
    
    /**
     * Normalize URI
     */
    protected function normalizeUri($uri)
    {
        $uri = trim($uri, '/');
        return $uri === '' ? '/' : '/' . $uri;
    }
    
    /**
     * Handle 404 Not Found
     */
    protected function handleNotFound()
    {
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    /**
     * Generate URL for named route
     */
    public function url($routeName, $parameters = [])
    {
        // Implementation for named routes
        return CURRENT_DOMAIN . ltrim($routeName, '/');
    }
}