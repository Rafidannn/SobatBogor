<?php
/**
 * core/Router.php
 * Simple URL router: maps URL patterns to Controller@method.
 * Mendukung path parameter seperti /destinations/{slug} dan /admin/destinations/edit/{id}
 */
class Router {
    private array $routes = [];

    /**
     * Register GET route
     */
    public function get(string $path, string $handler): void {
        $this->routes[] = ['method' => 'GET', 'path' => $path, 'handler' => $handler];
    }

    /**
     * Register POST route
     */
    public function post(string $path, string $handler): void {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'handler' => $handler];
    }

    /**
     * Match incoming request to registered routes and dispatch.
     */
    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Strip BASE_URL subfolder prefix agar cocok dengan path route
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($basePath !== '' && $basePath !== '/') {
            $requestUri = substr($requestUri, strlen(rtrim($basePath, '/')));
        }
        if ($requestUri === '' || $requestUri === false) {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) continue;

            // Konversi path pattern {param} ke regex
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) continue;

            // Cocok — ambil nilai param
            array_shift($matches); // buang full match

            // Parsing handler: ClassName@method atau namespace\ClassName@method
            $handler = $route['handler'];
            [$classPart, $method] = explode('@', $handler);

            // Tangani namespace admin\ClassName
            if (str_contains($classPart, '\\')) {
                $parts     = explode('\\', $classPart);
                $namespace = implode('/', array_slice($parts, 0, -1));
                $className = end($parts);
                $file      = ROOT_PATH . '/app/controllers/' . $namespace . '/' . $className . '.php';
            } else {
                $className = $classPart;
                $file      = ROOT_PATH . '/app/controllers/' . $className . '.php';
            }

            // Load controller file jika belum di-require
            if (file_exists($file)) {
                require_once $file;
            } else {
                http_response_code(500);
                die("<h2>Controller not found: {$file}</h2>");
            }

            if (!class_exists($className)) {
                http_response_code(500);
                die("<h2>Class not found: {$className}</h2>");
            }

            $controller = new $className();

            if (!method_exists($controller, $method)) {
                http_response_code(500);
                die("<h2>Method not found: {$className}::{$method}()</h2>");
            }

            // Panggil method dengan parameter dari URL
            call_user_func_array([$controller, $method], $matches);
            return;
        }

        // Tidak ada route yang cocok → 404
        http_response_code(404);
        $this->show404();
    }

    /**
     * Tampilan 404 sederhana namun tetap on-brand
     */
    private function show404(): void {
        echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 — Halaman Tidak Ditemukan | SobatBogor</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:Outfit,sans-serif;background:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
        .box{text-align:center;padding:3rem;}
        .num{font-size:7rem;font-weight:800;color:#ea580c;line-height:1;}
        h1{font-size:1.5rem;font-weight:700;color:#0f172a;margin:.5rem 0 1rem;}
        p{color:#64748b;}
        a{display:inline-block;margin-top:1.5rem;background:#ea580c;color:#fff;padding:.7rem 2rem;border-radius:50px;text-decoration:none;font-weight:600;}
    </style>
</head>
<body>
    <div class="box">
        <div class="num">404</div>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Maaf, halaman yang kamu cari tidak ada atau telah dipindahkan.</p>
        <a href="' . BASE_URL . '/">Kembali ke Beranda</a>
    </div>
</body>
</html>';
    }
}
