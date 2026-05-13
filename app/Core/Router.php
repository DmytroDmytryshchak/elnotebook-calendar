<?php

// Зберігає таблицю маршрутів і визначає який контролер викликати для поточного запиту
class Router
{
    private $routes = array();

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes[] = array(
            'method' => $method,
            'uri'    => $uri,
            'action' => $action,
        );
    }

    public function dispatch($request)
    {
        $method = $request->method();
        $uri    = $request->uri();

        foreach ($this->routes as $route) {
            $pattern = $this->uriToPattern($route['uri']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array();
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[] = $value;
                    }
                }
                $this->runAction($route['action'], $request, $params);
                return;
            }
        }

        throw new NotFoundException('Route not found: ' . $method . ' ' . $uri);
    }

    private function uriToPattern($uri)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $uri);
        return '#^' . $pattern . '$#';
    }

    private function runAction($action, $request, $params)
    {
        $parts      = explode('@', $action);
        $className  = $parts[0];
        $methodName = $parts[1];

        $controller = new $className();

        call_user_func_array(array($controller, $methodName), array_merge(array($request), $params));
    }
}