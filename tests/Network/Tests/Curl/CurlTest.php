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

    public function test200Headers()
    {
        // Test 200 OK headers
        $curlMock = $this->getCurlMock();
        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue($this->getResultRequest()));

        $result = $curlMock->get("http://test.com");

        $headers = $result['headers'];

        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('Tue, 22 Aug 2011 08:45:15 GMT', $headers['Date']);
    }

    /**
     * Tests 1 line headers such as a 501 response
     */
    public function test501Headers()
    {
        $response = <<<RESPONSE
HTTP/1.1 501 Not Implemented


RESPONSE;

        // Test 501 Not Implemented Headers
        $curlMock = $this->getCurlMock();
        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue(array(
                'curl_info' => array('http_code' => 501),
                'response'  => $response
            )
        ));

        $result = $curlMock->get("http://test.com");

        $headers = $result['headers'];

        $this->assertEquals('HTTP/1.1 501 Not Implemented', $headers['HTTP']);
    }

    /**
     * Tests for 100 Continue header supported by HTTP 1.1
     * HTTP 100 should be dropped to return 2nd header status
     */
    public function test100Headers()
    {
        $response = <<<RESPONSE
HTTP/1.1 100 Continue

HTTP/1.1 200 OK
Date: Wed, 21 Sep 2011 12:54:49 GMT
Server: Apache/2.2.17 (Ubuntu)
Content-Type: text/html; charset=UTF-8

Response Body
RESPONSE;

        // Test 100 Continue Headers
        $curlMock = $this->getCurlMock();
        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue(array(
                'curl_info' => array('http_code' => 200),
                'response'  => $response
            )
        ));

        $result = $curlMock->post("http://test.com");

        $headers = $result['headers'];

        $this->assertEquals('HTTP/1.1 200 OK', $headers['HTTP']);
        $this->assertEquals('text/html; charset=UTF-8', $headers['Content-Type']);
    }

    /**
     * Tests headers than contains multiple whitespace and split correclty
     * if on LF is used, ie not CRLF
     */
    public function testMultiWhiteSpaceHeaders()
    {
$response = <<<RESPONSE
HTTP/1.1 200 OK\nDate:\tWed, 21 Sep 2011 12:54:49 GMT\nServer:       Apache/2.2.17 (Ubuntu)
Content-Type:text/html; charset=UTF-8

Response Body
RESPONSE;

        // Test 100 Continue Headers
        $curlMock = $this->getCurlMock();
        $curlMock->expects($this->once())
            ->method('doCurl')
            ->will($this->returnValue(array(
                'curl_info' => array('http_code' => 200),
                'response'  => $response
            )
        ));

        $result = $curlMock->post("http://test.com");

        $headers = $result['headers'];

        $this->assertEquals('HTTP/1.1 200 OK', $headers['HTTP']);
        $this->assertEquals('Wed, 21 Sep 2011 12:54:49 GMT', $headers['Date']);
        $this->assertEquals('Apache/2.2.17 (Ubuntu)', $headers['Server']);
        $this->assertEquals('text/html; charset=UTF-8', $headers['Content-Type']);
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
