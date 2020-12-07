<?php   
    include_once 'config.php';

    echo "RemoteAddr: " . $_SERVER['REMOTE_ADDR'];
    echo "<br>";
    echo "PHPSESSID: " . $_COOKIE['PHPSESSID'];
    echo "<br>";
    echo "UserAgent: " . $_SERVER['HTTP_USER_AGENT'];
    echo "<br>";
    echo "Referer: " . $_SERVER['HTTP_REFERER'];

    echo "<br><br>";
    $address_dec = IPEntry::toDecAddress($_SERVER['REMOTE_ADDR']);
    echo "RemoteAddr(Dec): " . $address_dec;
    echo "<br>";
    $exists_cache = IPEntryCache::entryExists($address_dec);
    echo "EntryExists(Cached): " . (($exists_cache) ? "YES" : "NO");
    echo "<br>";

    $entry = IPEntry::getMatchingEntry($_SERVER['REMOTE_ADDR']);
    echo "EntryExists: " . (($entry == NULL) ? "NO" : "YES");
    echo "<br>";

    echo "CacheEntries: " . IPEntryCache::cacheSize();
    echo "<br>";

    if ($entry != NULL) {
        echo "<h1>Filter Rule applied</h1>";
        echo "<div style=\"padding-left=5\">";
        echo "Id: " . $entry->id . "<br>";
        echo "CIDR: " . $entry->ip_cidr . "<br>";
        echo "Enabled: " . $entry->enabled . "<br>";
        echo "Date: " . $entry->date_added . "<br>";
        echo "Min IP: " . $entry->ip_dec_min . "<br>";
        echo "Max IP: " . $entry->ip_dec_max . "<br>";
        echo "</div>";
    }


    header("X-RZ-Floodgate: 1", TRUE);
?>