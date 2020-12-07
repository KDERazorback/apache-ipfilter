<?php
class IPEntry {
    public $id;
    public $ip_cidr;
    public $enabled;
    public $date_added;
    public $ip_dec_min;
    public $ip_dec_max;
    private $conn;
    private $table = RZIPF_DB_TABLE_IPENTRY;

    static function fromResult($result_set) {
        $obj = new IPEntry();

        $obj->id = $result_set['id'];
        $obj->ip_cidr = $result_set['ip_cidr'];
        $obj->enabled = $result_set['enabled'];
        $obj->date_added = $result_set['date_added'];
        $obj->ip_dec_min = $result_set['ip_dec_min'];
        $obj->ip_dec_max = $result_set['ip_dec_max'];

        return $obj;
    }

    static function entryExists($ip_min) {
        if (empty($ip_min))
            throw new Exception("Invalid Operation. Entry is not set.");
    
        $conn = DbConnection::open_connection();

        $stmt = $conn->get_connection()->prepare("SELECT COUNT(`ip_dec_min`) FROM `" . RZIPF_DB_TABLE_IPENTRY . "` WHERE `ip_dec_min`=?");
        $stmt->bind_param("i", $ip_min);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return ($count > 0);
    }

    static function getMatchingEntry($ip_address) {
        $addr_dec = IPEntry::toDecAddress($ip_address);

        $conn = DbConnection::open_connection();

        $stmt = $conn->get_connection()->prepare("SELECT * FROM `" . RZIPF_DB_TABLE_IPENTRYCACHE . "` WHERE `ip_dec`=? LIMIT 1");
        $stmt->bind_param("i", $addr_dec);
        $stmt->execute();
        $stmt->store_result();
        $result_set = DbConnection::parse_mysqli_results($stmt);
        $stmt->free_result();
        $stmt->close();

        if ($result_set != NULL) {
            $cached = IPEntryCache::fromResult($result_set[0]);

            if ($cached->filter == NULL) {
                return NULL;
            }

            if ($cached->filtered == 0) {
                return NULL;
            }

            $stmt = $conn->get_connection()->prepare("SELECT * FROM `" . RZIPF_DB_TABLE_IPENTRY . "` WHERE `id`=?");
            $stmt->bind_param("i", $cached->filter);
            $stmt->execute();
            $stmt->store_result();
            $result_set = DbConnection::parse_mysqli_results($stmt);
            $stmt->free_result();
            $stmt->close();

            if ($result_set == NULL) {
                return NULL;
            }

            return IPEntry::fromResult($result_set[0]);
        }

        $stmt = $conn->get_connection()->prepare("SELECT * FROM `" . RZIPF_DB_TABLE_IPENTRY . "` WHERE `ip_dec_min`<=? AND `ip_dec_max`>=? AND `enabled`=1 LIMIT 1");
        $stmt->bind_param("ii", $addr_dec, $addr_dec);
        $stmt->execute();
        $stmt->store_result();
        $result_set = DbConnection::parse_mysqli_results($stmt);
        $stmt->free_result();
        $stmt->close();

        if ($result_set == NULL) {
            $stmt->close();
            $filtered = 0;
            $stmt = $conn->get_connection()->prepare("INSERT INTO `" . RZIPF_DB_TABLE_IPENTRYCACHE . "` 
            (`ip_dec`, `filtered`) 
                VALUES (?, ?)");
            $stmt->bind_param("ii", $addr_dec, $filtered);
            $stmt->execute();
            $stmt->close();

            return NULL;
        }

        $filtered = 1;
        $filter_entry = IPEntry::fromResult($result_set[0]);
        $filterid = $filter_entry->id;
        $stmt = $conn->get_connection()->prepare("INSERT INTO `" . RZIPF_DB_TABLE_IPENTRYCACHE . "` 
            (`ip_dec`, `filtered`, `filter`) 
                VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $addr_dec, $filtered, $filterid);
        $stmt->execute();
        $stmt->close();

        return $filter_entry;
    }

    function insert() {
        if (isset($id)) {
            throw new Exception("Invalid Operation. ID not expected.");
        }

        $this->calculate();

        if (IPFilter::entryExists($this->ip_dec_min))
            throw new Exception("Invalid Operation. Invalid IP CIDR.");

        $this->conn = DbConnection::open_connection();

        $stmt = $this->conn->get_connection()->prepare("INSERT INTO `" . $table . "`
        (`ip_cidr`, `enabled`, `ip_dec_min`, `ip_dec_max`)
            VALUES (?, ?, ?, ?)");

        if (empty($this->ip_cidr) || strlen($this->ip_cidr) < 7 || strlen($this->ip_cidr) > 18)
            throw new Exception("Invalid Operation. Invalid data.");

        $stmt->bind_param("siii", $this->ip_cidr, $this->enabled, $this->ip_dec_min, $this->ip_dec_max);

        $stmt->execute();

        $stmt->close();
    }

    function calculate() {
        $masklen = (int)substr($this->ip_cidr, strpos($this->ip_cidr, '/') + 1);
        $addrlen = 32 - $masklen;

        if ($addrlen < 1) {
            throw new Exception("Invalid Operation. Invalid Mask.");
        }
            
        $netaddress = IPEntry::toDecAddress($this->ip_cidr);
        $addr_max = (2 ^ $addrlen) - 1;
        $mask = (0xFFFFFFFF xor $addr_max) and $netaddress;

        $this->ip_dec_min = $mask + 1;
        $this->ip_dec_max = $mask + $addr_max;
        $enabled = 1;
    }

    static function toDecAddress($ip_cidr) {
        $offset = 0;
        $netaddress = 0;
        for ($i = 0; $i < 4; $i++) {
            $offset_last = strpos($ip_cidr, '.', $offset);
            if ($offset_last === FALSE)
                $offset_last = strpos($ip_cidr, '/', $offset);
            if ($offset_last === FALSE)
                $offset_last = strlen($ip_cidr);

            $netaddress += (int)substr($ip_cidr, $offset, $offset_last - $offset);

            if ($i < 3)
                $netaddress = $netaddress << 8;
            $offset = $offset_last + 1;
        }

        return $netaddress;
    }
}
?>