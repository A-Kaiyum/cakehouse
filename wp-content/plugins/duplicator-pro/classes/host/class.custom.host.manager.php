<?php
/**
 * custom hosting manager
 * singleton class
 *
 * Standard: PSR-2
 *
 * @package SC\DUPX\HOST
 * @link http://www.php-fig.org/psr/psr-2/
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

require_once (DUPLICATOR_PRO_PLUGIN_PATH.'/classes/host/interface.host.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'/classes/host/class.godaddy.host.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'/classes/host/class.wpengine.host.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'/classes/host/class.wordpresscom.host.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'/classes/host/class.liquidweb.host.php');

class DUP_PRO_Custom_Host_Manager
{

    const HOST_GODADDY      = 'godaddy';
    const HOST_WPENGINE     = 'wpengine';
    const HOST_WORDPRESSCOM = 'wordpresscom';
    const HOST_LIQUIDWEB    = 'liquidweb';

    /**
     *
     * @var DUP_PRO_Custom_Host_Manager
     */
    protected static $instance = null;

    /**
     *
     * @var bool
     */
    private $inizialized = false;

    /**
     *
     * @var DUP_PRO_Host_interface[]
     */
    private $customHostings = array();

    /**
     *
     * @var string[]
     */
    private $activeHostings = array();

    /**
     *
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->customHostings[DUP_PRO_WPEngine_Host::getIdentifier()]     = new DUP_PRO_WPEngine_Host();
        $this->customHostings[DUP_PRO_GoDaddy_Host::getIdentifier()]      = new DUP_PRO_GoDaddy_Host();
        $this->customHostings[DUP_PRO_WordpressCom_Host::getIdentifier()] = new DUP_PRO_WordpressCom_Host();
        $this->customHostings[DUP_PRO_Liquidweb_Host::getIdentifier()]    = new DUP_PRO_Liquidweb_Host();
    }

    public function init()
    {
        if ($this->inizialized) {
            return true;
        }
        foreach ($this->customHostings as $cHost) {
            if (!($cHost instanceof DUP_PRO_Host_interface)) {
                throw new Exception('Host must implemnete DUP_PRO_Host_interface');
            }
            if ($cHost->isHosting()) {
                $this->activeHostings[] = $cHost->getIdentifier();
                $cHost->init();
            }
        }
        $this->inizialized = true;
        return true;
    }

    public function getActiveHostings()
    {
        return $this->activeHostings;
    }

    public function isHosting($identifier)
    {
        return in_array($identifier, $this->activeHostings);
    }

    public function getHosting($identifier)
    {
        if ($this->isHosting($identifier)) {
            return $this->activeHostings[$identifier];
        } else {
            return false;
        }
    }
}