<?php
class AdminContactController extends AdminController {
    public function index(): void { $contacts=Contact::all('created_at DESC'); $this->adminView('admin/contact/index',['pageTitle'=>'Liên hệ khách hàng','contacts'=>$contacts]); }
    public function resolve(): void { if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); return; } verify_csrf($_POST['csrf_token']??''); $id=(int)($_POST['id']??0); if($id>0) Contact::update($id,['status'=>'resolved']); flash('success','Đã cập nhật trạng thái liên hệ.'); $this->redirect('admin/contact'); }
}
