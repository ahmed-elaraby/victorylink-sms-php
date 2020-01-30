<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Created by PhpStorm.
 * User: Araby
 * Date: 4/21/2019
 * Time: 2:48 PM
 */

use ITeam\VictoryLink\VLClient;

class VLClientTest extends \PHPUnit\Framework\TestCase
{
    /** @var VLClient */
    private $client;

    protected function setUp()
    {
        $this->client = new VLClient('MAZAYA', 'H4A919369E');
    }

    public function testCheckCredit()
    {
        $this->assertEquals('25008', $this->client->checkCredit());
    }

    public function testSendSMS()
    {
        $sms = new \ITeam\VictoryLink\Model\SMS('01122009002', 'Test SMS', 'Mazaya');
        $this->assertTrue($this->client->sendSMS($sms));
    }
}
