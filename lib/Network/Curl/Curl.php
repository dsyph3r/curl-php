<?php

namespace Network\Curl;

/**
 * Curl
 *
 * Simple cURL wrapper for basic HTTP requests. Support available
 * for GET, POST, PUT, PATCH and DELETE
 *
 * @author    dsyph3r <d.syph.3r@gmail.com>
 */
class Curl
{
    /**
     * Constants for available HTTP methods
     */
    const GET     = 'GET';
    const POST    = 'POST';
    const PUT     = 'PUT';
    const PATCH   = 'PATCH';
    const DELETE  = 'DELETE';

    /**
     * Make a HTTP GET request
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any GET params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    public function get($url, $params = array(), $options = array())
    {
        return $this->request($url, self::GET, $params, $options);
    }

    /**
     * Make a HTTP POST request
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any POST params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    public function post($url, $params = array(), $options = array())
    {
        return $this->request($url, self::POST, $params, $options);
    }

    /**
     * Make a HTTP PUT request
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PUT params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    public function put($url, $params = array(), $options = array())
    {
        return $this->request($url, self::PUT, $params, $options);
    }

    /**
     * Make a HTTP PATCH request
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any PATCH params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    public function patch($url, $params = array(), $options = array())
    {
        return $this->request($url, self::PATCH, $params, $options);
    }

    /**
     * Make a HTTP DELETE request
     *
     * @param   string  $url          Full URL including protocol
     * @param   array   $params       Any DELETE params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    public function delete($url, $params = array(), $options = array())
    {
        return $this->request($url, self::DELETE, $params, $options);
    }

    /**
     * Make a HTTP request
     *
     * @param   string  $url          Full URL including protocol
     * @param   string  $method       HTTP method
     * @param   array   $params       Any params
     * @param   array   $options      Additional options for the request
     * @return  array                 Response
     */
    protected function request($url, $method = self::GET, $params = array(), $options = array())
    {
        $curl = curl_init();

        // Setup HTTP method specifics
        switch ($method) {
            case self::GET:
                if (count($params)) {
                  $url .= '?' . http_build_query($params);
                }
                break;
            case self::POST:
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case self::PUT:
                curl_setopt($curl, CURLOPT_PUT, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                //curl_setopt($curl, CURLOPT_INFILESIZE, strlen($params));
                break;
            case self::PATCH:
            case self::DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default:
                throw new CurlException('Unsupported HTTP request method ' . $method);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Check for custom headers
        if (isset($options['headers']) && count($options['headers']))
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);

        $response = $this->doCurl($curl);

        curl_close($curl);

        // Separate headers and body
        $responseSplit = explode("\r\n\r\n", $response['response']);

        $results = array(
            'curl_info'     => $response['curl_info'],
            'status'        => $response['curl_info']['http_code'],
            'headers'       => $this->splitHeaders($responseSplit[0]),
            'data'          => $responseSplit[1],
        );

        return $results;
    }

    /**
     * Split the HTTP headers
     *
     * @param   string  $rawHeaders     Raw HTTP headers
     * @return  array                   Key/Value headers
     */
    protected function splitHeaders($rawHeaders)
    {
        $headers = array();

        $headerLines = explode("\n", $rawHeaders);
        $headers['HTTP'] = array_shift($headerLines);
        foreach ($headerLines as $line) {
            $header = explode(":", $line, 2);
            $headers[trim($header[0])] = trim($header[1]);
        }
        return $headers;
    }

    /**
     * Perform the Curl request
     *
     * @param   cURL Handle     $curl       The cURL handle to use
     * @return  array                       cURL response
     */
    protected function doCurl($curl)
    {
        $response     = curl_exec($curl);
        $curlInfo     = curl_getinfo($curl);

        $results = array(
            'curl_info'     => $curlInfo,
            'response'      => $response,
        );

        return $results;
    }

}

/**
 * General Curl Exception
 */
class CurlException extends \Exception
{
}
