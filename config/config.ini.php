<?php
global $config;

/**
 * All paths settings using the global variable $config.
 */
$config['folder'] = '/Akristo/';
$config['http_root'] = 'http://' . $_SERVER['HTTP_HOST'] . $config['folder'];
$config['http_root_tpl'] = $config['http_root'] . 'tpl/';
$config['http_root_css'] = $config['http_root_tpl'] . 'css/';
$config['http_root_js'] = $config['http_root_tpl'] . 'js/';

$config['root'] = $_SERVER['DOCUMENT_ROOT'] . $config['folder'];
$config['root_lib'] = $config['root'] . 'lib/';
$config['root_mailer'] = $config['root_lib'] . 'PHPMailer/';
$config['root_tpl'] = $config['root'] . 'tpl/';

/**
 * Database configuration settings.
 */
$config['db']['host'] = 'localhost';
$config['db']['database'] = 'akristo';
$config['db']['user'] = 'root';
$config['db']['password'] = '';

/**
 * Result messages settings.
 */
$config['messages'] = array(
    'success' => array(
        'text' => 'Успешна регистрация. Изпратен е емайл.',
        'class' => 'success',
    ),
    'exists' => array(
        'text' => 'Потребителското име или емайла вече съществуват!',
        'class' => 'error',
    ),
    'emailSubject' => 'Успешна регистрация'
);

/**
 * Fields configration.
 */
$config['fields'] = array(
    'username' => array(
        'name' => 'username',
        'label' => 'Потребителско име:',
        'type' => 'text',
        'size' => 20,
        'minSymbols' => 3,
        'maxSymbols' => 20,
        'notEmpty' => true,
        'validateRegEx' => '',
        'errorMessage' => 'Please enter correct username!',
    ),
    'password' => array(
        'name' => 'password',
        'label' => 'Парола:',
        'type' => 'password',
        'size' => 20,
        'minSymbols' => 3,
        'maxSymbols' => 20,
        'notEmpty' => true,
        'validateRegEx' => '',
        'errorMessage' => 'Please enter correct password!',
    ),
    'email' => array(
        'name' => 'email',
        'label' => 'Email:',
        'type' => 'text',
        'size' => 40,
        'minSymbols' => 5,
        'maxSymbols' => 40,
        'notEmpty' => true,
        'validateRegEx' => 'FILTER_VALIDATE_EMAIL',
        'errorMessage' => 'Please enter correct email!',
    ),
);
?>