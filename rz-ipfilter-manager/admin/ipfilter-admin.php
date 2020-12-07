<?php
if (!defined('ABSPATH')) {
    //rz_ipfilter_log ( 'no ABSPATH defined in ipfilter-admin.php');
    wp_die ( 'Invalid call to ipfilter-admin.php on IPFilter plugin. No ABSPATH defined' );
}
if (!defined('RZ_IPFILTER_VERSION')) { 
    //rz_ipfilter_log ( 'no RZ_IPFILTER_VERSION defined in ipfilter-admin.php');
    wp_die ( 'Invalid call to ipfilter-admin.php on IPFilter plugin. No VERSION variable defined' );
}


class RZ_IpFilter {
    public $version = '';
    public $directory = '';
    public $options = '';
    public $optionsArr;
    private $iconSvg="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCA4NzYgODY1IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA4NzYgODY1IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMzIiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgZD0iTTYyNyw2MTJjMCwxMy4yLTEwLjgsMjQtMjQsMjRINDIKCQljLTEzLjIsMC0yNC0xMC44LTI0LTI0VjQxYzAtMTMuMiwxMC44LTI0LDI0LTI0aDU2MWMxMy4yLDAsMjQsMTAuOCwyNCwyNFY2MTJ6Ii8+CjwvZz4KPGc+Cgk8cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMzIiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgZD0iTTU0NSw0NzNjMCwxMy4yLTEwLjgsMjQtMjQsMjRoLTYyCgkJYy0xMy4yLDAtMjQsMTAuMS0yNCwyMi41UzQyNC4yLDU0Miw0MTEsNTQySDIyNy42Yy0xMy4yLDAtMjQuNC0xMC4xLTI0LjgtMjIuNWMtMC40LTEyLjQtMTEuNi0yMi41LTI0LjgtMjIuNWgtNTQuNwoJCWMtMTMuMiwwLTI0LTEwLjgtMjQtMjRjMCwwLDAtMzM2LjQsMC0zNDkuNHMxMC44LTIzLjYsMjQtMjMuNkg1MjFjMTMuMiwwLDI0LDEwLjgsMjQsMjRWNDczeiIvPgo8L2c+CjxsaW5lIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIzMiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiB4MT0iMTQ3IiB5MT0iOTciIHgyPSIxNDciIHkyPSIxNzciLz4KPGxpbmUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjMyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHgxPSIxOTciIHkxPSI5NyIgeDI9IjE5NyIgeTI9IjE3NyIvPgo8bGluZSBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMzIiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgeDE9IjI0NyIgeTE9Ijk3IiB4Mj0iMjQ3IiB5Mj0iMTc3Ii8+CjxsaW5lIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIzMiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiB4MT0iMjk3IiB5MT0iOTciIHgyPSIyOTciIHkyPSIxNzciLz4KPGxpbmUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjMyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHgxPSIzNDciIHkxPSI5NyIgeDI9IjM0NyIgeTI9IjE3NSIvPgo8bGluZSBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMzIiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgeDE9IjM5NyIgeTE9Ijk3IiB4Mj0iMzk3IiB5Mj0iMTc3Ii8+CjxsaW5lIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIzMiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiB4MT0iNDQ3IiB5MT0iOTciIHgyPSI0NDciIHkyPSIxNzciLz4KPGxpbmUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjMyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHgxPSI0OTciIHkxPSI5NyIgeDI9IjQ5NyIgeTI9IjE3NyIvPgo8cmVjdCB4PSI1NiIgeT0iNTQ1LjIiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIzMiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiB3aWR0aD0iMTEzIiBoZWlnaHQ9IjQ0LjciLz4KPGxpbmUgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjMyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHgxPSI3NTguMyIgeTE9Ijc0Ny4zIiB4Mj0iNjU1LjciIHkyPSI2NDQuNyIvPgo8bGluZSBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iMzIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgeDE9IjY1NS43IiB5MT0iNzQ3LjMiIHgyPSI3NTguMyIgeTI9IjY0NC43Ii8+CjxwYXRoIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSIzMiIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBkPSJNODYxLDY5NmMwLTg1LTY5LTE1NC0xNTQtMTU0cy0xNTQsNjktMTU0LDE1NAoJczY5LDE1NCwxNTQsMTU0Uzg2MSw3ODEsODYxLDY5NnoiLz4KPHJlY3QgeD0iNDc3IiB5PSI1NDUuMiIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjMyIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHdpZHRoPSIxMTMiIGhlaWdodD0iNDQuNyIvPgo8L3N2Zz4K";


    public function settings_html() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>These page controls how the plugin will connect to the IP Filter database and which GeoIP database will be used for IP-2-Location resolution.</p>

            <form method="post" action="options.php"> 
                <?php
                settings_fields( RZ_IPFILTER_OPTIONS );
                
                do_settings_sections('database');
                do_settings_sections('geoip');
                do_settings_sections('endpoint');

                ?>
                <br><h3>Target tables:</h3>
                <br><strong>Analytics:</strong> <?php echo htmlspecialchars($this->options->Schema . "." . $this->options->TablePrefix . "analytic") ?></p>
                <br><strong>IPTable:</strong> <?php echo htmlspecialchars($this->options->Schema . "." . $this->options->TablePrefix .  "ipentry") ?></p>
                <br><strong>GeoIP:</strong> <?php echo htmlspecialchars($this->options->GeoipSchema . "." . $this->options->GeoipTablePrefix . $this->options->GeoipTableName) ?></p>
                <br>
                <?php

                submit_button( __( 'Save Settings', 'textdomain' ) );
                ?>
            </form>
        </div>
        <?php
    }

    public function analytics_html() {
        # Re-Load current settings
        $options=new RZ_IpFilter_Options();
        $options->load();
        $optionsArr = $options->getArray();


        # Include, jQuery, jQueryUI and jTable
        wp_enqueue_script(
            'jquery-ui',
            RZ_IPFILTER_URL . '/external/jquery-ui/jquery-ui.min.js', 
            array( 'jquery' ) 
        );
        wp_enqueue_script(
            'jquery-jtable',
            RZ_IPFILTER_URL . '/external/jtable/jquery.jtable.min.js', 
            array( 'jquery', 'jquery-ui-tabs' ) 
        );
        wp_enqueue_script(
            'iptable-hits',
            RZ_IPFILTER_URL . '/js/iptable_hits.js', 
            array( 'jquery' ) 
        );
        # Include style for jTable
        wp_enqueue_style(
            'jtable-blue',
            RZ_IPFILTER_URL . '/external/jtable/themes/metro/blue/jtable.min.css'
        );

        # Set required JS variables
        ?>
        <script type="text/javascript">
            var iptable_endpoint_url="<?php
                if (empty($options->Endpoint)) {
                    echo (RZ_IPFILTER_URL . '/api/ipfilter_json.php');
                } else {
                    echo ($options->Endpoint);
                } ?>";
            var countryimg_endpoint_url="<?php
                    echo (RZ_IPFILTER_URL . '/api/flag_img.php');
                ?>";
        </script>
        <?php

        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>These page displays a list of connection attempts that were filtered by the IP Filter driver due to the presence of a matching rule added by an Administrator.</p>
            <div id="IPFilterHitsTable"></div>
            <br><br>
            <button id="refresh" name="refresh" class="button button-primary" onclick="jQuery('#IPFilterHitsTable').jtable('load');">Refresh</button>
        </div>
        <?php
    }

    public function topentries_html() {
        # Re-Load current settings
        $options=new RZ_IpFilter_Options();
        $options->load();
        $optionsArr = $options->getArray();

        # Include, jQuery, jQueryUI and jTable
        wp_enqueue_script(
            'jquery-ui',
            RZ_IPFILTER_URL . '/external/jquery-ui/jquery-ui.min.js', 
            array( 'jquery' ) 
        );
        wp_enqueue_script(
            'jquery-jtable',
            RZ_IPFILTER_URL . '/external/jtable/jquery.jtable.min.js', 
            array( 'jquery', 'jquery-ui-tabs' ) 
        );
        wp_enqueue_script(
            'iptable-hits',
            RZ_IPFILTER_URL . '/js/iptable_tophits.js', 
            array( 'jquery' ) 
        );
        # Include style for jTable
        wp_enqueue_style(
            'jtable-blue',
            RZ_IPFILTER_URL . '/external/jtable/themes/metro/blue/jtable.min.css'
        );

        # Set required JS variables
        ?>
        <script type="text/javascript">
            var iptable_endpoint_url="<?php
                if (empty($options->Endpoint)) {
                    echo (RZ_IPFILTER_URL . '/api/ipfilter_json.php');
                } else {
                    echo ($options->Endpoint);
                } ?>";

            var countryimg_endpoint_url="<?php
                    echo (RZ_IPFILTER_URL . '/api/flag_img.php');
                ?>";
        </script>
        <?php

        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p>Top offending addresses and countries from the IPFilter database.</p>
            <div class="container-fluid">
                <div class="row">
                    <div id="IPFilterTopAddressHitsTable" class="col-md-6"></div>
                    <div class="col-md-6 wrap">
                        <div id="IPFilterTopCountriesHitsTable"></div>
                        <br><br>
                        <div id="IPFilterTopNetworksHitsTable"></div>
                    </div>
                </div>
            </div>
            <br><br>
            <button id="refresh" name="refresh" class="button button-primary" onclick="jQuery('#IPFilterTopAddressHitsTable').jtable('load'); jQuery('#IPFilterTopCountriesHitsTable').jtable('load'); jQuery('#IPFilterTopNetworksHitsTable').jtable('load');">Refresh</button>
        </div>
        <?php
    }

    public function adminHook() {
        add_menu_page(
            'IPFilter Analytics',
            'IPFilter',
            RZ_IPFILTER_CAP_MANAGE,
            'rz-ipfilter-manage',
            array ( $this, 'analytics_html' ),
            $this->iconSvg
        );

        add_submenu_page(
            'rz-ipfilter-manage',
            'IPFilter - Top Offenders',
            'Top Entries',
            RZ_IPFILTER_CAP_MANAGE,
            'rz-ipfilter-manage-topentries',
            array ( $this, 'topentries_html' )
        );

        add_submenu_page(
            'rz-ipfilter-manage',
            'IPFilter Settings',
            'Settings',
            RZ_IPFILTER_CAP_MANAGE,
            'rz-ipfilter-manage-settings',
            array ( $this, 'settings_html' )
        );
    }

    public function __construct($dir, $ver, $opt) {
        
        if (empty($ver) || empty($dir) || empty($opt)) {
            wp_die("Invalid data passed to RZ_IpFilter constructor.");
        }
        
        $this->directory = $dir;
        $this->version = $ver;
        $this->options = $opt;
        $this->optionsArr = $opt->getArray();

        add_action( 'admin_menu', array ( $this, 'adminHook' ));
    }

    function settings_db_html() {
        echo '<p>Modify connection details to the IPTable MySQL/MariaDB Database for Analytics data management.</p>';

        echo '<table>';
        do_settings_fields( RZ_IPFILTER_OPTIONS, 'ipfilter_db');
        echo '</table>';
    }

    function settings_geoip_html() {
        echo '<p>Modify connection details for the GeoIP Table used to resolve IP-to-Country pairs.</p>';
        echo '<table>';
        do_settings_fields( RZ_IPFILTER_OPTIONS, 'ipfilter_geoip');
        echo '</table>';
    }

    function settings_endpoint_html() {
        echo '<p>Modify Advanced IPTable Plugin settings.</p>';
        echo '<table>';
        do_settings_fields( RZ_IPFILTER_OPTIONS, 'ipfilter_endpoint');
        echo '</table>';
    }

    function setting_field_textarea($data) {
        $key=$data['key'];
        echo "<input id='$key' name='". RZ_IPFILTER_OPTIONS . "[" . $key . "]' size='40' type='text' value='{$this->optionsArr[$key]}' />";
        if (!empty($data['description']))
            echo '<p class="description">' . esc_html( $data['description'] ) .'</p>';
    }

    function setting_field_password($data) {
        $key=$data['key'];
        echo "<input type='password' id='$key' name='". RZ_IPFILTER_OPTIONS . "[" . $key . "]' size='40' type='text' value='{$this->optionsArr[$key]}' />";
        if (!empty($data['description']))
            echo '<p class="description">' . esc_html( $data['description'] ) .'</p>';
    }

    function settings_validate( $input ) {
        if (!is_object($this->options))
            $this->options = new RZ_IpFilter_Options();
        $options = $this->options;
        $options->load();
        $optionsArr = $options->getArray();

        $key = 'endpoint';
        if (empty($input[$key]) || preg_match('/^[a-z\/0-9_-]{0,32}$/i', $input[$key])) {
            $options->Endpoint = trim($input[$key]);
        }

        $key = 'db_hostname';
        if (!empty($input[$key]) && preg_match('/^[a-z]+[a-z0-9_\.-]{5,32}$/i', $input[$key])) {
            $options->Hostname = trim($input[$key]);
        }

        $key = 'db_schema';
        if (!empty($input[$key]) && preg_match('/^[a-z_]+[a-z0-9_\.-]{2,16}$/i', $input[$key])) {
            $options->Schema = trim($input[$key]);
        }

        $key = 'db_username';
        if (!empty($input[$key]) && preg_match('/^[\w0-9_\-\.]{2,32}$/i', $input[$key])) {
            $options->Username = trim($input[$key]);
        }

        $key = 'db_password';
        if (!empty($input[$key]) && preg_match('/^.{8,32}$/i', $input[$key])) {
            $options->Password = trim($input[$key]);
        }

        $key = 'db_tableprefix';
        if (empty($input[$key]) || preg_match('/^[a-z-_]*[a-z0-9_\.-]{0,8}$/i', $input[$key])) {
            $options->TablePrefix = trim($input[$key]);
        }

        $key = 'geoip_schema';
        if (!empty($input[$key]) && preg_match('/^[a-z_]+[a-z0-9_\.-]{2,16}$/i', $input[$key])) {
            $options->GeoipSchema = trim($input[$key]);
        }

        $key = 'geoip_tablename';
        if (!empty($input[$key]) && preg_match('/^[a-z_]+[a-z0-9_-]{2,16}$/i', $input[$key])) {
            $options->GeoipTableName = trim($input[$key]);
        }

        $key = 'geoip_tableprefix';
        if (empty($input[$key]) || preg_match('/^[a-z-_]*[a-z0-9_\.-]{0,8}$/i', $input[$key])) {
            $options->GeoipTablePrefix = trim($input[$key]);
        }

        $optionsArr = $options->getArray();
        $options->save();

        return $optionsArr;
    }

    public function init_menu() {
        register_setting( RZ_IPFILTER_OPTIONS, RZ_IPFILTER_OPTIONS, array( $this, 'settings_validate' ) );

        add_settings_section('ipfilter_endpoint', 'Endpoint Settings', array ( $this, 'settings_endpoint_html' ), 'endpoint');
        add_settings_section('ipfilter_db', 'Database Settings', array ( $this, 'settings_db_html' ), 'database');
        add_settings_section('ipfilter_geoip', 'GeoIP Database Settings', array ( $this, 'settings_geoip_html' ), 'geoip');

        add_settings_field('ipfilter_endpoint', 'Endpoint URL', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_endpoint', array( 'label_for' => 'ipfilter_endpoint', 'key' => 'endpoint', 'description' => 'Leave blank to use the default endpoint on the current server.' ));
        add_settings_field('ipfilter_db_hostname', 'Server Hostname', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_db', array( 'key' => 'db_hostname' ));
        add_settings_field('ipfilter_db_schema', 'Database Schema', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_db', array( 'key' => 'db_schema' ));
        add_settings_field('ipfilter_db_username', 'Username', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_db', array( 'key' => 'db_username' ));
        add_settings_field('ipfilter_db_password', 'Password', array ( $this, 'setting_field_password' ), RZ_IPFILTER_OPTIONS, 'ipfilter_db', array( 'key' => 'db_password' ));
        add_settings_field('ipfilter_db_prefix', 'Table Prefix', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_db', array( 'key' => 'db_tableprefix' ));

        add_settings_field('ipfilter_geoip_schema', 'Database Schema', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_geoip', array( 'key' => 'geoip_schema' ));
        add_settings_field('ipfilter_geoip_tablename', 'Table Name', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_geoip', array( 'key' => 'geoip_tablename' ));
        add_settings_field('ipfilter_geoip_tableprefix', 'Table Prefix', array ( $this, 'setting_field_textarea' ), RZ_IPFILTER_OPTIONS, 'ipfilter_geoip', array( 'key' => 'geoip_tableprefix' ));
    }
}

?>