<?php
declare(strict_types=1);

namespace Test\Chat\API;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Chat\DB;

class APITest extends TestCase
{
  private $http;
  private $opts;

  public function setup() : void
  {
    $this->http = new Client(['base_uri' => 'http://localhost:' . getenv("PORT")]);
    $this->opts = ["headers"=>["X-USERID"=>"10"]];
  }

  public function tearDown() {
    $this->http = null;
  }

  public function testSendMessage() : void
  {
    $response = $this->http->post('/messages/send', array_merge($this->opts, ["form_params"=>["recipient"=>20,"text"=>"Prova"]]));
    $this->assertEquals(201, $response->getStatusCode());
  }

  public function testGetMessages() : void
  {
    $response = $this->http->get('/messages', $this->opts);
    $this->assertEquals(200, $response->getStatusCode());
    $contentType = $response->getHeaders()["Content-Type"][0];
    $this->assertEquals("application/json;charset=utf-8", $contentType);
  }

  public function testBadAuth() : void
  {
    $response = $this->http->get('/messages', ['http_errors' => false]);
    $this->assertEquals(403, $response->getStatusCode());
    $response = $this->http->get('/messages', ["headers"=>["X-USERID"=>"pippo"], 'http_errors' => false]);
    $this->assertEquals(403, $response->getStatusCode());
  }

  public function testBadMessage() : void
  {
    $response = $this->http->post('/messages/send', array_merge($this->opts, ["form_params"=>["text"=>"Prova"], 'http_errors' => false]));
    $this->assertEquals(400, $response->getStatusCode());
    $response = $this->http->post('/messages/send', array_merge($this->opts, ["form_params"=>["recipient"=>"pippo","text"=>"Prova"], 'http_errors' => false]));
    $this->assertEquals(400, $response->getStatusCode());
  }
}
