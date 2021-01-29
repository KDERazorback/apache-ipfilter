<?php
class Rz_IpFilter_AnalyticRecords {
    public $conn;
    public $options;

    function __construct($mysql_connection, $options) {
        if (empty($mysql_connection) || !is_object($mysql_connection) || empty($options) || !is_object($options))
            die ("Invalid data passed to class constructor.");

        $this->conn = $mysql_connection->get_connection();
        $this->options = $options;
    }

    function __destruct() {
        unset($this->conn);
    }

    function getRecords($offset = 0, $count = 50, $groupby_alias = '') {
        if (!is_numeric($offset) || !is_numeric($count) || $offset < 0 || $count < 1)
            return NULL;

        if (!preg_match("/^[\w_\-]{0,16}$/", $groupby_alias)) {
            return NULL;
        }

        $table_analytics= "`" . $this->options->Schema . "`.`" . $this->options->TablePrefix . "analytic" . "`";
        $table_entries= "`" . $this->options->Schema . "`.`" . $this->options->TablePrefix .  "ipentry" . "`";
        $table_geoip= "`" . $this->options->GeoipSchema . "`.`" . $this->options->GeoipTablePrefix . $this->options->GeoipTableName . "`";

        $groupby = '';
        if (!empty($groupby_alias) && strlen($groupby_alias) >= 2) {
            if (strcasecmp($groupby_alias, "country_name") == 0) $groupby = $table_geoip . ".`country_name`";
            if (strcasecmp($groupby_alias, "country_code") == 0) $groupby = $table_geoip . ".`country_code`";
            if (strcasecmp($groupby_alias, "address") == 0) $groupby = $table_analytics . ".`remote_addr`";
            if (strcasecmp($groupby_alias, "address_cidr") == 0) $groupby = $table_entries . ".`ip_cidr`";
        }

        $query = "SELECT
            *,
            $table_entries.`ip_cidr` AS 'address_cidr',
            $table_geoip.`country_code`,
            $table_geoip.`country_name`";

        if (strlen($groupby) > 2) {
        $query = $query . ",
            COUNT(*) AS 'count'";
        }
        
        $query = $query . "
        FROM
            $table_analytics
        INNER JOIN
            $table_entries
        ON
            $table_analytics.`filter_id`=$table_entries.`id`
        INNER JOIN
            $table_geoip
        ON
            $table_entries.`ip_dec_min`=$table_geoip.`ip_dec`";

        if (strlen($groupby) > 2) {
            $query = $query . "
        GROUP BY 
            $groupby";

            $query = $query . "
        ORDER BY
            `count` DESC";
        } else {
            $query = $query . "
        ORDER BY
            `date` DESC";
        }

        if ($count >= 1) {
            $query = $query . "
        LIMIT
            $count";
        }

        if ($offset >= 0) {
            $query = $query . "
        OFFSET
            $offset";
        }

        $query = $query . ";";

        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute())
            die("Failed to connect to the Database server.");

        $stmt->store_result();

        $results = Rz_IpFilter_DbConnection::parse_mysqli_results($stmt);

        if (count($results) < 1)
        {
            $stmt->free_result();
            $stmt->close();

            return NULL; // Table empty
        }

        $stmt->free_result();
        $stmt->close();

        return $results;
    }

    function getRecordCount() {
        $table_analytics= "`" . $this->options->Schema . "`.`" . $this->options->TablePrefix . "analytic" . "`";

        $query = "SELECT
            COUNT(`id`)
        FROM
            $table_analytics;";
        
        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute())
            die("Failed to connect to the Database server.");

        $stmt->bind_result($count);

        if (!$stmt->fetch())
            die("Failed to connect to the Database server.");
        
        $result_count = $count;

        $stmt->close();

        if ($result_count < 0)
            $result_count = 0;
        
        return $result_count;
    }
}