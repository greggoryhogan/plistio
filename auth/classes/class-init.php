<?php 
class Plistio {
    public function _construct() {
        $this->autoload;
    }

    private function autoload() {
        require_once($_SERVER['DOCUMENT_ROOT'].'/required/plistio-config.php');
        echo 'autoloaading';
        
    }
}
new Plistio();