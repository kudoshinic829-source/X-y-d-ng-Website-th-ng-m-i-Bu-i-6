<?php
class CartController extends Controller {
    public function index(): void {
        $cart=CartService::build();
        if ($cart['adjusted']) flash('error','Một số sản phẩm trong giỏ đã được điều chỉnh số lượng theo tồn kho hiện tại.');
        if (!empty($cart['removed'])) flash('error','Đã bỏ khỏi giỏ hàng vì sản phẩm ngừng kinh doanh hoặc hết hàng: '.implode(', ',$cart['removed']));
        $this->view('cart/index',['pageTitle'=>'Giỏ hàng','cart'=>$cart]);
    }
    public function add(): void {
        verify_csrf($_POST['csrf_token'] ?? '');
        $productId=(int)($_POST['product_id'] ?? 0); $qty=max(1,(int)($_POST['quantity'] ?? 1));
        $product=Product::find($productId);
        if (!$product || $product['status']!=='active') { $this->respond(false,'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'); return; }
        $stock=(int)$product['stock']; $current=(int)(Cart::items()[$productId] ?? 0);
        if ($stock<=0) { $this->respond(false,'Sản phẩm đã hết hàng.'); return; }
        if ($current+$qty>$stock) { Cart::setQuantity($productId,$stock); $this->respond(true,'Số lượng đã được điều chỉnh theo tồn kho tối đa ('.$stock.').'); return; }
        Cart::add($productId,$qty); $this->respond(true,'Đã thêm sản phẩm vào giỏ hàng.');
    }
    public function update(): void {
        verify_csrf($_POST['csrf_token'] ?? ''); $id=(int)($_POST['product_id'] ?? 0); $qty=(int)($_POST['quantity'] ?? 0); $p=Product::find($id);
        if ($p && $p['status']==='active' && $qty>0 && (int)$p['stock']>0) Cart::setQuantity($id,min($qty,(int)$p['stock'])); else Cart::remove($id);
        flash('success','Đã cập nhật giỏ hàng.'); $this->redirect('cart/index');
    }
    public function remove(): void { verify_csrf($_POST['csrf_token'] ?? ''); Cart::remove((int)($_POST['product_id'] ?? 0)); flash('success','Đã xóa sản phẩm khỏi giỏ hàng.'); $this->redirect('cart/index'); }
    public function clear(): void { verify_csrf($_POST['csrf_token'] ?? ''); Cart::clear(); flash('success','Đã xóa toàn bộ giỏ hàng.'); $this->redirect('cart/index'); }
    private function respond(bool $success,string $message): void {
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')==='XMLHttpRequest') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success'=>$success,'message'=>$message,'cart_count'=>Cart::totalQuantity()],JSON_UNESCAPED_UNICODE); exit;
        }
        flash($success?'success':'error',$message); $this->redirect('product/detail/'.(int)($_POST['product_id'] ?? 0));
    }
}
