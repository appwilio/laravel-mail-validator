<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 23.08.16
 * Time: 10:31
 */

namespace App\Lib\Validators\Smtp;


use Symfony\Component\Config\Definition\Exception\Exception;

class SmtpSocket
{
    /*
     * @type boolean
    */
    protected $authorises = false;
    /**
     * @type string
     */
    protected $host;
    /**
     * @type string
     */
    protected $login;
    /**
     * @type string
     */
    protected $password;
    /**
     * @type integer
     */
    protected $port = 25;
    /**
     * @type boolean
     */
    protected $ssl = false;

    /**
     * @type boolean
     */
    protected $tls = false;
    /**
     * @type integer
     */
    protected $timeout = 30;

    /**
     * @var string
     */
    protected $_newLine = "\r\n";

    /**
     * @type resource
     */
    protected $_smtpConnect = null;
    protected $_errno;
    protected $_errstr;


    protected $_debug = [];

    /**
     * TODO: this is madness
     * @var array
     */
    protected $commands = [];

    /**
     * SmtpSocket constructor.
     */
    public function __construct()
    {
        $this->commands = [
            "auth_login" => [
                "expect" => 334,
                "function" => function ($data = null) {
                    return "AUTH LOGIN";
                }
            ],
            "auth_login_user" => [
                "expect" => 334,
                "function" => function ($data = null) {
                    return base64_encode($this->getLogin());
                }
            ],
            "auth_login_password" => [
                "expect" => 235,
                "function" => function ($data = null) {
                    return base64_encode($this->getPassword());
                }
            ],
            "hello" => [
                "expect" => 250,
                "function" => function ($data = null) {
                    return "EHLO me";
                }
            ],
            "starttls" => [
                "expect" => 220,
                "function" => function ($data = null) {
                    return "STARTTLS";
                }
            ],
            "from" => [
                "expect" => 250,
                "function" => function ($from = null) {
                    return "MAIL FROM:<$from>";
                }
            ],
            "to" => [
                "expect" => 250,
                "function" => function ($to = null) {
                    return "RCPT TO:<{$to}>";
                }
            ],
            "data_start" => [
                "expect" => 354,
                "function" => function ($data = null) {
                    return "DATA";
                }
            ],
            "data_end" => [
                "expect" => 250,
                "function" => function ($data = null) {
                    return ".";
                }
            ],
            "quit" => [
                "expect" => 221,
                "function" => function ($data = null) {
                    return "QUIT";
                }
            ]
        ];
    }

    /**
     * @param $to
     * @param $data
     * @param $from
     * @return $this
     * @throws CriticalSocketException
     * @throws InformativeSocketException
     */
    public function send($to, $data, $from)
    {
        $this->connect()
            ->command("from", $from)
            ->command("to", $to)
            ->sendData($data)
            ->command("quit")
            ->closeSocket();

        return $this;
    }

    /**
     * @param $to
     * @param $from
     * @return boolean
     * @throws CriticalSocketException
     * @throws InformativeSocketException
     */
    public function check($to, $from)
    {
        $this->connect()
            ->command("from", $from);

        try{
            $this->command("to", $to);
        } catch (InformativeSocketException $e) {
            $this->command("quit")
                ->closeSocket();
            return false;
        }

        $this->command("quit")
            ->closeSocket();

        return true;
    }

    /**
     * @param mixed $message
     * @param string $type
     */
    protected function writeDebug($message, $type = "Unknown")
    {
        $this->_debug[] = [
            "type" => $type,
            "time" => date("Y\\m\\d H:i:s"),
            "data" => $message
        ];
        return $this;
    }

    /**
     * @return $this
     */
    protected function clearDebug()
    {
        $this->_debug = [];
        return $this;
    }

    /**
     * @return $this
     * @throws CriticalSocketException
     */
    protected function connect()
    {

        $this->clearDebug();

        if (is_resource($this->_smtpConnect)) {
            throw new CriticalSocketException("smtp connect already established");
        }

        $this->initSocket();

        $this->writeDebug($this->readFromSocket(), "initSocket");

        if ($this->tls) {
            $this->initTls();
        }

        $this->command("hello");

        if ($this->authorises) {
            $this->authorise();
        }

        return $this;
    }

    /**
     * @return $this
     * @throws CriticalSocketException
     */
    protected function initSocket()
    {
        $this->_smtpConnect = fsockopen((($this->ssl) ? "ssl://" : "") . $this->host,
            $this->port,
            $this->_errno,
            $this->_errstr,
            $this->timeout
        );

        if (!is_resource($this->_smtpConnect)) {
            throw new CriticalSocketException("Cant open socket: {$this->_errstr}", $this->_errno);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function closeSocket()
    {
        fclose($this->_smtpConnect);
        return $this;
    }

    /**
     * @return $this
     * @throws InformativeSocketException
     */
    protected function initTls()
    {
        $this->command("hello")
            ->command("starttls");

        stream_socket_enable_crypto($this->_smtpConnect, TRUE, STREAM_CRYPTO_METHOD_TLS_CLIENT);

        return $this;
    }

    /**
     * @return $this
     * @throws InformativeSocketException
     */
    protected function authorise()
    {
        $this->command("hello")
            ->command("auth_login")
            ->command("auth_login_user")
            ->command("auth_login_password");

        return $this;
    }

    /**
     * @param $data
     * @return $this
     * @throws CriticalSocketException
     */
    protected function sendToSocket($data)
    {
        if (!fwrite($this->_smtpConnect, $data . $this->_newLine)) {
            throw new CriticalSocketException("Cant write to socket");
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function readFromSocket()
    {
        $data = "";

        while ($str = fgets($this->_smtpConnect, 512)) {
            $data .= $str;

            if (substr($str, 3, 1) == " ") {
                break;
            }
        }

        return $data;
    }

    /**
     * @param $response
     * @return integer
     */
    protected function fetchCode($response)
    {
        return (int)substr($response, 0, 3);
    }

    /**
     * @param $response
     * @return string
     */
    protected function fetchMessage($response)
    {
        return substr($response, 4);
    }

    /**
     * @param $cmd
     * @param null $data
     * @return $this
     * @throws CriticalSocketException
     * @throws InformativeSocketException
     */
    protected function command($cmd, $data = null)
    {
        if (!isset($this->commands[$cmd])) {
            throw new CriticalSocketException("No such command {$cmd}");
        }

        $command = new Command($this->commands[$cmd]["expect"], $this->commands[$cmd]["function"]);

        $socketMessage = $command->format($data);
        $this->writeDebug($socketMessage, "SEND");
        $this->sendToSocket($socketMessage);

        $response = $this->readFromSocket();
        $this->writeDebug("{$response}", "RESPONSE");

        $code = $this->fetchCode($response);

        if (!$code || "" === trim($code)) {
            throw new CriticalSocketException("Cant get response from socket");
        }

        if ($command->getExpectCode() !== $code) {
            throw new InformativeSocketException($this->fetchMessage($response));
        }

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    protected function sendData($data)
    {
        $this->command("data_start")
            ->sendToSocket(str_replace(".", "\.", $data))
            ->command("data_end");

        return $this;
    }

    /**
     * GETTERS AND SETTERS
     */

    /**
     * @return boolean
     */
    public function getAuthorises()
    {
        return $this->authorises;
    }

    /**
     * @param boolean $authorised
     */
    public function setAuthorises($authorises)
    {
        $this->authorises = (boolean)$authorises;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = (int)$port;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSsl()
    {
        return $this->ssl;
    }

    /**
     * @param boolean $ssl
     */
    public function setSsl($ssl)
    {
        $this->ssl = (boolean)$ssl;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTls()
    {
        return $this->tls;
    }

    /**
     * @param boolean $tls
     */
    public function setTls($tls)
    {
        $this->tls = (boolean)$tls;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return (int)$this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return array
     */
    public function getDebug()
    {
        return var_export($this->_debug, true);
    }
}