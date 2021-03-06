<?php
/**
 * This is the class that manages the functions related to the views
 * 
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

/**
 * View functions
 */
class DUPX_View_Funcs
{

    public static function installerLogLink($echo = true)
    {
        $log_url = $GLOBALS['DUPX_INIT_URL'].'/'.$GLOBALS["LOG_FILE_NAME"].'?now='.DUPX_U::esc_attr($GLOBALS['NOW_TIME']);
        return DUPX_U_Html::getLightBoxIframe('installer-log.txt', 'installer-log.txt', $log_url, true, true, $echo);
    }

    public static function getHelpLink($section = '')
    {
        switch ($section) {
            case "secure" :
                $helpOpenSection = 'section-security';
                break;
            case "step1" :
                $helpOpenSection = 'section-step-1';
                break;
            case "step2" :
                $helpOpenSection = 'section-step-2';
                break;
            case "step3" :
                $helpOpenSection = 'section-step-3';
                break;
            case "step4" :
                $helpOpenSection = 'section-step-4';
                break;
            case "help" :
            default :
                $helpOpenSection = '';
        }

        return '?'.http_build_query(array(
            DUPX_Paramas_Manager::PARAM_VIEW => 'help',
            DUPX_Security::VIEW_TOKEN => DUPX_CSRF::generate('help'),
            'basic' => '',
            'open_section' => $helpOpenSection
        ));
    }

    public static function helpLink($section, $linkLabel = 'Help', $echo = true)
    {
        ob_start();
        $help_url = self::getHelpLink($section);
        DUPX_U_Html::getLightBoxIframe($linkLabel, 'HELP', $help_url);
        if ($echo) {
            ob_end_flush();
        } else {
            return ob_get_clean();
        }
    }

    public static function helpLockLink()
    {
        if ($GLOBALS['DUPX_AC']->secure_on) {
            self::helpLink('secure', '<i class="fa fa-lock fa-xs"></i>');
        } else {
            self::helpLink('secure', '<i class="fa fa-unlock-alt fa-xs"></i>');
        }
    }

    public static function helpIconLink($section)
    {
        self::helpLink($section, '<i class="fas fa-question-circle fa-sm"></i>');
    }

    /**
     * Get badge class attr val from status
     *
     * @param string $status
     * @return string html class attribute
     */
    public static function getBadgeClassFromCheckStatus($status)
    {
        switch ($status) {
            case 'Pass':
                return 'status-badge-pass';
            case 'Fail':
                return 'status-badge-fail';
            case 'Warn':
                return 'status-badge-warn';
            default:
                DUPX_Log::error(sprintf("The arcCheck var has the illegal value %s in switch case", DUPX_Log::varToString($status)));
        }
    }
}