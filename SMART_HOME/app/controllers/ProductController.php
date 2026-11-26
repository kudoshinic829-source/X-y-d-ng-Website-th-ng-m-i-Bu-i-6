<?php
class ProductController extends Controller {
    public function detail($id): void {
        $product = Product::find((int)$id);
        if (!$product || $product['status'] !== 'active') $this->abort404();
        $category = Category::find((int)$product['category_id']);
        $images = ProductImage::forProduct((int)$product['id']);
        $sql = "SELECT p.*, pi.file_name AS image FROM products p LEFT JOIN product_images pi ON pi.product_id=p.id AND pi.is_primary=1 WHERE p.category_id=:cid AND p.id<>:pid AND p.status='active' ORDER BY p.created_at DESC LIMIT 4";
        $stmt = Database::getInstance()->prepare($sql); $stmt->execute(['cid'=>$product['category_id'],'pid'=>$product['id']]);
        $this->view('product/detail', ['pageTitle'=>$product['name'],'product'=>$product,'category'=>$category,'images'=>$images,'related'=>$stmt->fetchAll()]);
    }
}
