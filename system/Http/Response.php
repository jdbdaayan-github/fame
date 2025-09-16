<?php
namespace System\Http;

class Response
{
    protected $content;
    protected $status;
    protected $headers;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status  = $status;
        $this->headers = $headers;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function header(string $key, string $value)
    {
        $this->headers[$key] = $value;
    }

    public function send()
    {
        // Set HTTP status
        http_response_code($this->status);

        // Send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        // Send body (fallback to empty string)
        echo $this->content ?? '';
    }
}
