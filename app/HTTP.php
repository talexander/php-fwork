<?php
/**
 * User: tarakanov
 */

namespace App;


class Http {

    /**
     * @var  The default protocol to use if it cannot be detected
     */
    public static $defaultProtocol = 'HTTP/1.1';

    /**
     * Issues a HTTP redirect.
     *
     * @param  string    $uri       URI to redirect to
     * @param  int       $code      HTTP Status code to use for the redirect
     * @throws HTTP_Exception
     */
// @TODO: implement
//    public static function redirect($uri = '', $code = 302)
//    {
//        $e = HTTP_Exception::factory($code);
//
//        if ( ! $e instanceof HTTP_Exception_Redirect)
//            throw new Kohana_Exception('Invalid redirect code \':code\'', array(
//                ':code' => $code
//            ));
//
//        throw $e->location($uri);
//    }

    /**
     * Parses a HTTP header string into an associative array
     *
     * @param   string   $header_string  Header string to parse
     * @return  HTTP_Header
     */
    public static function parse_header_string($header_string) {

        // Otherwise we use the slower PHP parsing
        $headers = array();

        // Match all HTTP headers
        if (preg_match_all('/(\w[^\s:]*):[ ]*([^\r\n]*(?:\r\n[ \t][^\r\n]*)*)/', $header_string, $matches))
        {
            // Parse each matched header
            foreach ($matches[0] as $key => $value)
            {
                // If the header has not already been set
                if ( ! isset($headers[$matches[1][$key]]))
                {
                    // Apply the header directly
                    $headers[$matches[1][$key]] = $matches[2][$key];
                }
                // Otherwise there is an existing entry
                else
                {
                    // If the entry is an array
                    if (is_array($headers[$matches[1][$key]]))
                    {
                        // Apply the new entry to the array
                        $headers[$matches[1][$key]][] = $matches[2][$key];
                    }
                    // Otherwise create a new array with the entries
                    else
                    {
                        $headers[$matches[1][$key]] = array(
                            $headers[$matches[1][$key]],
                            $matches[2][$key],
                        );
                    }
                }
            }
        }

        // Return the headers
        return new HTTP_Header($headers);
    }

    /**
     * Parses the the HTTP request headers and returns an array containing
     * key value pairs. This method is slow, but provides an accurate
     * representation of the HTTP request.
     *
     *      // Get http headers into the request
     *      $request->headers = HTTP::request_headers();
     *
     * @return  HTTP_Header
     */
    public static function request_headers() {
        // Setup the output
        $headers = array();

        // Parse the content type
        if ( ! empty($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
        }

        // Parse the content length
        if ( ! empty($_SERVER['CONTENT_LENGTH'])) {
            $headers['content-length'] = $_SERVER['CONTENT_LENGTH'];
        }

        foreach ($_SERVER as $key => $value) {
            // If there is no HTTP header here, skip
            if (strpos($key, 'HTTP_') !== 0) {
                continue;
            }

            // This is a dirty hack to ensure HTTP_X_FOO_BAR becomes x-foo-bar
            $headers[str_replace(array('HTTP_', '_'), array('', '-'), $key)] = $value;
        }

        return new HTTP_Header($headers);
    }
}