<?php

class AuthenticationController extends Zend_Controller_Action
{
    public function init(){
        
    }
    public function indexAction(){
        
    }
    public function logInAction(){
//        if(Zend_Auth::getInstance()->hasIdentity()){
//            $this->_redirect('index/index');
//        }
        
        $request = $this->getRequest();
        $form = new Form_LoginForm();
        if($request->isPost()){
            if($form->isValid($this->_request->getPost())){
                $authAdapter = $this->getAuthAdapter();
        
                $username = $form->getValue('username');
                $pass = $form->getValue('pass');
                $authAdapter->setIdentity($username)
                    ->setCredential($pass);
        
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
        
                if($result->isValid()){
                    $identity = $authAdapter->getResultRowObject();
            
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
            
                    $this->_redirect('index/index');
                }else{
                  $this->view->errorMsg = 'Неправильный логин и/или пароль.';
        }
            }
        }
// form 
        
        $this->view->form = $form;
        
        
        
    }
    public function logOutAction(){
//        $username = 'alex';
//        $pass = '0000';
//        $authAdapter = $this->getAuthAdapter();
//        $authAdapter->setIdentity($username)
//                    ->setCredential($pass);
//        
//        $auth = Zend_Auth::getInstance();
//        $result = $auth->authenticate($authAdapter);
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('index/index');
    }
    private function getAuthAdapter(){
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('users')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('pass');
        return $authAdapter;
    }
}
