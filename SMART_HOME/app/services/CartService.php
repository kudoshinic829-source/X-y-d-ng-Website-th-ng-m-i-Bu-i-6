<?php
class CartService {
    public static function build(): array {
        $items = Cart::items();
        if (!$items) return ['lines'=>[], 'subtotal'=>0.0, 'adjusted'=>false, 'removed'=>[]];
        $products = Product::findManyWithImage(array_keys($items));
        $lines=[]; $subtotal=0.0; $adjusted=false; $removed=[];
        foreach ($items as $productId=>$qty) {
            $productId=(int)$productId; $product=$products[$productId] ?? null;
            if (!$product || $product['status'] !== 'active') { Cart::remove($productId); $removed[]=$product['name'] ?? ('#'.$productId); continue; }
            $qty=max(1,(int)$qty); $stock=(int)$product['stock'];
            if ($stock<=0) { Cart::remove($productId); $removed[]=$product['name']; continue; }
            if ($qty>$stock) { $qty=$stock; Cart::setQuantity($productId,$qty); $adjusted=true; }
            $price=(float)$product['price']; $lineTotal=$price*$qty; $subtotal+=$lineTotal;
            $lines[]=['product_id'=>$productId,'name'=>$product['name'],'price'=>$price,'quantity'=>$qty,'stock'=>$stock,'line_total'=>$lineTotal,'image'=>$product['image'] ?? null];
        }
        return ['lines'=>$lines,'subtotal'=>$subtotal,'adjusted'=>$adjusted,'removed'=>$removed];
    }
}
