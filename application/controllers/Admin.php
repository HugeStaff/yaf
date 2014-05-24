<?php
   //require "../yaf_classes.php";
   class AdminController extends Yaf_Controller_Abstract
   {
      public function init(){
         //使用layout页面布局
         $this->_layout = new LayoutPlugin('slide/layout.html');
         $this->dispatcher = Yaf_Registry::get("dispatcher");
         $this->dispatcher->registerPlugin($this->_layout);

         $this->_user = new AdminModel();
      }

      public function indexAction()
      {
         $this->getView()->assign("name",'yantze');
         $this->getView()->assign("content",'game,');

         $userData = $this->_user->selectAll();
         $this->getView()->assign("userData", $userData );

      }

      public function loginAction()
      {
         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $pwd      = $this->getRequest()->getPost('password');

            $ret  = $this->_user->loginUser($username, sha1(trim($pwd)));

            if($ret)
            {
               //$this->getView()->assign("content",'登陆成功！！');
               //$_SESSION['username']=$username."ddd"; //这种方式已经不使用了
               Yaf_Session::getInstance()->set("username",$username);
               exit("登录成功！");
            }
            else
            {
               //$this->getView()->assign("content",'登陆不成功！！');
               exit("登陆不成功！");
            }
         }

         return false;
      }

      public function addAction()
      {
         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($posts['password'] != $posts['repassword']){
               exit("两次密码不一致");
            }
            unset($posts['repassword']);
            unset($posts['submit']);
            $posts['is_del'] = 0;
            if($this->_user->insert($posts)){
               exit("添加成功");
            }else{
               exit("添加失败");
            }
         }
         return false;
      }
      public function editAction()
      {
         if($this->getRequest()->isPost()){
            $posts = $this->getRequest()->getPost();
            $posts['password'] = sha1($posts['password']);
            $posts['repassword'] = sha1($posts['repassword']);
            foreach($posts as $v){
               if(empty($v)){
                  exit("不能为空");
               }
            }
            if($posts['password'] != $posts['repassword']){
               exit("两次密码不一致");
            }
            $username = $posts['username'];
            unset($posts['repassword']);
            unset($posts['submit']);
            unset($posts['username']);
            $posts['is_del'] = 0;
            if($this->_user->update($username, $posts)){
               exit("修改成功");
            }else{
               exit("修改失败");
            }
         }
      }

      public function delAction()
      {
         if($this->getRequest()->isPost())
         {
            $username = $this->getRequest()->getPost('username');
            $password = $this->getRequest()->getPost('password');
            $password = sha1($password);
            if($this->_user->loginUser($username,$password))
            {
               if($this->_user->del($username)){
                  exit("删除成功");
               }else{
                  exit("删除失败");
               }
            }
            exit("删除失败");
         }
         return false;
      }

      public function LogoutAction()
      {
         unset($_SESSION['username']);
         header('Location:/admin/');
      }

   }