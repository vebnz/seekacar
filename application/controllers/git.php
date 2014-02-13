<?php

class git extends CI_Controller {

        public function __construct() {
                parent::__construct();
        }

        public function pull() {
			echo "<h2>Running git...</h2>";
			echo "<pre>";
			echo shell_exec("git status");
			echo "</pre>";

			echo "---";

			echo "<pre>";
			echo shell_exec("git pull");
			echo "</pre>";
		}
}

?>

