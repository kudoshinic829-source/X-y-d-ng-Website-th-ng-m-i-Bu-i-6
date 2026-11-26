<?php
abstract class Controller {
    protected function view(string $path, array $data = [], string|false $layout = 'main'): void {
        $viewFile = __DIR__ . '/../views/' . $path . '.php';
        if (!is_file($viewFile)) { http_response_code(404); require __DIR__ . '/../views/errors/404.php'; return; }
        if ($layout === false) { extract($data); require $viewFile; return; }
        $content = function () use ($viewFile, $data): void { extract($data); require $viewFile; };
        extract($data);
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) { http_response_code(500); exit('Không tìm thấy layout.'); }
        require $layoutFile;
    }
    protected function redirect(string $path): void { header('Location: ' . base_url($path)); exit; }
    protected function abort404(): void { http_response_code(404); require __DIR__ . '/../views/errors/404.php'; exit; }
}
