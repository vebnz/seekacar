<?php

class git extends CI_Controller {

        public function __construct() {
                parent::__construct();
        }

        public function pull() {				
                echo shell_exec("git pull");
        }
}

?>

