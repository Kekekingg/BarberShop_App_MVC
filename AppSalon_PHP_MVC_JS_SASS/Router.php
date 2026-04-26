<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes()
    {

        // Protect Routes...
        session_start();

        // Protected route arrays...
        // $protected_routes = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        // $auth = $_SESSION['login'] ?? null;

        $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }


        if ( $fn ) {
            // Call user fn it will call a function when we don't know what it will be.
            call_user_func($fn, $this); // This is for passing arguments
        } else {
            echo "Page Not Found or Invalid Route";
        }
    }

    public function render($view, $data = [])
    {

        // Read what we pass before your eyes
        foreach ($data as $key => $value) {
            $$key = $value;  // The double dollar sign means: variable variable. Basically, our variable remains the original, but when assigned to another, it doesn't overwrite it; it maintains its value. In this way, the variable name is assigned dynamically.
        }

        ob_start(); // Storage in memory for a moment...

        // then we include the view in the layout
        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean(); // Clean the Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}
