<?php
require './config/config.ini.php';
require $GLOBALS['config']['root_lib'] . 'FormsActions.php';
require $GLOBALS['config']['root_lib'] . 'Template.php';
require $GLOBALS['config']['root_mailer'] . 'class.phpmailer.php';

/**
 * Initialize PDO Dtatabase object.
 * @var PDO
 */
try {
    $dbObj = new PDO('mysql:host=' . $GLOBALS['config']['db']['host'] . ';dbname=' . $GLOBALS['config']['db']['database'], $GLOBALS['config']['db']['user'], $GLOBALS['config']['db']['password']);
} catch (PDOException $e) {
    echo $e->getMessage();
}

/**
 * Template Object.
 * @var Template
 */
$tplObj = new Template();

/**
 * Fetch fields configuration.
 */
$fieldsConfig = $GLOBALS['config']['fields'];

/**
 * Process form submit.
 * Works even if Javascript is disabled.
 */
if (!empty($_POST['submiForm']) && (int)$_POST['submiForm'] == 1) {
    $resultMessage = '';
    $resultClass = '';

    if (!empty($_POST['ajaxSubmit'])) {
        header('Content-Type: application/json');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
    }

    $verify = FormsActions::verifyFields($fieldsConfig);

    if (empty($verify)) {
        /**
         * Insert new user only if there is no such username or email already registered.
         */
        $sth = $dbObj->prepare("INSERT INTO users (username, password, email)
                                SELECT * FROM (SELECT :username, :password, :email) AS tmp
                                WHERE NOT EXISTS (SELECT username, email FROM users WHERE username = :username OR email = :email)");

        $sth->bindValue(':username', trim(mysql_real_escape_string($_POST['username'])), PDO::PARAM_STR);
        $sth->bindValue(':password', trim(mysql_real_escape_string(hash('sha512', $_POST['password']))), PDO::PARAM_STR);
        $sth->bindValue(':email', trim(mysql_real_escape_string($_POST['email'])), PDO::PARAM_STR);
        $sth->execute();

        /**
         * If successful insert.
         */
        if ($dbObj->lastInsertId() > 0) {
            $resultMessage = $GLOBALS['config']['messages']['success']['text'];
            $resultClass = $GLOBALS['config']['messages']['success']['class'];

            /**
             * Send email.
             */
            $mail = new PHPMailer();
            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'nellysttodorova@gmail.com';
            $mail->Password = '*******';

            $mail->setFrom('nellysttodorova@gmail.com', 'Nelly Todorova');
            $mail->addReplyTo('nellysttodorova@gmail.com', 'Nelly Todorova');
            $mail->addAddress(trim($_POST['email']));
            $mail->Subject = $GLOBALS['config']['messages']['emailSubject'];

            $tplObj->set('emailSubject', $GLOBALS['config']['messages']['emailSubject']);
            $tplObj->set('resultMessage', $resultMessage);
            $mailtext = $tplObj->fetch($GLOBALS['config']['root_tpl'] . 'email.tpl');
            
            $mail->MsgHTML($mailtext);

            if (!$mail->send()) {
                trigger_error('Mailer Error: '. $mail->ErrorInfo, E_USER_ERROR);
            }

            unset($mail);
        } else {
            $resultMessage = $GLOBALS['config']['messages']['exists']['text'];
            $resultClass = $GLOBALS['config']['messages']['exists']['class'];
        }
    }

    $dbObj = null;

    if (!empty($_POST['ajaxSubmit'])) {
        echo json_encode(array('message' => $resultMessage, 'class' => $resultClass));
        return;
    }

    $tplObj->set('verify', $verify);
    $tplObj->set('resultMessage', $resultMessage);
    $tplObj->set('resultClass', $resultClass);
}

$tplObj->set('http_root_js', $GLOBALS['config']['http_root_js']);
$tplObj->set('http_root_css', $GLOBALS['config']['http_root_css']);
$tplObj->set('fieldsConfig', $fieldsConfig);

/**
 * Fetch the main.tpl file where all HTML is printed.
 */
echo $tplObj->fetch($GLOBALS['config']['root_tpl'] . 'main.tpl');
?>