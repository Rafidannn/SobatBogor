<?php
/**
 * core/Controller.php
 * Base Controller: provides view rendering and redirect helpers.
 */
class Controller {

    /**
     * Render sebuah view dengan layout (wrapper).
     * Contoh: $this->view('home/index', ['title' => 'Beranda', 'data' => $data])
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void {
        // Ekstrak data ke variabel lokal
        extract($data);

        // Jika layout = 'none', render view langsung tanpa wrapper
        if ($layout === 'none') {
            $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';
            if (!file_exists($viewFile)) {
                http_response_code(404);
                die("<h1>View not found: {$view}</h1>");
            }
            require $viewFile;
            return;
        }

        // Ambil konten view sebagai string (output buffering)
        ob_start();
        $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(404);
            die("<h1>View not found: {$view}</h1>");
        }
        require $viewFile;
        $content = ob_get_clean();

        // Render layout yang membungkus $content
        $layoutFile = ROOT_PATH . '/app/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Redirect ke URL lain
     */
    protected function redirect(string $url): void {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Kirim response JSON
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
