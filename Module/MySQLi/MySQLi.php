<?php
namespace KKur;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class MySQLi {

    private $_account;
    private \mysqli $_mysqli;

    public function __construct() {
        try {
            $this->_account = new class {

                private string $_username;
                private string $_password;
                private string $_database;
                private string $_host;
            
                public function __construct(string& $username = "Kaisa",
                                     string& $password = "KaisaArrived.",
                                     string& $database = "Blog",
                                     string& $host = "localhost") {
                    $this->_username = $username;
                    $this->_password = $password;
                    $this->_database = $database;
                    $this->_host = $host;
                }
            
                public function getUsername() : string {
                    return $this->_username;
                }
            
                public function getPassword() : string {
                    return $this->_password;
                }
            
                public function getDatabase() : string {
                    return $this->_database;
                }
            
                public function getHost() : string {
                    return $this->_host;
                }
            
            };

            $this->_mysqli = new \mysqli(
                $this->_account->getHost(),
                $this->_account->getUsername(),
                $this->_account->getPassword(),
                $this->_account->getDatabase()
            );
            if (!$this->_mysqli) {
                throw new \Exception("Database connection failed!");
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    public function __destruct() {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    public function isConnected() : bool {
        if ($this->_mysqli) {
            return true;
        } else {
            return false;
        }
    }

    public function close() : void {
        if ($this->isConnected()) {
            $this->_mysqli->close();
        }
    }

    public function &_get_mysqli() :\mysqli {
        return $this->_mysqli;
    }
    
}


