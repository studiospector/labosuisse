<?php
/*
  Plugin Name: WooCommerce Calicantus
  Plugin URI: https://calicant.us
  Description: Integrazione con Calicantus
  Author: Calicantus
  Author URI: https://calicant.us
  Version: 1.0.0

 */

if (!defined('ABSPATH')) {
    exit;
}

require_once("validation.class.php");
require_once("ftps.class.php");

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    if (!class_exists('WC_Calicantus')) {

        class WC_Calicantus {

            private $options;
            private $xml_data;
            // elenco corrieri disponibili
            private $carriers = array(
                array(
                    'id' => 'brt',
                    'name' => 'Bartolini',
                ),
                array(
                    'id' => 'dhl',
                    'name' => 'DHL',
                ),
                array(
                    'id' => 'fedex',
                    'name' => 'FedEx',
                ),
                array(
                    'id' => 'gls',
                    'name' => 'GLS',
                ),
                array(
                    'id' => 'poste_italiane',
                    'name' => 'Poste Italiane',
                ),
                array(
                    'id' => 'sda',
                    'name' => 'SDA',
                ),
                array(
                    'id' => 'tnt',
                    'name' => 'TNT',
                ),
                array(
                    'id' => 'ups',
                    'name' => 'UPS',
                ),
                array(
                    'id' => 'AMZN_IT',
                    'name' => 'Amazon Shipping',
                ),
            );
            private $carriers_shipping_service = array(
                array(
                    'id' => 'air',
                    'name' => 'Air',
                ),
                array(
                    'id' => 'sea',
                    'name' => 'Sea',
                ),
                array(
                    'id' => 'truck',
                    'name' => 'Truck',
                ),
            );
            private $ftp_primario_deliveries = '';
            private $ftp_primario_documentation = '';
            private $ftp_primario_availabilities = '';
            private $ftp_secondario_deliveries = '';
            private $ftp_secondario_documentation = '';
            private $ftp_secondario_status = '';
            private $ftp_backup_deliveries = '';
            private $ftp_backup_status = '';
            private $ftp_backup_availabilities = '';
            private $bcc_addresses = '';
            private $modalita = 'local';
            private $ftps_host = '';
            private $ftps_port = '';
            private $ftps_user = '';
            private $ftps_password = '';
            private $cache_ttl = 0;
            private $global_options = '';
            private $document_cache_ad = '';
            private $document_cache_time_ad = '';
            private $document_cache_gd = '';
            private $document_cache_time_gd = '';
            private $temp_dir;

            public function __construct() {
                add_action('admin_enqueue_scripts', array($this, 'admin_enqueue')); //inserisce gli script necessari in admin
                add_action('admin_menu', array($this, 'custom_page')); //genera la pagina di opzioni in admin
                add_action('admin_init', array($this, 'page_init')); //inizializza le opzioni
                add_action('admin_notices', array(&$this, 'plugin_check')); //controlla l'esistenza delle cartelle per i file XML
                add_action('woocommerce_email_order_meta', array($this, 'woo_add_order_notes_to_email'), 10, 3); //aggiunge il tracking alla mail inviata al cliente
                add_filter('manage_edit-shop_order_columns', array($this, 'wc_new_order_column')); //aggiunge una colonna nella lista degli ordini
                add_action('manage_shop_order_posts_custom_column', array($this, 'wc_new_order_column_data')); //riempie la nuova colonna aggiunta nella lista degli ordini
                add_action('woocommerce_order_actions', array($this, 'add_reset_action')); //aggiunge l'option per il reset dell'ordine nel select Azioni Ordine
                add_action('woocommerce_order_action_wc_custom_order_reset', array($this, 'reset_order_delivery')); //cancella il log di delivery esportata. In questo modo il file XML viene generato nuovamente.
                add_action('woocommerce_view_order', array($this, 'order_documents'), 20); //mostra i documenti nella pagina dell'ordine nell'admin
                add_action('add_meta_boxes', array($this, 'wporg_add_custom_box')); //aggiunge il meta box documenti nella pagina dell'ordine
                add_filter('woocommerce_email_attachments', array($this, 'attach_documents'), 10, 3); //aggiunge l'allegato alla mail
                add_filter('woocommerce_checkout_fields', array($this, 'add_billing_custom_field'), 50, 1); //aggiunge i campi necessari nell'indirizzo di fatturazione
                add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'billing_custom_field_admin_order_meta'), 10, 1); //mostra i campi aggiunti nell'indirizzo di fatturazione nell'admin
                add_action('wp_footer', array($this, 'billing_country_update_checkout'), 50); //aggiunge il js necessario per la validazione degli indirizzi
                add_action('woocommerce_checkout_process', array($this, 'validate_checkout')); //validazione indirizzo checkout lato server
                add_action('init', array($this, 'register_giacenza_aperta_order_status')); //aggiunge lo stato dell'ordine "Giacenza aperta"
                add_action('init', array($this, 'register_giacenza_chiusa_order_status')); //aggiunge lo stato dell'ordine "Giacenza chiusa"
                add_action('init', array($this, 'register_spedito_order_status')); //aggiunge lo stato dell'ordine "Spedito"
                add_filter('wc_order_statuses', array($this, 'add_order_statuses')); //aggiunge i nuovi stati ordine in admin/frontend
                register_activation_hook(__FILE__, array(&$this, 'cron_activation')); //aggiunge i cron di WordPress
                register_deactivation_hook(__FILE__, array(&$this, 'cron_deactivation')); //disattiva i cron di WordPress
                add_action('calicantus_availabilities', array(&$this, 'check_availabilities')); //modifica le quantità dei prodotti a seconda del file xml.
                add_action('calicantus_deliveries', array(&$this, 'check_deliveries')); //avvia la procedura di esportazione per le deliveries
                add_action('calicantus_status', array(&$this, 'check_status')); //modifica lo stato dell'ordine in relazione al file XML caricato
                add_action('calicantus_documentation', array(&$this, 'cron_documentation')); //restitisce un array contenente i file che vengono allegati all'email inviata al cliente tramite la funzione cron_documentation()
                add_filter('woocommerce_email_headers', array(&$this, 'add_bcc_to_emails'), 10, 2); //aggiunge gli eventuali indirizzi bcc alle email

                $this->global_options = get_option('calicantus_options');
                if (is_array($this->global_options)) {
                    if (isset($this->global_options['calicantus_ftp_primario_deliveries']))
                        $this->ftp_primario_deliveries = $this->global_options['calicantus_ftp_primario_deliveries'];
                    if (isset($this->global_options['calicantus_ftp_primario_documentation']))
                        $this->ftp_primario_documentation = $this->global_options['calicantus_ftp_primario_documentation'];
                    if (isset($this->global_options['calicantus_ftp_primario_availabilities']))
                        $this->ftp_primario_availabilities = $this->global_options['calicantus_ftp_primario_availabilities'];
                    if (isset($this->global_options['calicantus_ftp_secondario_deliveries']))
                        $this->ftp_secondario_deliveries = $this->global_options['calicantus_ftp_secondario_deliveries'];
                    if (isset($this->global_options['calicantus_ftp_secondario_documentation']))
                        $this->ftp_secondario_documentation = $this->global_options['calicantus_ftp_secondario_documentation'];
                    if (isset($this->global_options['calicantus_ftp_secondario_statuses']))
                        $this->ftp_secondario_status = $this->global_options['calicantus_ftp_secondario_statuses'];
                    if (isset($this->global_options['calicantus_ftp_backup_deliveries']))
                        $this->ftp_backup_deliveries = $this->global_options['calicantus_ftp_backup_deliveries'];
                    if (isset($this->global_options['calicantus_ftp_backup_statuses']))
                        $this->ftp_backup_status = $this->global_options['calicantus_ftp_backup_statuses'];
                    if (isset($this->global_options['calicantus_ftp_backup_availabilities']))
                        $this->ftp_backup_availabilities = $this->global_options['calicantus_ftp_backup_availabilities'];
                    if (isset($this->global_options['calicantus_bcc_addresses']))
                        $this->bcc_addresses = $this->global_options['calicantus_bcc_addresses'];
                    if (isset($this->global_options['calicantus_modalita']))
                        $this->modalita = $this->global_options['calicantus_modalita'];
                    if (isset($this->global_options['calicantus_ftps_host']))
                        $this->ftps_host = $this->global_options['calicantus_ftps_host'];
                    if (isset($this->global_options['calicantus_ftps_port']))
                        $this->ftps_port = $this->global_options['calicantus_ftps_port'];
                    if (isset($this->global_options['calicantus_ftps_user']))
                        $this->ftps_user = $this->global_options['calicantus_ftps_user'];
                    if (isset($this->global_options['calicantus_ftps_password']))
                        $this->ftps_password = $this->global_options['calicantus_ftps_password'];
                    if (isset($this->global_options['calicantus_cache_ttl'])) {
                        $this->cache_ttl = $this->global_options['calicantus_cache_ttl'];
                        if ($this->cache_ttl == '')
                            $this->cache_ttl = 3600;
                    }
                }
                if (get_option('calicantus_document_cache_ad')) {
                    $this->document_cache_ad = get_option('calicantus_document_cache_ad');
                }
                if (get_option('calicantus_document_cache_time_ad')) {
                    $this->document_cache_time_ad = get_option('calicantus_document_cache_time_ad');
                }
                if (get_option('calicantus_document_cache_gd')) {
                    $this->document_cache_gd = get_option('calicantus_document_cache_gd');
                }
                if (get_option('calicantus_document_cache_time_gd')) {
                    $this->document_cache_time_gd = get_option('calicantus_document_cache_time_gd');
                }

                $this->temp_dir = plugin_dir_path(__FILE__) . 'temp';
            }

            /**
             * Carica gli script necessari nell'admin
             */
            public function admin_enqueue() {
                wp_enqueue_script('jquery-ui-datepicker');
                wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
                wp_enqueue_style('jquery-ui');
                wp_enqueue_script('calicantus_custom_script', plugin_dir_url(__FILE__) . '/script.js');
                wp_enqueue_style('calicantus_custom_css', plugin_dir_url(__FILE__) . '/style.css');
            }

            /**
             * Aggiunge il nuovo menù nell'admin
             */
            public function custom_page() {
                add_submenu_page('woocommerce', 'Calicantus', 'Calicantus', 'lb_calicantus_management_cap', 'calicantus-setting-admin', array(&$this, 'custom_page_callback'));
            }

            /**
             * Mostra i documenti nella pagina dell'ordine nell'admin
             */
            public function order_documents($order_id) {
                ?>
                <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set">
                    <div class="woocommerce-column woocommerce-column--1 col-1">
                        <h2 class="woocommerce-column__title"><?php echo __('Documents', 'woocommerce') ?></h2>
                        <ul>
                            <?php echo $this->get_order_documents($order_id) ?>
                        </ul>
                    </div>
                </section>
                <?php
            }

            /**
             * Lista tutti gli allegati dell'ordine nell'admin
             */
            public function get_order_documents($order_id) {
                $files = $this->getAllFilesDataById($order_id);
                foreach ($files as $file) {
                    ?>
                    <?php /* <li><a href="<?php echo plugins_url('download-files.php', __FILE__).'?f='.$file.'&o='.$order_id ?>" target="_blank"><?php echo $file ?></a></li> */ ?>
                    <li><a href="<?php echo plugins_url('download-files.php', __FILE__) . '?f=' . $file['file'] . '&t=' . $file['type'] . '&o=' . $order_id ?>" target="_blank"><?php echo $file['name'] ?></a></li>
                    <?php
                }
            }

            /**
             * Callback di salvataggio della pagina custom
             */
            public function custom_page_callback() {
                $this->options = get_option('calicantus_options');
                ?>
                <div class="wrap">
                    <form method="POST" action="options.php">
                        <?php
                        settings_fields('calicantus_group');
                        do_settings_sections('calicantus-setting-admin');
                        submit_button();
                        ?>
                    </form>
                </div>
                <?php
            }

            /**
             * Inizializza tutte le opzioni nella pagina di configurazione del plugin
             */
            public function page_init() {
                register_setting(
                        'calicantus_group',
                        'calicantus_options',
                        array(&$this, 'sanitize')
                );

                add_settings_section(
                        'setting_section_id',
                        'Logistica Calicantus',
                        array($this, 'print_section_info'),
                        'calicantus-setting-admin'
                );

                add_settings_field(
                        'calicantus_inventory_code',
                        'Inventory code',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_inventory_code')
                );

                add_settings_field(
                        'calicantus_brand',
                        'Brand',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_brand')
                );

                add_settings_field(
                        'calicantus_modalita',
                        'Modalità di esecuzione',
                        array($this, 'select_array_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_modalita', array(array('id' => 'local', 'name' => 'File locali'), array('id' => 'ftps', 'name' => 'FTPS')))
                );

                add_settings_field(
                        'calicantus_ftps_host',
                        'FTPS Host',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftps_host', 'ftp.dominio.it')
                );

                add_settings_field(
                        'calicantus_ftps_port',
                        'FTPS Porta',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftps_port', '21')
                );

                add_settings_field(
                        'calicantus_ftps_user',
                        'FTPS User',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftps_user', 'nome-utente')
                );

                add_settings_field(
                        'calicantus_ftps_password',
                        'FTPS Password',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftps_password', 'password')
                );

                add_settings_field(
                        'calicantus_cache_ttl',
                        'TTL cache documenti',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_cache_ttl', '3600')
                );

                add_settings_field(
                        'calicantus_ftp_primario_deliveries',
                        'Percorso primario deliveries (Agilis)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_primario_deliveries', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_primario_documentation',
                        'Percorso primario documentation (Agilis)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_primario_documentation', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_primario_availabilities',
                        'Percorso primario availabilities (Agilis)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_primario_availabilities', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_secondario_deliveries',
                        'Percorso secondario deliveries (GSPED)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_secondario_deliveries', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_secondario_documentation',
                        'Percorso secondario documentation (GSPED)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_secondario_documentation', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_secondario_statuses',
                        'Percorso secondario statuses (GSPED)',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_secondario_statuses', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_backup_deliveries',
                        'Percorso backup deliveries',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_backup_deliveries', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_backup_statuses',
                        'Percorso backup statuses',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_backup_statuses', getcwd())
                );

                add_settings_field(
                        'calicantus_ftp_backup_availabilities',
                        'Percorso backup availabilities',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_ftp_backup_availabilities', getcwd())
                );

                add_settings_field(
                        'calicantus_export_from',
                        'Esporta ordini dal',
                        array($this, 'datepicker_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_export_from')
                );

                add_settings_field(
                        'calicantus_export_invoices_from',
                        'Invia fatture per ordini effettuati dal',
                        array($this, 'datepicker_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_export_invoices_from')
                );

                /* add_settings_field(
                  'calicantus_cron_key',
                  'Chiave segreta cron',
                  array($this, 'text_callback'),
                  'calicantus-setting-admin',
                  'setting_section_id',
                  array('calicantus_cron_key', $this->generateRandomString())
                  ); */

                //impostazioni per metodi di spedizione zone
                $delivery_zones = WC_Shipping_Zones::get_zones();
                foreach ($delivery_zones as $delivery_zone) {
                    foreach ($delivery_zone['shipping_methods'] as $shipping_method) {
                        add_settings_field(
                                'calicantus_carrier_' . $shipping_method->instance_id,
                                'Corriere per metodo "' . $delivery_zone['zone_name'] . ' - ' . $shipping_method->title . '"',
                                array($this, 'select_array_callback'),
                                'calicantus-setting-admin',
                                'setting_section_id',
                                array('calicantus_carrier_' . $shipping_method->instance_id, $this->carriers)
                        );
                        add_settings_field(
                                'calicantus_carrier_shipping_service_' . $shipping_method->instance_id,
                                'Shipping service per metodo "' . $delivery_zone['zone_name'] . ' - ' . $shipping_method->title . '"',
                                array($this, 'select_array_callback'),
                                'calicantus-setting-admin',
                                'setting_section_id',
                                array('calicantus_carrier_shipping_service_' . $shipping_method->instance_id, $this->carriers_shipping_service)
                        );
                    }
                }
                //impostazioni per metodi di spedizione per resto del mondo
                $default_zone = new WC_Shipping_Zone(0);
                $default_zone_formatted_location = $default_zone->get_formatted_location();
                $default_zone_shipping_methods = $default_zone->get_shipping_methods();
                foreach ($default_zone_shipping_methods as $shipping_method) {
                    add_settings_field(
                            'calicantus_carrier_' . $shipping_method->instance_id,
                            'Corriere per metodo "Resto del mondo - ' . $shipping_method->title . '"',
                            array($this, 'select_array_callback'),
                            'calicantus-setting-admin',
                            'setting_section_id',
                            array('calicantus_carrier_' . $shipping_method->instance_id, $this->carriers)
                    );
                    add_settings_field(
                            'calicantus_carrier_shipping_service_' . $shipping_method->instance_id,
                            'Shipping service per metodo "Resto del mondo - ' . $shipping_method->title . '"',
                            array($this, 'select_array_callback'),
                            'calicantus-setting-admin',
                            'setting_section_id',
                            array('calicantus_carrier_shipping_service_' . $shipping_method->instance_id, $this->carriers_shipping_service)
                    );
                }

                add_settings_field(
                        'calicantus_bcc_addresses',
                        'Indirizzi E-mail BCC',
                        array($this, 'text_callback'),
                        'calicantus-setting-admin',
                        'setting_section_id',
                        array('calicantus_bcc_addresses')
                );
            }

            public function print_section_info() {
                echo '<h4>Impostazioni:</h4>';
            }

            /**
             * Aggiunge l'option per il reset dell'ordine nel select Azioni Ordine
             */
            public function add_reset_action($actions) {
                global $theorder;
                if ($theorder->get_meta('_calicantus_export')) {
                    $actions['wc_custom_order_reset'] = __('Reset esportazione', 'my-textdomain');
                }
                return $actions;
            }

            /**
             * Pulisce le stringhe immesse nella pagina di configurazione del plugin
             */
            public function sanitize($input) {
                $new_input = array();
                foreach ($input as $k => $v) {
                    $new_input[$k] = sanitize_text_field($input[$k]);
                }
                return $new_input;
            }

            /**
             * Mostra il campo di testo nella pagina di configurazione del modulo
             */
            public function text_callback($input) {
                if (is_array($input) && count($input) > 0) {
                    $placeholder = '';
                    if (isset($input[1]))
                        $placeholder = $input[1];
                    printf(
                            '<input type="text" id="calicantus_options[' . $input[0] . ']" name="calicantus_options[' . $input[0] . ']" value="%s" placeholder="' . $placeholder . '" />',
                            isset($this->options[$input[0]]) ? esc_attr($this->options[$input[0]]) : ''
                    );
                }
            }

            /**
             * Mostra il select nella pagina di configurazione del modulo
             */
            public function select_callback($input) {
                if (
                        (is_array($input) && count($input) > 0) &&
                        (isset($input[1]) && is_array($input[1]) && count($input[1]) > 0)
                ) {
                    ?>
                    <select name="calicantus_options[<?php echo $input[0] ?>]" id="calicantus_options[<?php echo $input[0] ?>]">
                        <?php
                        foreach ($input[1] as $k => $o) {
                            $selected = '';
                            if ($this->options[$input[0]] == $k)
                                $selected = ' selected';
                            echo '<option value="' . $k . '"' . $selected . '>' . $o . '</option>';
                        }
                        ?>
                    </select>
                    <?php
                }
            }

            /**
             * Mostra il select nella pagina di configurazione del modulo
             */
            public function select_array_callback($input) {
                if (
                        (is_array($input) && count($input) > 0) &&
                        (isset($input[1]) && is_array($input[1]) && count($input[1]) > 0)
                ) {
                    ?>
                    <select name="calicantus_options[<?php echo $input[0] ?>]" id="calicantus_options[<?php echo $input[0] ?>]">
                        <?php
                        foreach ($input[1] as $k => $o) {
                            $selected = '';
                            if ($this->options[$input[0]] == $o['id'])
                                $selected = ' selected';
                            echo '<option value="' . $o['id'] . '"' . $selected . '>' . $o['name'] . '</option>';
                        }
                        ?>
                    </select>
                    <?php
                }
            }

            /**
             * Mostra il datepicker nella pagina di configurazione del modulo
             */
            public function datepicker_callback($input) {
                if (is_array($input) && count($input) > 0) {
                    printf(
                            '<input type="text" id="calicantus_options[' . $input[0] . ']" name="calicantus_options[' . $input[0] . ']" value="%s" class="custom_datepicker" />',
                            isset($this->options[$input[0]]) ? esc_attr($this->options[$input[0]]) : ''
                    );
                }
            }

            /**
             * Aggiunge lo stato dell'ordine
             */
            public function register_giacenza_aperta_order_status() {
                register_post_status('wc-giacenza-aperta', array(
                    'label' => 'Giacenza aperta',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Giacenza aperta <span class="count">(%s)</span>', 'Giacenza aperta <span class="count">(%s)</span>')
                ));
            }

            /**
             * Aggiunge lo stato dell'ordine
             */
            public function register_giacenza_chiusa_order_status() {
                register_post_status('wc-giacenza-chiusa', array(
                    'label' => 'Giacenza chiusa',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Giacenza chiusa <span class="count">(%s)</span>', 'Giacenza chiusa <span class="count">(%s)</span>')
                ));
            }

            /**
             * Aggiunge lo stato dell'ordine
             */
            public function register_spedito_order_status() {
                register_post_status('wc-spedito', array(
                    'label' => 'Spedito',
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop('Spedito <span class="count">(%s)</span>', 'Spedito <span class="count">(%s)</span>')
                ));
            }

            /**
             * Aggiunge i nuovi stati dell'ordine nell'admin/frontend
             */
            public function add_order_statuses($order_statuses) {
                $new_order_statuses = array();
                foreach ($order_statuses as $key => $status) {
                    $new_order_statuses[$key] = $status;
                    if ('wc-processing' === $key) {
                        $new_order_statuses['wc-giacenza-aperta'] = 'Giacenza aperta';
                        $new_order_statuses['wc-giacenza-chiusa'] = 'Giacenza chiusa';
                        $new_order_statuses['wc-spedito'] = 'Spedito';
                    }
                }
                return $new_order_statuses;
            }

            /**
             * Genera una stringa random
             */
            public function generateRandomString($length = 24) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }

            /**
             * Funzione di supporto per la configurazione del plugin
             */
            public function plugin_check() {
                if ($this->modalita == 'local') {
                    $output = '';
                    $output .= $this->check_dir($this->ftp_primario_deliveries, 'Percorso FTP primario deliveries (Agilis)');
                    $output .= $this->check_dir($this->ftp_primario_documentation, 'Percorso FTP primario documentation (Agilis)');
                    $output .= $this->check_dir($this->ftp_primario_availabilities, 'Percorso FTP primario availabilities (Agilis)');
                    $output .= $this->check_dir($this->ftp_secondario_deliveries, 'Percorso FTP secondario deliveries (GSPED)');
                    $output .= $this->check_dir($this->ftp_secondario_documentation, 'Percorso FTP secondario documentation (GSPED)');
                    $output .= $this->check_dir($this->ftp_secondario_status, 'Percorso FTP secondario statuses (GSPED)');
                    $output .= $this->check_dir($this->ftp_backup_deliveries, 'Percorso FTP backup deliveries');
                    $output .= $this->check_dir($this->ftp_backup_availabilities, 'Percorso FTP backup availabilities');
                    $output .= $this->check_dir($this->ftp_backup_status, 'Percorso FTP backup statuses');

                    if ($output != '') {
                        ?>
                        <div class="notice notice-success is-dismissible"><?php echo $output ?></div>
                        <?php
                    }
                }
            }

            /**
             * Controlla l'esistenza di una cartella
             */
            public function check_dir($dir, $name) {
                $output = '';
                if ($dir == '') {
                    $output .= '<p>Cartella "' . $name . '" non impostata.</p>';
                } else if (!is_dir($dir)) {
                    $output .= "<p>La directory $dir non esiste.</p>";
                }
                return $output;
            }

            /**
             * Aggiunge i cron di WordPress
             */
            public function cron_activation() {
                if (!wp_next_scheduled('calicantus_availabilities')) {
                    wp_schedule_event(time(), 'hourly', 'calicantus_availabilities'); //TODO modificare tempo
                }
                if (!wp_next_scheduled('calicantus_status')) {
                    wp_schedule_event(time(), 'hourly', 'calicantus_status'); //TODO modificare tempo
                }
                if (!wp_next_scheduled('calicantus_deliveries')) {
                    wp_schedule_event(time(), 'hourly', 'calicantus_deliveries'); //TODO modificare tempo
                }
                if (!wp_next_scheduled('calicantus_documentation')) {
                    wp_schedule_event(time(), 'hourly', 'calicantus_documentation'); //TODO modificare tempo
                }
            }

            /**
             * Disattiva i cron di WordPress
             */
            public function cron_deactivation() {
                wp_clear_scheduled_hook('calicantus_availabilities');
                wp_clear_scheduled_hook('calicantus_status');
                wp_clear_scheduled_hook('calicantus_deliveries');
                wp_clear_scheduled_hook('calicantus_documentation');
            }

            /**
             * Aggiunge una colonna nella lista degli ordini
             */
            public function wc_new_order_column($columns) {
                $columns['gestione_column'] = 'Gestione';
                return $columns;
            }

            /**
             * Riempie la nuova colonna aggiunta nella lista degli ordini
             */
            public function wc_new_order_column_data($column) {
                global $post;
                $output = '';
                $settings = get_option('calicantus_options');
                if ('gestione_column' === $column) {
                    $order = wc_get_order($post->ID);
                    if ($order) {
                        if (strtotime($order->order_date) < strtotime($settings['calicantus_export_from'])) { //se l'ordine è antecedente alla data impostata da backend
                            $output .= '<span class="label label-primary icona-gestione icona-ignorato" title="Ignorato">I</span>';
                        } else if ($order->get_status() == 'processing' && !$order->get_meta('_calicantus_export')) { //l'ordine è "pagamento accettato" e non è stata salvata a DB l'esportazione
                            $output .= '<span class="label label-primary icona-gestione icona-da-esportare" title="Da esportare">D</span>';
                        } else if ($order->get_meta('_calicantus_export')) { //se è stata salvata a DB l'esportazione
                            $output .= '<span class="label label-primary icona-gestione icona-esportato" title="Esportato">E</span>';
                        } else {
                            $output .= '<span class="label label-primary icona-gestione icona-non-esportato" title="Non esportato">N</span>';
                        }

                        $attachments = $this->getAllFilesDataById($this->getOrderId($order));
                        if (count($attachments) > 0) {
                            $output .= '<img src="' . plugins_url('attachments.png', __FILE__) . '" alt="" style="margin:5px;float:left;width:15px;" /><div style="float:left;margin-top:-8px;margin-left:-8px;">' . count($attachments) . '</div>';
                        }
                        if ($order->get_meta('_calicantus_invoice_sent')) {
                            $output .= '<img src="' . plugins_url('envelope.png', __FILE__) . '" alt="" style="margin:5px;float:left;height:15px;" title="Allegati inviati"/>';
                        }
                    }
                }
                echo $output;
            }

            /**
             * Restituisce tutte le informazioni sugli allegati di un ordine
             */
            public function getAllFilesDataById($id_order) {
                $attachments = array();
                $settings = get_option('calicantus_options');
                if ($this->modalita == 'local') {
                    $documents = array_diff(scandir($this->ftp_primario_documentation), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    if ($this->isCacheValid('agilis_documentation')) {
                        $documents = get_option('calicantus_document_cache_ad');
                    } else {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection())
                            $documents = $ftps->lsDirectory($this->ftp_primario_documentation);
                        if (isset($documents) && is_array($documents)) {
                            update_option('calicantus_document_cache_ad', $documents);
                            $this->setCacheValid('agilis_documentation');
                        }
                    }
                }
                if (!is_array($documents))
                    $documents = array();
                foreach ($documents as $file) {
                    if (substr($file, 0, strlen($id_order . '_')) === $id_order . '_') {
                        $temp = array();
                        $temp['type'] = 1;
                        $temp['name'] = $file;
                        $temp['file'] = base64_encode($file);
                        //$temp['time'] = filemtime($this->ftp_primario_documentation . DIRECTORY_SEPARATOR . $file);
                        $attachments[] = $temp;
                    }
                }

                if ($this->modalita == 'local') {
                    $documents = array_diff(scandir($this->ftp_secondario_documentation), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    if ($this->isCacheValid('gsped_documentation')) {
                        $documents = get_option('calicantus_document_cache_gd');
                    } else {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection())
                            $documents = $ftps->lsDirectory($this->ftp_secondario_documentation);
                        if (is_array($documents)) {
                            update_option('calicantus_document_cache_gd', $documents);
                            $this->setCacheValid('gsped_documentation');
                        }
                    }
                }
                if (!is_array($documents))
                    $documents = array();
                foreach ($documents as $file) {
                    if (substr($file, 0, strlen($id_order . '_')) === $id_order . '_') {
                        $temp = array();
                        $temp['type'] = 2;
                        $temp['name'] = $file;
                        $temp['file'] = base64_encode($file);
                        //$temp['time'] = filemtime($this->ftp_secondario_documentation . DIRECTORY_SEPARATOR . $file);
                        $attachments[] = $temp;
                    }
                }
                return $attachments;
            }

            /**
             * Restitisce un array contenente i file che vengono allegati all'email inviata al cliente tramite la funzione cron_documentation()
             */
            public function getAllFilesById($id_order) {
                $attachments = array();
                if ($this->modalita == 'local') {
                    $documents = array_diff(scandir($this->ftp_primario_documentation), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    if ($this->isCacheValid('agilis_documentation')) {
                        $documents = get_option('calicantus_document_cache_ad');
                    } else {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection())
                            $documents = $ftps->lsDirectory($this->ftp_primario_documentation);
                        if (is_array($documents)) {
                            update_option('calicantus_document_cache_ad', $documents);
                            $this->setCacheValid('agilis_documentation');
                        }
                    }
                }
                foreach ($documents as $file) {
                    if (substr($file, 0, strlen($id_order . '_')) === $id_order . '_') {
                        $attachments[] = $this->ftp_primario_documentation . DIRECTORY_SEPARATOR . $file;
                    }
                }

                if ($this->modalita == 'local') {
                    $documents = array_diff(scandir($this->ftp_secondario_documentation), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    if ($this->isCacheValid('gsped_documentation')) {
                        $documents = get_option('calicantus_document_cache_gd');
                    } else {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection())
                            $documents = $ftps->lsDirectory($this->ftp_secondario_documentation);
                        if (is_array($documents)) {
                            update_option('calicantus_document_cache_gd', $documents);
                            $this->setCacheValid('gsped_documentation');
                        }
                    }
                }
                foreach ($documents as $file) {
                    if (substr($file, 0, strlen($id_order . '_')) === $id_order . '_') {
                        $attachments[] = $this->ftp_secondario_documentation . DIRECTORY_SEPARATOR . $file;
                    }
                }
                return $attachments;
            }

            /**
             * Modifica le quantità dei prodotti a seconda del file xml
             */
            public function check_availabilities() {
                $availabilities_files = array();
                if ($this->modalita == 'local') {
                    $availabilities_files = array_diff(scandir($this->ftp_primario_availabilities), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                    if ($ftps->checkConnection())
                        $availabilities_files = $ftps->lsDirectory($this->ftp_primario_availabilities);
                }
                if ($availabilities_files == false)
                    $availabilities_files = [];
                foreach ($availabilities_files as $file) {
                    $xml = '';
                    if ($this->modalita == 'local') {
                        $xml = $this->ftp_primario_availabilities . DIRECTORY_SEPARATOR . $file;
                    } else if ($this->modalita == 'ftps') {
                        if ($ftps->checkConnection()) {
                            $xml = tempnam($this->temp_dir, "TMP0");
                            $ftps->getTempFile($xml, $this->ftp_primario_availabilities . DIRECTORY_SEPARATOR . $file);
                        }
                    }
                    if (is_file($xml)) {
                        $availabilities = new SimpleXMLElement($xml, NULL, TRUE);
                        $cdate = date('Ymd_His') . '_';

                        foreach ($availabilities as $availability) {
                            $sku = (string) $availability->sku;
                            $product_id = wc_get_product_id_by_sku($sku);

                            if ($product_id > 0) {
                                $status = 'outofstock';
                                if ($availability->availability > 0)
                                    $status = 'instock';
                                try {
                                    $product = new WC_Product($product_id);
                                } catch (Exception $e) {
                                    try {
                                        $product = new WC_Product_Variation($product_id);
                                    } catch (Exception $e) {
                                        error_log('Lo SKU ' . $sku . ' non esiste: ' . $e);
                                        continue;
                                    }
                                }
                            } else {
                                error_log('Lo SKU ' . $sku . ' non esiste');
                                continue;
                            }

                            $product->set_manage_stock(true);
                            $product->set_stock_quantity($availability->availability);
                            $product->set_stock_status($status);
                            $product->save();

                            /* se wmpl */
                            if (function_exists('icl_object_id')) {
                                $id = $product_id;
                                global $sitepress;

                                $new_quantity = get_post_meta($id, '_stock', true);

                                if (is_numeric($new_quantity)) {

                                    $new_stock_status = ($new_quantity > 0) ? "instock" : "outofstock";
                                    wc_update_product_stock_status($id, $new_stock_status);

                                    $trid = $sitepress->get_element_trid($id, 'post_product');
                                    if (is_numeric($trid)) {
                                        $translations = $sitepress->get_element_translations($trid, 'post_product');

                                        if (is_array($translations)) {

                                            foreach ($translations as $translation) {
                                                if (!isset($translation->element_id) || $translation->element_id == $id) {
                                                    continue;
                                                }

                                                update_post_meta($translation->element_id, '_stock', $new_quantity);
                                                wc_update_product_stock_status($translation->element_id, $new_stock_status);
                                            }
                                        }
                                    }
                                }
                            }
                            /* se wpml */
                        }
                        if ($this->modalita == 'local') {
                            rename($xml, $this->ftp_backup_availabilities . DIRECTORY_SEPARATOR . $cdate . $file);
                        } else if ($this->modalita == 'ftps') {
                            $ftps->moveFile($this->ftp_primario_availabilities . DIRECTORY_SEPARATOR . $file, $this->ftp_backup_availabilities . DIRECTORY_SEPARATOR . $cdate . $file);
                        }
                    }
                }
            }

            /**
             * Avvia la procedura di esportazione per le deliveries
             */
            public function check_deliveries() {
                $query = new WC_Order_Query(array(
                    'limit' => -1,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'status' => 'processing',
                    'date_created' => '>=' . $this->global_options['calicantus_export_from']
                ));
                $orders = $query->get_orders();
                foreach ($orders as $order) {
                    if (!$order->get_meta('_calicantus_export')) {
                        $this->exportDelivery($order->get_id());
                    }
                }
            }

            /**
             * Genera il file XML della delivery e salva a DB che l'esportazione è stata effettuata
             */
            public function exportDelivery($id) {
                $order = wc_get_order($id);
                $order_data = $order->get_data();
                /* echo '<pre>';
                  print_r($order_data);
                  die(); */
                $europa = array('AT', 'BE', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'SE', 'SI', 'SK', 'BG', 'RO');

                switch ($order_data['payment_method']) { //associazione codice di pagamento del CMS e codice di Calicantus
                    case 'bacs':
                        $payment = 'bank_transfer';
                        break;
                    case 'paypal':
                        $payment = 'paypal';
                        break;
                    default:
                        $payment = $order_data['payment_method'];
                }
                $currency_code = $order->get_currency();

                $lang = 'it';
                if ($order_data['billing']['country'] != 'IT')
                    $lang = 'en';
                if (function_exists('icl_object_id')) {
                    $lang = get_post_meta($id, 'wpml_language', true);
                }
                /* echo get_post_meta( $order_data['id'], '_billing_calicantus_cf', true )." CF\n";
                  echo get_post_meta( $order_data['id'], '_billing_calicantus_vat', true )." VAT\n";
                  echo $order_data['id']; print_r($order_data);die(); */
                if (
                /* in_array($order_data['billing']['country'], $europa) &&
                  ((get_post_meta( $order_data['id'], '_billing_calicantus_cf', true ) && get_post_meta( $order_data['id'], '_billing_calicantus_cf', true ) != '') || (get_post_meta( $order_data['id'], '_billing_calicantus_vat', true ) && get_post_meta( $order_data['id'], '_billing_calicantus_vat', true ) != '')) && */
                        isset($order_data['billing']['company']) &&
                        $order_data['billing']['company'] != ''
                ) {
                    $nome = $order_data['billing']['company'];
                    $cognome = '';
                } else {
                    $nome = $order_data['billing']['first_name'];
                    $cognome = $order_data['billing']['last_name'];
                }

                if (isset($order_data['shipping']['company']) && $order_data['shipping']['company'] != '') {
                    $snome = $order_data['shipping']['first_name'];
                    $scognome = $order_data['shipping']['last_name'] . ' - ' . $order_data['shipping']['company'];
                } else {
                    $snome = $order_data['shipping']['first_name'];
                    $scognome = $order_data['shipping']['last_name'];
                }

                $settings = get_option('calicantus_options');
                $shipping = reset($order->get_items('shipping'));

                // FIX: Ordine con soli prodotti virtuali, shipping non esiste
                if ($shipping) {
                    $carrier_id = $shipping->get_instance_id();
                    $carrier = $settings['calicantus_carrier_' . $carrier_id];
                    $carrier_shipping_service = $settings['calicantus_carrier_shipping_service_' . $carrier_id];
                }

                $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><pick_list></pick_list>');

                $delivery_header = $xml_data->addChild('delivery_header');
                $delivery_header->addChild('inventory_code', $settings['calicantus_inventory_code']);
                $delivery_header->addChild('brand', $settings['calicantus_brand']);
                $delivery_header->addChild('delivery_type', 0);
                $delivery_header->addChild('delivery_id', $this->getOrderId($order));
                $delivery_header->addChild('delivery_date', date('Y-m-d'));
                $delivery_header->addChild('order_number', $this->getOrderId($order));
                $delivery_header->addChild('order_date', date('Y-m-d', strtotime($order->order_date)));
                $delivery_header->addChild('payment_method', $payment);
                if ((in_array($order_data['billing']['country'], $europa) &&  ((get_post_meta($order_data['id'], '_billing_calicantus_cf', true) && get_post_meta($order_data['id'], '_billing_calicantus_cf', true) != '') || (get_post_meta($order_data['id'], 'vat_number', true) && get_post_meta($order_data['id'], 'vat_number', true) != ''))) || (in_array($order_data['billing']['country'], ['CH', 'GB', 'NO', 'IS'])))
                    $delivery_header->addChild('payment_term', 'S');
                else
                    $delivery_header->addChild('payment_term', 'N');
                $delivery_header->addChild('carrier', $carrier);
                $delivery_header->addChild('shipping_service', $carrier_shipping_service);
                $delivery_header->addChild('shipping_note', strip_tags($order->get_customer_note()));
                $delivery_header->addChild('gift_message', '');
                $delivery_header->addChild('ddt_lang', strtoupper($lang));
                $delivery_header->addChild('pricelist_id', 1);
                $delivery_header->addChild('pricelist_name', 'default');
                $delivery_header->addChild('total_net', $order_data['total'] - $order_data['total_tax']);
                $delivery_header->addChild('total_sales_tax', $order_data['total_tax']);
                $delivery_header->addChild('total_delivery', $order_data['total']);


                foreach ($order->get_used_coupons() as $coupon_code) {
                    $coupon_object = new WC_Coupon($coupon_code);

                    $coupon_type = 'oneoff';
                    if ($coupon_object->discount_type == 'percent')
                        $coupon_type = 'percentage';
                    $coupon_campaign = $coupon_object->get_description();
                    $coupon_tax = $order->get_discount_tax() * -1;
                    $coupon_price = $order->get_total_discount() * -1;
                    $delivery_header->addChild('coupon_code', $coupon_code);
                    $delivery_header->addChild('coupon_campaign', $coupon_campaign);

                    $delivery_header->addChild('coupon_value', $coupon_object->get_amount());
                    $delivery_header->addChild('coupon_type', $coupon_type);
                }

                $bill_to_info = $delivery_header->addChild('bill_to_info');
                $bill_to_info->addChild('bill_to_cust_number', $order_data['billing']['email']);
                $bill_to_info->addChild('bill_to_cust_first_name', $nome);
                $bill_to_info->addChild('bill_to_cust_last_name', $cognome);
                $bill_to_info->addChild('bill_to_address1', $order_data['billing']['address_1']);
                $bill_to_info->addChild('bill_to_address2', $order_data['billing']['address_2']);
                $bill_to_info->addChild('bill_to_city', $order_data['billing']['city']);
                $bill_to_info->addChild('bill_to_zip_code', $order_data['billing']['postcode']);
                $bill_to_info->addChild('bill_to_province', $order_data['billing']['state']);
                $bill_to_info->addChild('bill_to_country', $order_data['billing']['country']);
                if (get_post_meta($id, 'vat_number', true) && get_post_meta($id, 'vat_number', true) != '')
                    $bill_to_info->addChild('bill_to_vatnumber', get_post_meta($id, 'vat_number', true));
                if (get_post_meta($id, '_billing_calicantus_cf', true) && get_post_meta($id, '_billing_calicantus_cf', true) != '')
                    $bill_to_info->addChild('bill_to_fiscalcode', get_post_meta($id, '_billing_calicantus_cf', true));
                $bill_to_info->addChild('bill_to_isocountry', $order_data['billing']['country']);
                $bill_to_info->addChild('bill_to_currency', $currency_code);
                $bill_to_info->addChild('bill_to_telephone', $order_data['billing']['phone']);
                $bill_to_info->addChild('bill_to_email', $order_data['billing']['email']);
                if (get_post_meta($id, '_billing_calicantus_pec', true) && get_post_meta($id, '_billing_calicantus_pec', true) != '')
                    $bill_to_info->addChild('bill_to_pec', get_post_meta($id, '_billing_calicantus_pec', true));
                if (get_post_meta($id, '_billing_calicantus_sdi', true) && get_post_meta($id, '_billing_calicantus_sdi', true) != '')
                    $bill_to_info->addChild('bill_to_cd', get_post_meta($id, '_billing_calicantus_sdi', true));
                $ship_to_info = $delivery_header->addChild('ship_to_info');
                $ship_to_info->addChild('ship_to_cust_number', $order_data['billing']['email']);
                $ship_to_info->addChild('ship_to_cust_first_name', $snome);
                $ship_to_info->addChild('ship_to_cust_last_name', $scognome);
                $ship_to_info->addChild('ship_to_address1', $order_data['shipping']['address_1']);
                $ship_to_info->addChild('ship_to_address2', $order_data['shipping']['address_2']);
                $ship_to_info->addChild('ship_to_city', $order_data['shipping']['city']);
                $ship_to_info->addChild('ship_to_zip_code', $order_data['shipping']['postcode']);
                $ship_to_info->addChild('ship_to_province', $order_data['shipping']['state']);
                $ship_to_info->addChild('ship_to_country', $order_data['shipping']['country']);
                $ship_to_info->addChild('ship_to_isocountry', $order_data['shipping']['country']);
                $ship_to_info->addChild('ship_to_telephone', $order_data['billing']['phone']);
                $ship_to_info->addChild('ship_to_email', $order_data['billing']['email']);

                $cont = 1;
                $coupon_tax_rate = 0;
                $is_virtual = true;


                foreach ($order->get_items('tax') as $tax_item) {
                    $tax_items_class[$tax_item->get_rate_id()] = $tax_item->get_rate_percent();
                }


                foreach ($order->get_items() as $item_key => $item_values) {
                    $product = $item_values->get_product();

                    if ($product !== false) {

                        $item_data = $item_values->get_data();

                        $tax_r = '22.00';
                        $taxes = $item_values->get_taxes();
                        foreach ($taxes['subtotal'] as $rate_id => $tax) {
                            $tax_r = number_format($tax_items_class[$rate_id], 2, '.', ',');
                        }

                        if ($product->get_sku())
                            $product_sku = $product->get_sku();
                        else
                            $product_sku = $product->get_id();

                        if (!$product->is_virtual())
                            $is_virtual = false;

                        $price = $item_data['total'] / $item_data['quantity'];
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'wc_order_product_lookup';
                        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

                            if ($product->is_type('variation')) {
                                $query = "select * from " . $wpdb->prefix . "wc_order_product_lookup where order_id=" . $order->get_id() . " AND variation_id=" . $product->get_id();
                            } else {
                                $query = "select * from " . $wpdb->prefix . "wc_order_product_lookup where order_id=" . $order->get_id() . " AND product_id=" . $product->get_id();
                            }

                            $result = $wpdb->get_results($query);

                            if (is_array($result) && isset($result[0])) {
                                $price = number_format($result[0]->product_net_revenue + $result[0]->coupon_amount, 2) / $item_data['quantity'];
                            }
                        }
                        $price_discount = $price;

                        $delivery_line = $delivery_header->addChild('delivery_line');
                        $delivery_line->addChild('delivery_line_id', $cont);
                        $delivery_line->addChild('delivery_line_type', 'A');
                        $delivery_line->addChild('sku', $product_sku);
                        $delivery_line->addChild('sku_parent', '');
                        $sku_description = $delivery_line->addChild('sku_description', strip_tags($item_data['name']));
                        $sku_description->addAttribute('lang', strtolower($lang));
                        $delivery_line->addChild('qty', $item_data['quantity']);

                        /*
                         * Non c'è distinzione tra prezzo pieno e scontato perchè WC
                         * si salva solo il prezzo reale/finale del prodotto su item_data.
                         * Usando spesso plugin per cambiare la logica di prezzi di WC, se facessimo
                         * riferimento ad esempio a $product->get_regular_price() avremmo il prezzo "base"
                         * di WC ma che però potrebbe essere non usato (pensiamo ad esempio a plugin che introducono
                         * prezzi diversi per country, ecc...)
                         */

                        $delivery_line->addChild('price', $price);
                        $delivery_line->addChild('price_discount', $price_discount);
                        $delivery_line->addChild('discount_perc1', 0);
                        $delivery_line->addChild('discount_perc2', 0);

                        $delivery_line->addChild('total_price', $price_discount * $item_data['quantity']);
                        $delivery_line->addChild('um', 'NR');
                        $delivery_line->addChild('sector', '');
                        $delivery_line->addChild('group', '');
                        $delivery_line->addChild('subgroup', '');
                        $delivery_line->addChild('weight', '');
                        $delivery_line->addChild('width', '');
                        $delivery_line->addChild('height', '');
                        $delivery_line->addChild('made_in', '');
                        $delivery_line->addChild('customs_code', '');
                        $delivery_line->addChild('vat_cod', $tax_r);
                        $delivery_line->addChild('vat_tax', ($price_discount / 100 * $tax_r) / $item_data['quantity']);
                        $cont++;
                        $coupon_tax_rate = $tax_r;
                    } else {
                        error_log('Calicantus ordine '.$id.' - prodotto non trovato: '.print_r($item_values->get_data(), true));
                        return false;
                    }
                }


                foreach ($order->get_used_coupons() as $coupon_code) {
                    $coupon_object = new WC_Coupon($coupon_code);
                    $coupon_type = 'oneoff';
                    if ($coupon_object->discount_type == 'percent')
                        $coupon_type = 'percentage';
                    $coupon_campaign = $coupon_object->get_description();
                    $coupon_tax = $order->get_discount_tax() * -1;
                    $coupon_price = $order->get_total_discount() * -1;

                    $delivery_line = $delivery_header->addChild('delivery_line');
                    $delivery_line->addChild('delivery_line_id', 0);
                    $delivery_line->addChild('delivery_line_type', 'C');
                    $delivery_line->addChild('sku', $coupon_code);
                    $delivery_line->addChild('sku_parent', '');
                    $sku_description = $delivery_line->addChild('sku_description', $coupon_campaign);
                    $sku_description->addAttribute('lang', strtolower($lang));
                    $delivery_line->addChild('qty', 1);
                    $delivery_line->addChild('price', $coupon_price);
                    $delivery_line->addChild('price_discount', 0);
                    $delivery_line->addChild('discount_perc1', 0);
                    $delivery_line->addChild('discount_perc2', 0);
                    $delivery_line->addChild('total_price', $coupon_price);
                    $delivery_line->addChild('um', 'NR');
                    $delivery_line->addChild('sector', '');
                    $delivery_line->addChild('group', '');
                    $delivery_line->addChild('subgroup', '');
                    $delivery_line->addChild('weight', '');
                    $delivery_line->addChild('width', '');
                    $delivery_line->addChild('height', '');
                    $delivery_line->addChild('made_in', '');
                    $delivery_line->addChild('customs_code', '');
                    $delivery_line->addChild('vat_cod', $coupon_tax_rate);
                    $delivery_line->addChild('vat_tax', $coupon_tax);
                }


                if (!$is_virtual) {
                    $delivery_line = $delivery_header->addChild('delivery_line');
                    $delivery_line->addChild('delivery_line_id', 0);
                    $delivery_line->addChild('delivery_line_type', 'S');
                    $delivery_line->addChild('sku', 'shipping');
                    $delivery_line->addChild('sku_parent', '');
                    $sku_description = $delivery_line->addChild('sku_description', 'Spedizione');
                    $sku_description->addAttribute('lang', strtolower($lang));
                    $delivery_line->addChild('qty', 1);
                    $delivery_line->addChild('price', $order_data['shipping_total']);
                    $delivery_line->addChild('price_discount', $order_data['shipping_total']);
                    $delivery_line->addChild('discount_perc1', 0);
                    $delivery_line->addChild('discount_perc2', 0);
                    $delivery_line->addChild('total_price', $order_data['shipping_total']);
                    $delivery_line->addChild('um', 'NR');
                    $delivery_line->addChild('sector', '');
                    $delivery_line->addChild('group', '');
                    $delivery_line->addChild('subgroup', '');
                    $delivery_line->addChild('weight', '');
                    $delivery_line->addChild('width', '');
                    $delivery_line->addChild('height', '');
                    $delivery_line->addChild('made_in', '');
                    $delivery_line->addChild('customs_code', '');
                    $delivery_line->addChild('vat_cod', $tax_r);
                    $delivery_line->addChild('vat_tax', $order_data['shipping_tax']);
                }

                $ts = date('YmdHis');
                $dom = new DOMDocument();
                $dom->loadXML(html_entity_decode($xml_data->asXML()));
                $dom->formatOutput = true;
                $formattedXML = $dom->saveXML();

                /* echo $formattedXML;
                  print_r($order);
                  die(); */

                if ($this->modalita == 'local') {
                    $fp = fopen($this->ftp_primario_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', 'w+');
                    fwrite($fp, $formattedXML);
                    fclose($fp);

                    $fp = fopen($this->ftp_secondario_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', 'w+');
                    fwrite($fp, $formattedXML);
                    fclose($fp);

                    $fp = fopen($this->ftp_backup_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', 'w+');
                    fwrite($fp, $formattedXML);
                    fclose($fp);
                } else if ($this->modalita == 'ftps') {
                    $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                    if ($ftps->checkConnection()) {
                        $fp = fopen('php://temp', 'r+');
                        fwrite($fp, $formattedXML);
                        rewind($fp);
                        $ftps->putFile($this->ftp_primario_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', $fp);

                        $fp = fopen('php://temp', 'r+');
                        fwrite($fp, $formattedXML);
                        rewind($fp);
                        $ftps->putFile($this->ftp_secondario_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', $fp);

                        $fp = fopen('php://temp', 'r+');
                        fwrite($fp, $formattedXML);
                        rewind($fp);
                        $ftps->putFile($this->ftp_backup_deliveries . DIRECTORY_SEPARATOR . 'deliveries_' . $ts . '_' . mt_rand(1, 9999) . '.xml', $fp);
                    }
                }

                update_post_meta($id, '_calicantus_export', 'yes');

                $order->add_order_note('Ordine ' . $id . ' esportato XML delivery (' . date('Y-m-d H:i:s') . ')');

                // utile se attiva qualche forma di cache aggressiva (es: Redis)
                wc_delete_shop_order_transients($order);
            }

            /**
             * Controlla se un file è un XML valido.
             */
            public function isXML($xmlstr) {
                $doc = new DOMDocument();
                if (@$doc->load($xmlstr)) {
                    return true;
                } else {
                    return false;
                }
            }

            /**
             * Modifica lo stato dell'ordine in relazione al file XML caricato
             */
            public function check_status() {
                $cdate = date('Ymd_His') . '_';
                if ($this->modalita == 'local') {
                    $scanned_directory = array_diff(scandir($this->ftp_secondario_status), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                    if ($ftps->checkConnection())
                        $scanned_directory = $ftps->lsDirectory($this->ftp_secondario_status);
                }
                $tabella_stati = array(
                    17 => 'spedito', // spedizione partita
                    19 => 'completed', // consegnato
                    30 => 'giacenza-aperta', // giacenza aperta
                    31 => 'giacenza-chiusa' // giacenza chiusa
                );
                if ($scanned_directory == false)
                    $scanned_directory = [];
                foreach ($scanned_directory as $status_file) {
                    if ($this->modalita == 'local') {
                        $status_input = $this->ftp_secondario_status . DIRECTORY_SEPARATOR . $status_file;
                    } else if ($this->modalita == 'ftps') {
                        if ($ftps->checkConnection()) {
                            $status_input = tempnam($this->temp_dir, "TMPS0");
                            $ftps->getTempFile($status_input, $this->ftp_secondario_status . DIRECTORY_SEPARATOR . $status_file);
                        }
                    }
                    if ($this->isXML($status_input)) {
                        $status_update = new SimpleXMLElement($status_input, NULL, TRUE);

                        $order = wc_get_order((int) $this->getStatusId($status_update->rifnum));

                        if (!empty($order)) {
                            $order_data = $order->get_data();
                            $new_os = $tabella_stati[(int) $status_update->status];
                            $old_os = $order_data['status'];

                            /*
                             * Se lo stato è diverso da quello precedente e se è tra gli stati che vogliamo gestire in Prestashop
                             */

                            if (($old_os != $new_os) && array_key_exists((int) $status_update->status, $tabella_stati)) {

                                /*
                                 * Spedizione partita (setto il tracking)
                                 */

                                if ((int) $status_update->status == 17) {
                                    $tracking = $status_update->tracking_url;
                                    $order->add_order_note('Tracking: <a href="' . $status_update->tracking_url . ">" . $status_update->tracking_url . "</a>", true);
                                    $order->save();
                                }

                                /*
                                 * Tutti gli stati
                                 */

                                $order->update_status($new_os);
                            }
                            if ($this->modalita == 'local') {
                                rename($this->ftp_secondario_status . DIRECTORY_SEPARATOR . $status_file, $this->ftp_backup_status . DIRECTORY_SEPARATOR . $cdate . $status_file);
                            } else if ($this->modalita == 'ftps') {
                                $ftps->moveFile($this->ftp_secondario_status . DIRECTORY_SEPARATOR . $status_file, $this->ftp_backup_status . DIRECTORY_SEPARATOR . $cdate . $status_file);
                            }

                            // utile se attiva qualche forma di cache aggressiva (es: Redis)
                            wc_delete_shop_order_transients($order);
                        }
                    }
                }
            }

            /**
             * Cancella il log di delivery esportata. In questo modo il file XML viene generato nuovamente.
             */
            public function reset_order_delivery($order) {
                delete_post_meta($order->id, '_calicantus_export');

                // utile se attiva qualche forma di cache aggressiva (es: Redis)
                wc_delete_shop_order_transients($order);
            }

            /**
             * Aggiunge il tracking alla mail inviata al cliente
             */
            public function woo_add_order_notes_to_email($order, $sent_to_admin, $plain_text) {
                if ('completed' != $order->get_status()) {
                    return;
                }

                $args = array(
                    'post_id' => $order->get_id(),
                    'approve' => 'approve',
                    'type' => ''
                );
                remove_filter('comments_clauses', array('WC_Comments', 'exclude_order_comments'));
                $notes = get_comments($args);
                add_filter('comments_clauses', array('WC_Comments', 'exclude_order_comments'));

                echo '<h2>' . __('Shipping', 'woocommerce') . '</h2>';
                if ($notes) {
                    foreach ($notes as $note) {
                        if (strpos($note->comment_content, 'Tracking:') !== false) {
                            $note_classes = get_comment_meta($note->comment_ID, 'is_customer_note', true) ? array('customer-note', 'note') : array('note');
                            ?>
                            <div class="note_content">
                                <?php echo wpautop(wptexturize(wp_kses_post($note->comment_content))); ?>
                            </div>
                            <?php
                        }
                    }
                }
            }

            /**
             * $file: nome del file
             * $type: 1 cartella documentatio primaria, 2 cartella documentation secondaria
             * Scarica il file nel caso che l'utente loggato sia colui che ha effettuato l'ordine, oppure nel caso di un amministratore
             * Se il plugin è impostato su ftps, scarica il file via ftps e invia direttamente al browser
             */
            public function download_file($order_id, $file, $type) {
                if ($type == 1) {
                    $directory = $this->ftp_primario_documentation;
                } else {
                    $directory = $this->ftp_secondario_documentation;
                }
                $order = wc_get_order($order_id);
                $user_id = $order->get_user_id();
                if ($user_id == get_current_user_id() || current_user_can('administrator') || current_user_can('editor') ||  current_user_can('shop_manager')) {
                    if ($this->modalita == 'local') {
                        $fileurl = $directory . DIRECTORY_SEPARATOR . $file;
                        if (is_file($fileurl)) {
                            header("Content-type:application/pdf");
                            header('Content-Disposition: attachment; filename=' . $file);
                            readfile($fileurl);
                            die();
                        }
                    } else if ($this->modalita == 'ftps') {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection()) {
                            $ftps->download_file($directory . DIRECTORY_SEPARATOR . $file);
                        }
                    }
                }
            }

            /**
             * Aggiunge il meta box documenti nella pagina dell'ordine
             */
            public function wporg_add_custom_box() {
                $screens = ['shop_order'];
                foreach ($screens as $screen) {
                    add_meta_box(
                            'calicantus_documents',
                            'Documenti',
                            array($this, 'backend_documents'),
                            $screen
                    );
                }
            }

            /**
             * Stampa i documenti dell'ordine nella relativa pagina nell'admin
             */
            public function backend_documents($post) {
                $order = wc_get_order($post->ID);
                $files = $this->getAllFilesDataById($this->getOrderId($order));
                ?>
                <ul class="calicantus-backend-documents">
                    <?php
                    if (count($files) < 1) {
                        ?>
                        <li>Nessun documento disponibile</li>
                        <?php
                    } else {
                        foreach ($files as $file) {
                            ?>
                            <li><a href="<?php echo plugins_url('download-files.php', __FILE__) . '?f=' . $file['file'] . '&t=' . $file['type'] . '&o=' . $post->ID ?>" target="_blank"><?php echo $file['name'] ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <?php
            }

            /**
             * Avvia la procedura per l'invio delle email con fattura
             */
            public function cron_documentation() {
                if ($this->modalita == 'local') {
                    $documents = array_diff(scandir($this->ftp_primario_documentation), array('..', '.'));
                } else if ($this->modalita == 'ftps') {
                    if ($this->isCacheValid('agilis_documentation')) {
                        $documents = get_option('calicantus_document_cache_ad');
                    } else {
                        $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                        if ($ftps->checkConnection())
                            $documents = $ftps->lsDirectory($this->ftp_primario_documentation);
                        if (is_array($documents)) {
                            update_option('calicantus_document_cache_ad', $documents);
                            $this->setCacheValid('agilis_documentation');
                        }
                    }
                }

                if (!is_array($documents))
                    $documents = array();
                foreach ($documents as $file) {
                    $file_check = strtolower($file);
                    if (strrpos($file_check, "_invoice") > 0) {
                        $order_id = $this->getStatusId(substr($file, 0, strpos($file, '_')));
                        $order = wc_get_order($order_id);
                        if (!$order->get_meta('_calicantus_invoice_sent')) {
                            $date_add = $order->order_date;
                            if (strtotime($date_add) >= strtotime($this->global_options['calicantus_export_from'])) {
                                $mailer = WC()->mailer();
                                $mails = $mailer->get_emails();
                                if (!empty($mails)) {
                                    foreach ($mails as $mail) {
                                        if ($mail->id == 'customer_invoice') {
                                            $mail->trigger($order_id);
                                        }
                                    }
                                }
                                $order->update_meta_data('_calicantus_invoice_sent', 'yes');
                                $order->add_order_note('Ordine ' . $order_id . ' inviata email con documentation (' . date('Y-m-d H:i:s') . ')');
                                $order->save();

                                // utile se attiva qualche forma di cache aggressiva (es: Redis)
                                wc_delete_shop_order_transients($order);
                            }
                        }
                    }
                }
                if(file_exists($this->temp_dir)){
                    $pdfs = array_diff(scandir($this->temp_dir), array('..', '.'));
                    if($pdfs){
                        foreach ($pdfs as $pdf) {
                            @unlink($this->temp_dir . DIRECTORY_SEPARATOR . $pdf);
                        }
                    }
                }
            }

            /**
             * Aggiunge l'allegato alla mail
             */
            public function attach_documents($attachments, $email_id, $order) {
                if (!is_a($order, 'WC_Order') || !isset($email_id)) {
                    return $attachments;
                }
                $attachments = array();
                $attachs = $this->getAllFilesById($this->getOrderId($order));
                if ($this->modalita == 'ftps') {
                    $ftps = new Ftps($this->ftps_host, $this->ftps_port, $this->ftps_user, $this->ftps_password);
                    $temp = array();
                    if ($ftps->checkConnection()) {
                        foreach ($attachs as $attach) {
                            $pdf = $this->temp_dir . DIRECTORY_SEPARATOR . basename($attach);
                            if (strpos($attach, $this->ftp_primario_documentation) !== false) {
                                $ftps->getTempFile($pdf, $this->ftp_primario_documentation . DIRECTORY_SEPARATOR . basename($attach));
                            } else if (strpos($attach, $this->ftp_secondario_documentation) !== false) {
                                $ftps->getTempFile($pdf, $this->ftp_secondario_documentation . DIRECTORY_SEPARATOR . basename($attach));
                            }
                            $temp[] = $pdf;
                        }
                        $attachs = $temp;
                    }
                }
                $attachs = array_unique($attachs);
                if ($email_id == 'customer_invoice') {
                    foreach ($attachs as $attach) {
                        $attachments[] = $attach;
                    }
                }
                return $attachments;
            }

            /**
             * Aggiunge i campi necessari nell'indirizzo di fatturazione
             */
            public function add_billing_custom_field($fields) {
                $fields['billing']['billing_calicantus_cf'] = array(
                    'type' => 'text',
                    'label' => __('Codice fiscale', 'labo-suisse-theme'),
                    'placeholder' => _x('Codice fiscale', 'placeholder', 'labo-suisse-theme'),
                    'required' => false,
                    'class' => array('calicantus-custom-input-cf'),
                    'show' => true,
                    'priority' => 32
                );
                /* $fields['billing']['billing_calicantus_vat'] = array(
                  'type' => 'text',
                  'label' => __('P. IVA', 'woocommerce'),
                  'placeholder' => _x('P. IVA', 'placeholder', 'woocommerce'),
                  'required' => false,
                  'class' => array('calicantus-custom-input-vat'),
                  'show' => true,
                  'priority' => 34
                  ); */
                $fields['billing']['billing_calicantus_pec'] = array(
                    'type' => 'text',
                    'label' => __('PEC', 'woocommerce'),
                    'placeholder' => _x('PEC', 'placeholder', 'woocommerce'),
                    'required' => false,
                    'class' => array('calicantus-custom-input-pec'),
                    'show' => true,
                    'priority' => 36
                );
                $fields['billing']['billing_calicantus_sdi'] = array(
                    'type' => 'text',
                    'label' => __('SDI', 'woocommerce'),
                    'placeholder' => _x('SDI', 'placeholder', 'woocommerce'),
                    'required' => false,
                    'class' => array('calicantus-custom-input-sdi'),
                    'show' => true,
                    'priority' => 38
                );
                if (isset($fields['billing']['vat_number'])) {
                    $fields['billing']['vat_number']['priority'] = 34;
                }
                return $fields;
            }

            /**
             * Mostra i campi aggiunti nell'indirizzo di fatturazione nell'admin
             */
            public function billing_custom_field_admin_order_meta($order) {
                echo '<p>';
                if (get_post_meta($order->get_id(), '_billing_calicantus_cf', true))
                    echo '<strong>' . __('Codice fiscale', 'woocommerce') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_calicantus_cf', true) . '<br/>';
                if (get_post_meta($order->get_id(), '_billing_calicantus_vat', true))
                    echo '<strong>' . __('P. IVA', 'woocommerce') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_calicantus_vat', true) . '<br/>';
                if (get_post_meta($order->get_id(), '_billing_calicantus_pec', true))
                    echo '<strong>' . __('PEC', 'woocommerce') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_calicantus_pec', true) . '<br/>';
                if (get_post_meta($order->get_id(), '_billing_calicantus_sdi', true))
                    echo '<strong>' . __('SDI', 'woocommerce') . ':</strong> ' . get_post_meta($order->get_id(), '_billing_calicantus_sdi', true) . '<br/>';
                echo '</p>';
            }

            /**
             * Aggiunge il js necessario per la validazione degli indirizzi
             */
            public function billing_country_update_checkout() {
                if (!is_checkout())
                    return;
                ?>
                <script type="text/javascript">
                    jQuery(function ($) {
                        var europa = ['AT', 'BE', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'SE', 'SI', 'SK', 'BG', 'RO'];
                        $('select#billing_country').on('change', function () {
                            manage_country();
                        });
                        $("#billing_company").on("change paste keyup", function () {
                            manage_country();
                        });
                        $('form.checkout').on('checkout_place_order', function () {
                            var sdi = jQuery('#billing_calicantus_sdi').val();
                            var pec = jQuery('#billing_calicantus_pec').val();
                            if ($('#billing_company').val() != '' && $('select#billing_country').val() == 'IT') {
                                if (sdi.length == 7) {
                                    console.log('3');
                                    return true;
                                } else {
                                    if (pec.length > 5) {
                                        console.log('4');
                                        if (validateEmail(pec))
                                            return true;
                                        else {
                                            alert('Il campo PEC non è valido.');
                                            return false;
                                        }
                                    } else {
                                        alert('Compila il campo Codice SDI oppure il campo PEC');
                                        return false;
                                    }
                                }
                            }
                        });
                        manage_country();
                        function manage_country() {
                            var country = $('select#billing_country').val();
                            if ($('#billing_company').length && $('#billing_company').val().trim().length > 0) { //company
                                if ($.inArray(country, europa) > 0) {
                                    $('#vat_number_field').show();
                                    if (country == 'IT') {
                                        $('#billing_calicantus_pec_field').show();
                                        $('#billing_calicantus_sdi_field').show();
                                    } else {
                                        $('#billing_calicantus_pec_field').hide();
                                        $('#billing_calicantus_sdi_field').hide();
                                    }
                                } else {
                                    $('#vat_number_field').hide();
                                    $('#billing_calicantus_pec_field').hide();
                                    $('#billing_calicantus_sdi_field').hide();
                                }
                            } else {
                                $('#vat_number_field').hide();
                                $('#billing_calicantus_pec_field').hide();
                                $('#billing_calicantus_sdi_field').hide();
                            }
                        }
                        function validateEmail(email) {
                            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                            return regex.test(email);
                        }
                    });
                </script>
                <?php
            }

            /**
             * Validazione indirizzo checkout lato server
             */
            public function validate_checkout() {
                $europa = array('AT', 'BE', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'SE', 'SI', 'SK', 'BG', 'RO');
                if (isset($_POST['billing_calicantus_vat']) && $_POST['billing_calicantus_vat'] != '') {
                    if ($_POST['billing_country'] == 'IT') {
                        if (!$this->check_piva($_POST['billing_calicantus_vat']))
                            wc_add_notice(__('<strong>P.IVA</strong> errata'), 'error');
                    } else if (in_array($_POST['billing_country'], $europa)) {
                        $validation = new Validation();
                        $result = $validation->check(strtoupper($_POST['billing_country']), $_POST['billing_calicantus_vat']);
                        $result = $validation->isValid();
                        $result = $result === "no_connection" ? false : $validation->isValid();
                        error_log($result);
                        if (!$result)
                            wc_add_notice(__('<strong>P.IVA</strong> errata'), 'error');
                    }
                }
            }

            /**
             * Controllo validità p.iva
             */
            public function check_piva($pi) {
                //$pi = substr($pi, 2);
                if ($pi == '')
                    return false;
                if (strlen($pi) != 11)
                    return false;
                if (!ereg("^[0-9]+$", $pi))
                    return false;
                $s = 0;
                for ($i = 0; $i <= 9; $i += 2)
                    $s += ord($pi[$i]) - ord('0');
                for ($i = 1; $i <= 9; $i += 2) {
                    $c = 2 * ( ord($pi[$i]) - ord('0') );
                    if ($c > 9)
                        $c = $c - 9;
                    $s += $c;
                }
                if (( 10 - $s % 10 ) % 10 != ord($pi[10]) - ord('0'))
                    return false;
                return true;
            }

            /**
             * Aggiunge gli eventuali indirizzi bcc alle email
             */
            public function add_bcc_to_emails($headers, $object) {
                $add_bcc_to = array(
                    'customer_invoice',
                );
                if (in_array($object, $add_bcc_to)) {
                    if ($this->bcc_addresses != '') {
                        $headers = array(
                            $headers,
                            'Bcc: ' . $this->bcc_addresses . "\r\n",
                        );
                    }
                }
                return $headers;
            }

            /**
             * Controlla che la cache dei documenti sia valida, in caso contrario la invalida
             */
            public function isCacheValid($type) {
                if ($type == 'agilis_documentation') {
                    if ($this->document_cache_time_ad != '' && ($this->document_cache_time_ad + $this->cache_ttl) > time())
                        return true;
                    else {
                        $this->setCacheInvalid('agilis_documentation');
                        return false;
                    }
                } else if ($type == 'gsped_documentation') {
                    if ($this->document_cache_time_gd != '' && ($this->document_cache_time_gd + $this->cache_ttl) > time())
                        return true;
                    else {
                        $this->setCacheInvalid('gsped_documentation');
                        return false;
                    }
                }
            }

            /**
             * Imposta la cache specifica come valida
             */
            public function setCacheValid($type) {
                $time = time();
                if ($type == 'agilis_documentation') {
                    update_option('calicantus_document_cache_time_ad', $time);
                    $this->document_cache_time_ad = $time;
                } else if ($type == 'gsped_documentation') {
                    update_option('calicantus_document_cache_time_gd', $time);
                    $this->document_cache_time_gd = $time;
                }
            }

            /**
             * Imposta la cache specifica come invalida
             */
            public function setCacheInvalid($type) {
                if ($type == 'agilis_documentation') {
                    update_option('calicantus_document_cache_time_ad', '');
                    $this->document_cache_time_ad = '';
                } else if ($type == 'gsped_documentation') {
                    update_option('calicantus_document_cache_time_gd', '');
                    $this->document_cache_time_gd = '';
                }
            }

            /**
             * Restituisce l'ID dell'ordine numerico (standard) o quello impostato con WSONP
             */
            public function getOrderId($order) {
                if (in_array('woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                    return $order->get_order_number();
                } else {
                    return $order->get_id();
                }
            }

            /**
             * Restituisce il vero ID dell'ordine (standard) anche se il plugin WSONP è attivo
             */
            public function getStatusId($rifnum) {
                if (in_array('woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                    return wc_seq_order_number_pro()->find_order_by_order_number($rifnum);
                } else {
                    return $rifnum;
                }
            }

        }

        $GLOBALS['wc_calicantus'] = new WC_Calicantus();
    }
}
?>
