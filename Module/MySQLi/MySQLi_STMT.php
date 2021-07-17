<?php
namespace KKur;

class MySQLi_STMT {
    
    public \mysqli_stmt $_mysqli_stmt;

    public function __construct(\mysqli& $mysqli, string& $query = null) {
        try {
            $this->_mysqli_stmt = new \mysqli_stmt($mysqli, $query);
            if (!$this->_mysqli_stmt) {
                throw new \Exception("Error Processing Request");
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    public function __destruct() {
        if ($this->_mysqli_stmt) {
            $this->_mysqli_stmt->close();
        }
    }

    public function close() : void {
        if ($this->_mysqli_stmt) {
            $this->_mysqli_stmt->close();
        }
    }

}

