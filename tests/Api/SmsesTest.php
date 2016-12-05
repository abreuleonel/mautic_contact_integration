<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     MIT http://opensource.org/licenses/MIT
 */

namespace Mautic\Tests\Api;

class SmsesTest extends MauticApiTestCase
{
    protected $testPayload = array(
        'name' => 'test',
        'message' => 'API test message'
    );

    protected $context = 'smses';

    protected $itemName = 'sms';

    public function testGetList()
    {
        $apiContext = $this->getContext($this->context);
        $response   = $apiContext->getList();
        $this->assertErrors($response);
    }

    public function testCreateGetAndDelete()
    {
        $apiContext = $this->getContext($this->context);

        // Test Create
        $response = $apiContext->create($this->testPayload);
        $this->assertPayload($response);

        // Test Get
        $response = $apiContext->get($response[$this->itemName]['id']);
        $this->assertPayload($response);

        // Test Delete
        $response = $apiContext->delete($response[$this->itemName]['id']);
        $this->assertErrors($response);
    }

    public function testEditPatch()
    {
        $smsApi   = $this->getContext($this->context);
        $response = $smsApi->edit(10000, $this->testPayload);

        //there should be an error as the sms shouldn't exist
        $this->assertTrue(isset($response['error']), $response['error']['message']);

        $response = $smsApi->create($this->testPayload);
        $this->assertErrors($response);

        $response = $smsApi->edit(
            $response[$this->itemName]['id'],
            array(
                'name' => 'test2'
            )
        );

        $this->assertErrors($response);

        //now delete the sms
        $response = $smsApi->delete($response[$this->itemName]['id']);
        $this->assertErrors($response);
    }

    public function testEditPut()
    {
        $smsApi = $this->getContext($this->context);
        $response    = $smsApi->edit(10000, $this->testPayload, true);
        $this->assertPayload($response);

        //now delete the sms
        $response = $smsApi->delete($response[$this->itemName]['id']);
        $this->assertErrors($response);
    }
}
