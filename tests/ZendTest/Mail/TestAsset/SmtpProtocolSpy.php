<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mail\TestAsset;

use Zend\Mail\Protocol\Smtp;

/**
 * Test spy to use when testing SMTP protocol
 */
class SmtpProtocolSpy extends Smtp
{
    public $calledQuit = false;
    protected $connect = false;
    protected $mail;
    protected $rcptTest = array();

    public function connect()
    {
        $this->connect = true;

        return true;
    }

    public function disconnect()
    {
        $this->connect = false;
        parent::disconnect();
    }

    public function helo($serverName = '127.0.0.1')
    {
        parent::helo($serverName);
    }

    public function quit()
    {
        $this->calledQuit = true;
        parent::quit();
    }

    public function rset()
    {
        parent::rset();
        $this->rcptTest = array();
    }

    public function mail($from)
    {
        parent::mail($from);
        $this->mail = $from;
    }

    public function rcpt($to)
    {
        $this->rcpt = true;
        $this->rcptTest[] = $to;
    }

    protected function _send($request)
    {
        // Save request to internal log
        $this->_addLog($request . self::EOL);
    }

    protected function _expect($code, $timeout = null)
    {
        return '';
    }

    /**
     * Are we connected?
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->connect;
    }

    /**
     * Get value of mail property
     *
     * @return null|string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->rcptTest;
    }

    /**
     * Get Auth Status
     *
     * @return bool
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set Auth Status
     *
     * @param  bool $status
     * @return self
     */
    public function setAuth($status)
    {
        $this->auth = (bool) $status;

        return $this;
    }

    /**
     * Get Session Status
     *
     * @return bool
     */
    public function getSessionStatus()
    {
        return $this->sess;
    }

    /**
     * Set Session Status
     *
     * @param  bool $status
     * @return self
     */
    public function setSessionStatus($status)
    {
        $this->sess = (bool) $status;

        return $this;
    }
}
