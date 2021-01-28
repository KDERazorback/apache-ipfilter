<?php
namespace RazorSoftware\IpFilter;

class DbConnection {
    private $conn;

    /*
        * Parse multi row mysqli results dynamically
        * per table columns
        * @param mysqli statment
        * @returns array
        */
    static function parse_mysqli_results($stmt)
    {
        $params = array();
        $data = array();
        $results = array();
        $meta = $stmt->result_metadata();
        
        while($field = $meta->fetch_field())
            $params[] = &$data[$field->name]; // pass by reference
        
        call_user_func_array(array($stmt, 'bind_result'), $params);
        
        while($stmt->fetch())
        {
            foreach($data as $key => $val) 
            {
                $c[$key] = $val;
            }
            $results[] = $c;
        }
        return $results;
    }

    function __construct() {
        $this->conn = new \mysqli(RZIPF_DB_HOST, RZIPF_DB_USER, RZIPF_DB_PASSWORD, RZIPF_DB_SCHEMA);
    }

    function __destruct() {
        $this->conn->close();
    }

    function get_connection() {
        return $this->conn;
    }

    static function open_connection() {
        return new DbConnection();
    }
}
?>