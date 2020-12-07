<?php
    class Analytic {
        public $filter;
        public $remoteaddr;
        public $sessionid;
        public $useragent;
        public $referrer;
        private $conn;

        function __construct($filter) {
            $this->filter = $filter;
            $this->remoteaddr = $_SERVER['REMOTE_ADDR'];
            $this->sessionid = $_COOKIE['PHPSESSID'];
            $this->useragent =  $_SERVER['HTTP_USER_AGENT'];
            $this->referrer = $_SERVER['HTTP_REFERER'];
        }

        function save() {
            $this->conn = DbConnection::open_connection();

            $filterid = NULL;

            if ($this->filter != NULL) {
                $filterid = $this->filter->id;
            }

            $stmt = $this->conn->get_connection()->prepare("INSERT INTO `" . RZIPF_DB_TABLE_ANALYTICS . "`
            (`filter_id`, `remote_addr`, `sess_id`, `referrer`, `useragent`)
             VALUES (?, ?, ?, ?, ?)");

            $stmt->bind_param("issss", $filterid, $this->remoteaddr, $this->sessionid, $this->referrer, $this->useragent);

            $stmt->execute();

            $stmt->close();
        }
    }
?>