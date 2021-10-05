<?php

namespace RazorSoftware\IpFilter;

class IPEntryCache
{
    public $id;
    public $ip_dec;
    public $filtered;
    public $filter;
    private $conn;

    public static function fromResult($result_set)
    {
        $obj = new IPEntryCache();

        $obj->id = $result_set['id'];
        $obj->ip_dec = $result_set['ip_dec'];
        $obj->filtered = $result_set['filtered'];
        $obj->filter = $result_set['filter'];

        return $obj;
    }

    public static function entryExists($ip_dec)
    {
        if (empty($ip_dec)) {
            throw new \Exception("Invalid Operation. Entry is not set.");
        }

        $conn = DbConnection::open_connection();
        $count = 0;

        $stmt = $conn->get_connection()->prepare("SELECT COUNT(`ip_dec`) FROM `" . RZIPF_DB_TABLE_IPENTRYCACHE . "` WHERE `ip_dec`=?");
        $stmt->bind_param("i", $ip_dec);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return ($count > 0);
    }

    public function insert()
    {
        if (isset($id)) {
            throw new \Exception("Invalid Operation. ID not expected.");
        }

        if (entryExists($this->ip_dec)) {
            throw new \Exception("Invalid Operation. Invalid IP on CACHE.");
        }

        $this->conn = DbConnection::open_connection();

        $stmt = $this->conn->get_connection()->prepare("INSERT INTO `" . $table . "`
        (`ip_dec`, `filtered`, `filter`)
            VALUES (?, ?, ?)");

        if (empty($this->ip_dec) || strlen($this->ip_dec) < 7 || strlen($this->ip_dec) > 15) {
            throw new \Exception("Invalid Operation. Invalid data.");
        }

        $stmt->bind_param("iii", $this->ip_dec, $this->filtered, $this->filter);

        $stmt->execute();

        $stmt->close();
    }

    public static function cacheSize()
    {
        $conn = DbConnection::open_connection();
        $count = 0;

        $stmt = $conn->get_connection()->prepare("SELECT COUNT(`ip_dec`) FROM `" . RZIPF_DB_TABLE_IPENTRYCACHE . "`");
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count;
    }
}
