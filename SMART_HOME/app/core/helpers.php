<?php
function base_url(string $path = ''): string { return rtrim(BASE_URL, '/') . ($path ? '/' . ltrim($path, '/') : '/'); }
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect_to(string $path): void { header('Location: ' . base_url($path)); exit; }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(string $token): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) { http_response_code(419); exit('Yêu cầu không hợp lệ (CSRF).'); }
}
function flash(string $type, string $message): void { $_SESSION['flash'][] = ['type'=>$type, 'message'=>$message]; }
function format_vnd($value): string { return number_format((float)$value, 0, ',', '.') . ' đ'; }
function product_image_url(?string $name, string $size = 'main'): string {
    if (!$name) return base_url('assets/img/no-image.svg');
    $folder = $size === 'thumb' ? 'thumbs/' : '';
    $path = __DIR__ . '/../../public/uploads/products/' . $folder . $name;
    return file_exists($path) ? base_url('uploads/products/' . $folder . rawurlencode($name)) : base_url('assets/img/no-image.svg');
}

function product_card_html(array $product): string {
    $url = e(base_url('product/detail/' . (int)$product['id']));
    $img = e(product_image_url($product['image'] ?? null, 'thumb'));
    $name = e($product['name'] ?? 'Sản phẩm');
    $price = format_vnd($product['price'] ?? 0);
    return '<div class="col"><a href="'.$url.'" class="text-decoration-none text-dark"><div class="card h-100 shadow-sm product-card"><img src="'.$img.'" class="card-img-top" loading="lazy" alt="'.$name.'"><div class="card-body d-flex flex-column"><h3 class="h6">'.$name.'</h3><p class="text-danger fw-bold mt-auto mb-0">'.$price.'</p></div></div></a></div>';
}
