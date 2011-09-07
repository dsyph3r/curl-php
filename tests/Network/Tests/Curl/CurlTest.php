<?php

namespace Network\Tests\Curl;

/**
 * CurlTest
 *
 * Tests for Curl
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->get("http://test.com");
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testPost()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->post("http://test.com");
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testPut()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->put("http://test.com");
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testPatch()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->patch("http://test.com");
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testDelete()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->delete("http://test.com");
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testHeaders()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->get("http://test.com");

        $headers = $result['headers'];

        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('Tue, 22 Aug 2011 08:45:15 GMT', $headers['Date']);
    }

    public function testData()
    {
        $curlMock = $this->getCurlMock();

        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->get("http://test.com");

        $this->assertEquals('Hello, World!', $result['data']);
    }

    protected function getCurlMock()
    {
        return $this->getMock('Network\Curl\Curl', array('doCurl'));
    }

    protected function getResultRequest()
    {
        $response = <<<RESPONSE
HTTP/1.1 200 OK
Server: nginx/1.0.4
Date: Tue, 22 Aug 2011 08:45:15 GMT
Content-Type: application/json
Connection: keep-alive
Status: 200 OK
Content-Length: 1000\r\n\r\nHello, World!
RESPONSE;

        return array(
            'curl_info' => array('http_code' => 200),
            'response'  => $response
        );
    }

}
