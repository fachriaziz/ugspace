<?php
class Router
{
  private $routes = [];

  public function get($path, $action)
  {
    $this->routes['GET'][$path] = $action;
  }

  public function post($path, $action)
  {
    $this->routes['POST'][$path] = $action;
  }

  public function run()
  {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/') ?: '/';

    // Check exact match
    if (isset($this->routes[$method][$uri])) {
      return $this->call($this->routes[$method][$uri]);
    }

    // Check dynamic routes (e.g., /schedule/1)
    foreach ($this->routes[$method] ?? [] as $route => $action) {
      $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
      if (preg_match("#^$pattern$#", $uri, $matches)) {
        array_shift($matches);
        return $this->call($action, $matches);
      }
    }

    // 404
    http_response_code(404);
    echo "Page not found";
  }

  private function call($action, $params = [])
  {
    $controller = new Controller;
    call_user_func_array([$controller, $action], $params);
  }
}
