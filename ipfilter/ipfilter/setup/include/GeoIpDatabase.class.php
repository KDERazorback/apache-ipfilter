<?php
namespace RazorSoftware\IpFilter\Setup;

class GeoIpDatabase {
    private $conn;
    private $table;
    private $statement;
    private $exportCount = 0;

    private $regions = array();

    function __construct($host, $user, $pass, $schema, $table) {
        if (empty($host) || empty($user) || empty($schema) || empty($table))
            throw new \Exception('Invalid arguments');

        $conn = new \mysqli($host, $user, $pass, $schema);

        if (!is_object($conn))
            throw new \Exception('Cannot connect to database server');

        $this->conn = $conn;
        $this->table = $table;
    }

    function getExportCount() { return $this->exportCount; }

    function select(array $regions) {
        if (empty($regions))
            throw new \Exception("Invalid argument");
        if ($this->statement !== NULL)
            throw new \Exception("Invalid Operation. This instance is already prepared");

        foreach ($regions as $region) {
            if (!in_array($region, $this->regions, true))
                array_push($this->regions, $region);
        }

        return $this;
    }

    function execute() {
        if ($this->statement !== NULL)
            throw new \Exception("Invalid Operation. This instance is already prepared");

        $regionCodes = preg_grep('/^[0-9A-Za-z\-]+$/', $this->regions);
        for ($i=0; $i < count($regionCodes); $i++) { 
            $regionCodes[$i] = "'" . $regionCodes[$i] . "'";
        }

        $sql = "SELECT
        `ip_cidr`,
        `ip_dec` AS 'ip_dec_min',
        (`ip_dec`+`address_count`) AS 'ip_dec_max',
        1 AS 'enabled'
        FROM
            `" . $this->table . "`
        WHERE
            `" . $this->table . "`.`country_code` IN (
                " . implode (',', $regionCodes) . "
            )
        ORDER BY
            `ip_dec_min` ASC;";

        $stmt = $this->conn->prepare($sql);

        if (!is_object($stmt)) {
            throw new \Exception("Internal SQL error");
        }

        $stmt->attr_set(MYSQLI_STMT_ATTR_CURSOR_TYPE, MYSQLI_CURSOR_TYPE_READ_ONLY);
        $stmt->attr_set(MYSQLI_STMT_ATTR_PREFETCH_ROWS, 200);
        $stmt->execute();

        $this->statement = $stmt;

        return $this;
    }

    function iterate($iterator) {
        $this->statement->bind_result(
            $ip_cidr,
            $ip_dec_min,
            $ip_dec_max,
            $enabled
        );

        while ($this->statement->fetch()) {
            $this->exportCount++;

            $data = array(
                'ip_cidr' => $ip_cidr,
                'ip_dec_min' => $ip_dec_min,
                'ip_dec_max' => $ip_dec_max,
                'enabled' => $enabled
            );

            if ($iterator($this->exportCount, $data) !== TRUE)
                break;
        }
        
        return $this;
    }

    function reset() {
        $this->exportCount = 0;
        if ($this->statement !== NULL)
            $this->statement->close();
        $this->statement = NULL;

        return $this;
    }

    function close() {
        if ($this->statement !== NULL)
            $this->statement->close();
        $this->statement = NULL;
        $this->conn->close();
        $this->conn = NULL;
    }
}

?>