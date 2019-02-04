<?php

declare(strict_types=1);

namespace Scherzetto\Http\Test;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Scherzetto\Http\ResponseSender;
use Psr\Http\Message\ResponseInterface;

class ResponseSenderTest extends TestCase
{
    /** @var ResponseInterface */
    private $response;

    /** @var ResponseSender */
    private $sender;

    public function setUp()
    {
        $this->response = new Response(
            404,
            [
                'Content-Type' => 'test/html; charset=UTF-8',
                'Cache-Control' => 'max-age=172800'

            ],
            "Not Found"
        );
        $this->sender = new ResponseSender($this->response);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendResponse()
    {
        ob_start();
        $this->sender->sendResponse();
        $echo = ob_get_clean();

        $this->assertEquals('Not Found', $echo);
    }

    public function testSendHeadersAlreadySent()
    {
        ob_start();
        $this->sender->sendResponse();
        $echo = ob_get_clean();

        $this->assertEquals('Not Found', $echo);
        $this->assertTrue(headers_sent());
    }
}
