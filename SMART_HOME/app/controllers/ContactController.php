<?php
class ContactController extends Controller {
    public function index(): void {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf($_POST['csrf_token']??'');
            $name=trim($_POST['full_name']??''); $email=trim($_POST['email']??''); $message=trim($_POST['message']??'');
            $errors=[]; if($name==='') $errors[]='Vui lòng nhập họ tên.'; if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Email không hợp lệ.'; if($message==='') $errors[]='Vui lòng nhập nội dung liên hệ.';
            if($errors){ flash('error',implode('<br>',array_map('e',$errors))); $this->redirect('contact'); }
            Contact::insert(['full_name'=>$name,'email'=>$email,'message'=>$message,'status'=>'new']);
            flash('success','Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.'); $this->redirect('contact');
        }
        $this->view('contact/index',['pageTitle'=>'Liên hệ']);
    }
}
