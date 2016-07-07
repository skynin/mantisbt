<?php
/**
 * DokuWiki Plugin authmantis (Auth Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Ventzy <v.kunev@gmail.com>
 * Original integration guide: http://www.mantisbt.org/wiki/doku.php/mantisbt:issue:7075:integration_with_dokuwiki
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class auth_plugin_authmantis extends DokuWiki_Auth_Plugin {

    public function __construct() {
        parent::__construct(); // for compatibility

        $this->cando['external'] = true;
        $this->cando['logoff'] = true;

        $this->success = true;
    }

    /**
     * Log off the current user
     */
    public function logOff() {
        auth_logout();
    }

    /**
     * Do all authentication
     *
     * @param   string  $user    Username
     * @param   string  $pass    Cleartext Password
     * @param   bool    $sticky  Cookie should not expire
     * @return  bool             true on successful auth
     */
    public function trustExternal($user, $pass, $sticky = false) {
        global $USERINFO;
        global $conf;

        $ValidUser = false;

		if (!function_exists('auth_prepare_username')) return true;

        // Manage HTTP authentication with Negotiate protocol enabled
        $user = auth_prepare_username($user);
        $pass = auth_prepare_password($pass);
        // This is necessary in all cases where Authorization HTTP header is always set
        if (auth_is_user_authenticated()) {
            $user = '';
        }

        // Has a user name been provided?
        if (!empty($user)) {
            // User name provided, so login via form in progress...
            // Are the specified user name and password valid?
            if (auth_attempt_login($user, $pass, $sticky)) {
                // Credential accepted...
                $_SERVER['REMOTE_USER'] = $user; // Set the user name (makes things work...)
                $ValidUser = true; // Report success.
            } else {
                // Invalid credentials
                if (!$silent) {
                    msg($this->lang ['badlogin'], -1);
                }

                $ValidUser = false;
            }
        } else {
            // No user name provided.
            // Is a user already logged in?
            if (auth_is_user_authenticated()) {
                // Yes, a user is logged in, so set the globals...
                // is it a media display or a page?
                if (isset($_REQUEST['media'])) {
                    //media
                    $t_project_name = explode(':', getNS(getID("media", false)));
                } else {
                    // normal page
                    $t_project_name = explode(':', getNS(getID()));
                }
                $t_project_id = project_get_id_by_name($t_project_name[1]);
                $t_access_level = access_get_project_level($t_project_id);
                $t_access_level_string = strtoupper(MantisEnum::getLabel(config_get('access_levels_enum_string'), $t_access_level)); // mantis 1.2.0rc
                // $t_access_level_string = strtoupper( get_enum_to_string( config_get( 'access_levels_enum_string' ),  $t_access_level ) );
                $t_access_level_string_ex = strtoupper($t_project_name[1]) . '_' . $t_access_level_string;

                $USERINFO['grps'] = array($t_access_level_string, $t_access_level_string_ex);
                $USERINFO['pass'] = current_user_get_field('password');
                $USERINFO['name'] = current_user_get_field('username');
                $USERINFO['mail'] = current_user_get_field('email');

                $_SERVER['REMOTE_USER'] = $USERINFO['name'];
                $_SESSION[$conf['title']]['auth']['user'] = $USERINFO['name'];
                $_SESSION[$conf['title']]['auth']['info'] = $USERINFO;

                $ValidUser = true;
            } else {
                $ValidUser = false;
            }
        }

        // Is there a valid user login?
        if (true != $ValidUser) {
            // No, so make sure any existing authentication is revoked.
            auth_logoff();
        }

        return $ValidUser;
    }

}
