<?php
/**
 * Created by PhpStorm.
 * User: Araby
 * Date: 4/21/2019
 * Time: 4:29 PM
 */

namespace ITeam\VictoryLink\Model;


class SMS implements SMSInterface
{
    private $text;
    private $lang;
    private $sender;
    private $receiver;

    public function __construct(string $receiver, string $text, string $sender, string $lang = self::LANG_EN)
    {
        $this->setReceiver($receiver);
        $this->setText($text);
        $this->setSender($sender);
        $this->setLang($lang);
    }


    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return SMS
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     * @return SMS
     */
    public function setLang(string $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     * @return SMS
     */
    public function setSender(string $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     * @return SMS
     */
    public function setReceiver(string $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }


}