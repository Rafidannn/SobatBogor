<?php
/**
 * core/Controller.php
 * Base Controller: provides view rendering and redirect helpers.
 * TODO: Implement in Tugas 1
 */
class Controller {
    protected function view(string $view, array $data = []): void {}
    protected function redirect(string $url): void {}
    protected function json(array $data, int $statusCode = 200): void {}
}
