<?php
/**
 * All actions related to user register.
 * @author Nelly Todorova <nelly.todorova@yahoo.com>
 */
class UserRegister
{
    /**
     * Database PDO Object.
     * @var PDO
     */
    protected $_dbObj = null;

    /**
     * Template Object.
     * @var Template
     */
    protected $_tplObj = null;

    /**
     * Store DB and Template Objects into internal properties.
     * @param PDO $dbObj
     * @param Template $tplObj
     * @return void
     */
    public function __construct($dbObj, $tplObj)
    {
        $this->_dbObj = $dbObj;
        $this->_tplObj = $tplObj;
    }

    /**
     * Set AJAX headers.
     * @return void
     */
    public function setAjaxHeaders()
    {
        header('Content-Type: application/json');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
    }

    /**
     * Register new user only if there is no such username or email already registered.
     * @param string $username
     * @param string $password
     * @param string $email
     * @return null|int - if successful insert, return the inserted ID
     */
    public function userRegister($username, $password, $email)
    {
        if (empty($username) || empty($password) || empty($email)) {
            return null;
        }

        $sth = $this->_dbObj->prepare("INSERT INTO users (username, password, email)
                                       SELECT * FROM (SELECT :username, :password, :email) AS tmp
                                       WHERE NOT EXISTS (SELECT username, email FROM users WHERE username = :username OR email = :email)");

        $sth->bindValue(':username', trim(mysql_real_escape_string($username)), PDO::PARAM_STR);
        $sth->bindValue(':password', trim(mysql_real_escape_string(hash('sha512', $password))), PDO::PARAM_STR);
        $sth->bindValue(':email', trim(mysql_real_escape_string($email)), PDO::PARAM_STR);
        $sth->execute();

        return $this->_dbObj->lastInsertId();
    }

    /**
     * Send confirmation email on successfull registration.
     * @param string $email
     * @param string $resultMessage
     * @return true on success or trigger error if not sent
     */
    public function sendEmailOnRegister($email, $resultMessage)
    {
        include $GLOBALS['config']['root_mailer'] . 'class.phpmailer.php';

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
        $mail->addAddress(trim($email));
        $mail->Subject = $GLOBALS['config']['messages']['emailSubject'];

        $this->_tplObj->set('emailSubject', $GLOBALS['config']['messages']['emailSubject']);
        $this->_tplObj->set('resultMessage', $resultMessage);
        $mailtext = $this->_tplObj->fetch($GLOBALS['config']['root_tpl'] . 'email.tpl');

        $mail->MsgHTML($mailtext);

        $send = $mail->send();
        unset($mail);

        if (!$send) {
            trigger_error('Mailer Error: ' . $mail->ErrorInfo, E_USER_ERROR);
        }

        return true;
    }
}
?>