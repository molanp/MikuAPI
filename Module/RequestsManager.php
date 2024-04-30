<?php
/**
 * requests类，用于发送HTTP请求
 */
class requests
{
    private $time = 5; // 超时时间，默认为5秒
    private $cookie = []; // 存储Cookie的数组
    private $header = []; // 存储请求头的数组

    /**
     * 设置超时时间
     * @param int $time 超时时间，单位为秒
     * @return void
     */
    public function timeout($time)
    {
        $this->time = $time;
    }

    /**
     * 设置Cookie
     * @param array $cookie 存储Cookie的数组
     * @return void
     */
    public function cookie($cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * 设置请求头
     * @param array $header 存储请求头的数组
     * @return void
     */
    public function header($header)
    {
        $this->header = $header;
    }

    /**
     * 发送GET请求
     * @param string $url 请求的URL地址
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    public function get($url, $data = [])
    {
        return $this->request($url, "GET", $data);
    }

    /**
     * 发送POST请求
     * @param string $url 请求的URL地址
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    public function post($url, $data = [])
    {
        return $this->request($url, "POST", $data);
    }

    /**
     * 发送PUT请求
     * @param string $url 请求的URL地址
     * @param binary $data 请求的数据，文件数据
     * @return Response 请求响应，类型为Response对象
     */
    public function put($url, $data = null)
    {
        $ip = rand(10, 200) . "." . rand(10, 200) . "." . rand(10, 200) . "." . rand(10, 200);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-FORWARDED-FOR:$ip", "CLIENT-IP:$ip"]);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.82");
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->time);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if (!empty($this->header)) {
            $headerString = [];
            foreach ($this->header as $key => $value) {
                $headerString[] = $key . ": " . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerString);
        }

        if (!empty($this->cookie)) {
            $cookieString = "";
            foreach ($this->cookie as $key => $value) {
                $cookieString .= $key . "=" . $value . "; ";
            }
            curl_setopt($ch, CURLOPT_COOKIE, rtrim($cookieString, "; "));
        }

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_PUT, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($response === false) {
            return curl_error($ch);
        }
        return new Response($headers, $body, $httpCode);
    }

    /**
     * 发送DELETE请求
     * @param string $url 请求的URL地址
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    public function delete($url, $data = [])
    {
        return $this->request($url, "DELETE", $data);
    }

    /**
     * 发送PATCH请求
     * @param string $url 请求的URL地址
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    public function patch($url, $data = [])
    {
        return $this->request($url, "PATCH", $data);
    }

    /**
     * 发送HEAD请求
     * @param string $url 请求的URL地址
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    public function head($url, $data = [])
    {
        return $this->request($url, "HEAD", $data);
    }

    /**
     * 发送CONNECT请求
     * @param string $url 请求的URL地址
     * @return Response 请求响应，类型为Response对象
     */
    public function connect($url)
    {
        return $this->request($url, "CONNECT");
    }

    /**
     * 发送TRACE请求
     * @param string $url 请求的URL地址
     * @return Response 请求响应，类型为Response对象
     */
    public function trace($url)
    {
        return $this->request($url, "TRACE");
    }

    /**
     * 发送OPTIONS请求
     * @param string $url 请求的URL地址
     * @return Response 请求响应，类型为Response对象
     */
    public function options($url)
    {
        return $this->request($url, "OPTIONS");
    }

    /**
     * 发送HTTP请求
     * @param string $url 请求的URL地址
     * @param string $method 请求的方法，例如GET、POST、PUT、DELETE等
     * @param array $data 请求的数据，以关联数组的形式传入
     * @return Response 请求响应，类型为Response对象
     */
    private function request($url, $method, $data = [])
    {
        $url = $url . "?" . http_build_query($data);
        $ip = rand(10, 200) . "." . rand(10, 200) . "." . rand(10, 200) . "." . rand(10, 200);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-FORWARDED-FOR:$ip", "CLIENT-IP:$ip"]);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36 Edg/114.0.1823.82");
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->time);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if (!empty($this->cookie)) {
            $cookieString = "";
            foreach ($this->cookie as $key => $value) {
                $cookieString .= $key . "=" . $value . "; ";
            }
            curl_setopt($ch, CURLOPT_COOKIE, rtrim($cookieString, "; "));
        }
        if (!empty($this->header)) {
            $headerString = [];
            foreach ($this->header as $key => $value) {
                $headerString[] = $key . ": " . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerString);
        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($response === false) {
            return curl_error($ch);
        }
        return new Response($headers, $body, $httpCode);
    }
}

/**
 * Response类，用于存储请求响应信息
 */
class Response extends requests
{
    private $headers; // 响应头信息
    private $body; // 响应内容
    private $httpCode; // 响应状态码

    /**
     * 构造函数，创建Response对象
     * @param string $headers 响应头信息
     * @param string $body 响应内容
     * @param int $httpCode 响应状态码
     */
    public function __construct($headers, $body, $httpCode)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->httpCode = $httpCode;
    }

    /**
     * 获取响应头信息
     * @return string 响应头信息
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * 获取响应内容
     * @return string 响应内容
     */
    public function content()
    {
        return $this->body;
    }

    /**
     * 获取响应内容
     * @return array|string 响应内容
     */
    public function json()
    {
        return json($this->body);
    }

    /**
     * 获取响应状态码
     * @return int 响应状态码
     */
    public function httpCode()
    {
        return $this->httpCode;
    }
}
