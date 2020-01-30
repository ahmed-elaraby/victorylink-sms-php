<?php
/**
 * Created by PhpStorm.
 * User: Araby
 * Date: 4/21/2019
 * Time: 2:18 PM
 */

namespace ITeam\VictoryLink\Model;


interface SMSInterface
{
    public const LANG_EN = 'E';
    public const LANG_AR = 'A';

    public function getText();

    public function setText(string $text);

    public function getLang();

    public function setLang(string $lang);

    public function getSender();

    public function setSender(string $sender);

    public function getReceiver();

    public function setReceiver(string $receiver);
}