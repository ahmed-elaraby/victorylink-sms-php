<?php
/**
 * Created by PhpStorm.
 * User: Araby
 * Date: 4/21/2019
 * Time: 2:28 PM
 */
namespace ITeam\VictoryLink;

use ITeam\VictoryLink\Exceptions\InvalidLanguage;
use ITeam\VictoryLink\Exceptions\InvalidSender;
use ITeam\VictoryLink\Exceptions\OutOfCredit;
use ITeam\VictoryLink\Exceptions\SendingRateTooHigh;
use ITeam\VictoryLink\Exceptions\SMSEmpty;
use ITeam\VictoryLink\Exceptions\UserNotSubscribed;
use ITeam\VictoryLink\Model\SMSInterface;

/**
 * Class VLClient
 * @package ITeam\VictoryLink
 */
class VLClient
{
    /**
     * @var string
     */
    protected $wsdlUrl;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var \SoapClient
     */
    protected $client;

    /**
     * VLClient constructor.
     * @param string $username
     * @param string $password
     * @param string|null $wsdlUrl
     */
    public function __construct(string $username, string $password, string $wsdlUrl = null)
    {
        $this->username = $username;
        $this->password = $password;
        if ($this->wsdlUrl === null) {
            $wsdlUrl = 'https://smsvas.vlserv.com/KannelSending/service.asmx?WSDL';
        }
        $this->wsdlUrl = $wsdlUrl;

        $this->client = new \SoapClient($this->wsdlUrl, [
            'trace' => 1,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            ))
        ]);
    }

    /**
     * @return bool
     * @throws InvalidLanguage
     * @throws InvalidSender
     * @throws OutOfCredit
     * @throws SMSEmpty
     * @throws SendingRateTooHigh
     * @throws UserNotSubscribed
     */
    public function checkCredit()
    {
        $response = $this->client->CheckCredit([
            'username' => $this->username,
            'password' => $this->password
        ]);

        return $this->_handleResponse($response->CheckCreditResult, $response->CheckCreditResult);
    }

    /**
     * @param SMSInterface $sms
     * @return bool
     * @throws InvalidLanguage
     * @throws InvalidSender
     * @throws OutOfCredit
     * @throws SMSEmpty
     * @throws SendingRateTooHigh
     * @throws UserNotSubscribed
     */
    public function sendSMS(SMSInterface $sms)
    {
        $response = $this->client->SendSMS([
            'UserName' => $this->username,
            'Password' => $this->password,
            'SMSText' => $sms->getText(),
            'SMSLang' => $sms->getLang(),
            'SMSSender' => $sms->getSender(),
            'SMSReceiver' => $sms->getReceiver(),
        ]);

        return $this->_handleResponse($response->SendSMSResult);
    }

    /**
     * @param int $responseValue
     * @param bool $successValue
     * @return mixed
     * @throws InvalidLanguage
     * @throws InvalidSender
     * @throws OutOfCredit
     * @throws SMSEmpty
     * @throws SendingRateTooHigh
     * @throws UserNotSubscribed
     */
    private function _handleResponse(int $responseValue, $successValue = true)
    {
        switch ($responseValue) {
            case -1:
                throw new UserNotSubscribed('User is not subscribed', $responseValue);
            case -5:
                throw new OutOfCredit('Out Of Credit', $responseValue);
            case -11:
                throw new InvalidLanguage('Invalid Language', $responseValue);
            case -12:
                throw new SMSEmpty('SMSInterface is empty', $responseValue);
            case -13:
                throw new InvalidSender('Sender is invalid', $responseValue);
            case -25:
                throw new SendingRateTooHigh('Sending rate greater than receiving rate', $responseValue);
            case -100:
                throw new \Exception('Unknown error occurred', $responseValue);
            default:
                return $successValue;
        }
    }
}