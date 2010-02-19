<?php

define('ExecPhp_VERSION', '4.7');
define('ExecPhp_PLUGIN_ID', 'exec-php');
define('ExecPhp_DIR', 'wp-content/plugins/exec-php');

define('ExecPhp_CAPABILITY_EXECUTE_WIDGETS', 'switch_themes');
define('ExecPhp_CAPABILITY_EXECUTE_ARTICLES', 'exec_php');
define('ExecPhp_CAPABILITY_WRITE_PHP', 'unfiltered_html');
define('ExecPhp_CAPABILITY_EDIT_PLUGINS', 'edit_plugins');
define('ExecPhp_CAPABILITY_EDIT_USERS', 'edit_users');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_POSTS', 'edit_others_posts');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_PAGES', 'edit_others_pages');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_PHP', 'edit_others_php');

define('ExecPhp_STATUS_OKAY', 0);
define('ExecPhp_STATUS_UNINITIALIZED', 1);
define('ExecPhp_STATUS_PLUGIN_VERSION_MISMATCH', 2);

define('ExecPhp_ACTION_REQUEST_USERS', 'execphp_request_users');
define('ExecPhp_REQUEST_FEATURE_SECURITY_HOLE', 'security_hole');
define('ExecPhp_REQUEST_FEATURE_WIDGETS', 'widgets');
define('ExecPhp_REQUEST_FEATURE_EXECUTE_ARTICLES', 'execute_articles');

define('ExecPhp_ACTION_UPDATE_OPTIONS', 'execphp_update_options');
define('ExecPhp_ACTION_UPDATE_USERMETA', 'execphp_update_usermeta');

define('ExecPhp_POST_WIDGET_SUPPORT', 'execphp_widget_support');
define('ExecPhp_POST_WYSIWYG_WARNING', 'execphp_wysiwyg_warning');

define('ExecPhp_ID_CONFIG_FORM', 'execphp-configuration');
define('ExecPhp_ID_INFO_FORM', 'execphp-information');
define('ExecPhp_ID_INFO_SECURITY_HOLE', 'execphp-security-hole');
define('ExecPhp_ID_INFO_WIDGETS', 'execphp-widgets');
define('ExecPhp_ID_INFO_EXECUTE_ARTICLES', 'execphp-execute-articles');
define('ExecPhp_ID_MESSAGE', 'execphp-message');

?>