<?php
    define('DIR_BASE',      dirname( dirname( __FILE__ ) ) . '/');
    define('DIR_CFG', DIR_BASE . 'config/');
    define('DIR_SYSTEM',    DIR_BASE . 'system/');
    define('DIR_VIEWS',     DIR_SYSTEM . 'views/');
    define('DIR_CTLS',      DIR_SYSTEM . 'ctls/');
    define('DIR_MDLS',      DIR_SYSTEM . 'mdls/');

    define('VIEW_HEADER',   DIR_VIEWS . 'header.html');
    define('VIEW_NAVIGATION',   DIR_VIEWS . 'nav.html');
    define('VIEW_FOOTER',   DIR_VIEWS . 'footer.html');
    define('VIEW_END', DIR_VIEWS . 'end.html');
    define('VIEW_SEARCH', DIR_VIEWS . 'search.php');
    define('VIEW_ACCOUNT_NAV', DIR_VIEWS . 'accountNav.html');
    define('VIEW_ADMIN_NAV', DIR_VIEWS . 'adminNav.html');
    define('VIEW_BODY_FORM', DIR_VIEWS . 'bodyForm.html');
    define('VIEW_LENS_FORM', DIR_VIEWS . 'lensForm.html');
    define('VIEW_TRIPOD_FORM', DIR_VIEWS . 'tripodForm.html');

    define('DIR_IMG_PROD', DIR_BASE.'resources/img/products/');


    define('CFG_DB_CONNECTION', DIR_CFG . 'connection.php');
    define('RES_UTIL', DIR_BASE. 'resources/PHP/utilities.php');
    define('RES_SQL', DIR_BASE . 'resources/PHP/sqlRequest.php');