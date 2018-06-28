<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews;

use Psr\Log\NullLogger;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Subscriber\Mock as GuzzleMock;
use GuzzleHttp\Message\Response as GuzzleResponse;
use GuzzleHttp\Stream\Stream as GuzzleStream;
use Triniti\Notify\Notifier\AppleNews\AppleNewsApiClient;
use Triniti\Tests\Notify\AbstractPbjxTest;

//fixme: convert to guzzle 6
class AppleNewsApiClientTest extends AbstractPbjxTest
{
    /* @var AppleNewsApiClient */
    protected $appleNewsApiClient;

    public function setUp()
    {
        $this->markTestSkipped();
        $logger = new NullLogger();
        $this->appleNewsApiClient = new AppleNewsApiClient($logger, 'https://news-api.apple.com', 'key', 'secret');
    }

    /**
     * @test init test
     */
    public function testCreateAppleNewsApiClient()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\AppleNewsApiClient', $this->appleNewsApiClient);
    }

    /**
     * @test testGenerateEndPoint
     *
     * @param string $endPoint
     * @param array $pathId
     * @param string $expected
     * @dataProvider providerTestGenerateEndPoint
     */
    public function testGenerateEndPoint($endPoint, array $pathId, $expected)
    {
        $generatedEndPoint = $this->invokeMethod($this->appleNewsApiClient, 'generateEndPoint', [$endPoint, $pathId]);
        $this->assertEquals($expected, $generatedEndPoint);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @test testCreateAppleNewsMetadata
     *
     */
    public function testCreateAppleNewsMetadata()
    {
        $metaJson = '{"data": {"revision": "AA"}}';
        $metadata = $this->invokeMethod($this->appleNewsApiClient, 'createAppleNewsMetadata', [$metaJson]);

        $contentDispositionParts = ['name=metadata'];
        $expectedMetadata = '';
        $expectedMetadata .= '--' . $this->accessProtected($this->appleNewsApiClient, 'boundary') . "\r\n";
        $expectedMetadata .= 'Content-Type: application/json' . "\r\n";
        $expectedMetadata .= 'Content-Disposition: form-data; ' . join('; ', $contentDispositionParts) . "\r\n";
        $expectedMetadata .= "\r\n" . $metaJson . "\r\n";
        $this->assertEquals($expectedMetadata, $metadata);

        $metaJson = '';
        $metadata = $this->invokeMethod($this->appleNewsApiClient, 'createAppleNewsMetadata', [$metaJson]);
        $this->assertEmpty($metadata);
    }

    public function accessProtected($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    /**
     * @test testCreateAppleNewsDocument
     *
     */
    public function testCreateAppleNewsDocument()
    {
        $documentJson = '{"version": "1.4"}';
        $document = $this->invokeMethod($this->appleNewsApiClient, 'createAppleNewsDocument', [$documentJson]);
        $contentDispositionParts = [
            'name=article',
            'filename=article.json',
            'size=' . \strlen($documentJson)
        ];

        $expectedDocument = '';
        $expectedDocument .= '--' . $this->accessProtected($this->appleNewsApiClient, 'boundary') . "\r\n";
        $expectedDocument .= 'Content-Type: application/json' . "\r\n";
        $expectedDocument .= 'Content-Disposition: form-data; ' . join('; ', $contentDispositionParts) . "\r\n";
        $expectedDocument .= "\r\n" . $documentJson . "\r\n";
        $this->assertEquals($expectedDocument, $document);
    }

    /**
     * @test testCreateAppleNewsDocumentException
     *
     * @expectedException \Exception
     */
    public function testCreateAppleNewsDocumentException()
    {
        $documentJson = '';
        $this->invokeMethod($this->appleNewsApiClient, 'createAppleNewsDocument', [$documentJson]);
    }

    /**
     * @test testPostRequestInvalidStatusCode
     * @param string $statusCode
     * @dataProvider providerTestRequestInvalidStatusCode
     *
     * @expectedException \Exception
     */
    public function testPostRequestInvalidStatusCode($statusCode)
    {
        /* @var GuzzleClient $guzzleClient */
        $guzzleClient = $this->invokeMethod($this->appleNewsApiClient, 'getGuzzleClient', []);

        $mock = new GuzzleMock();
        $mock->addResponse(new GuzzleResponse(
            $statusCode,
            ['Content-Type' => 'application/json'],
            GuzzleStream::factory(\json_encode([
                'errors' => [['code' => 'INVALID_TYPE', 'value' => 'not_valid']],
            ]))
        ));
        $mock->addResponse(new GuzzleResponse($statusCode));

        $guzzleClient->getEmitter()->attach($mock);
        $this->appleNewsApiClient->post('/channels/{channel_id}/articles', ['channel_id' => 11]);
    }

    /**
     * @test testNotificationRequestInvalidStatusCode
     * @param string $statusCode
     * @dataProvider providerTestRequestInvalidStatusCode
     *
     * @expectedException \Exception
     */
    public function testNotificationRequestInvalidStatusCode($statusCode)
    {
        /* @var GuzzleClient $guzzleClient */
        $guzzleClient = $this->invokeMethod($this->appleNewsApiClient, 'getGuzzleClient', []);

        $mock = new GuzzleMock();
        $mock->addResponse(new GuzzleResponse(
            $statusCode,
            ['Content-Type' => 'application/json'],
            GuzzleStream::factory(\json_encode([
                'errors' => [['code' => 'INVALID_TYPE', 'value'=> 'not_valid']],
            ]))
        ));
        $mock->addResponse(new GuzzleResponse($statusCode));

        $guzzleClient->getEmitter()->attach($mock);
        $this->appleNewsApiClient->notification(123, [
            'alertBody' => 'This is an example alert.',
            'countries' => ['US', 'GB'] // Optional
        ]);
    }

    /**
     * @test testGetRequestInvalidStatusCode
     * @param string $statusCode
     * @dataProvider providerTestRequestInvalidStatusCode
     *
     * @expectedException \Exception
     */
    public function testGetRequestInvalidStatusCode($statusCode)
    {
        /* @var GuzzleClient $guzzleClient */
        $guzzleClient = $this->invokeMethod($this->appleNewsApiClient, 'getGuzzleClient', []);

        $mock = new GuzzleMock();
        $mock->addResponse(new GuzzleResponse(
            $statusCode,
            ['Content-Type' => 'application/json'],
            GuzzleStream::factory(\json_encode([
                'errors' => [['code' => 'INVALID_TYPE', 'value' => 'not_valid']],
            ]))
        ));
        $mock->addResponse(new GuzzleResponse($statusCode));

        $guzzleClient->getEmitter()->attach($mock);
        $this->appleNewsApiClient->get('/channels/{channel_id}/articles', ['channel_id' => 11]);
    }

    /**
     * @test testPutRequestInvalidStatusCode
     * @param string $statusCode
     * @dataProvider providerTestRequestInvalidStatusCode
     *
     * @expectedException \Exception
     */
    public function testPutRequestInvalidStatusCode($statusCode)
    {
        /* @var GuzzleClient $guzzleClient */
        $guzzleClient = $this->invokeMethod($this->appleNewsApiClient, 'getGuzzleClient', []);

        $mock = new GuzzleMock();
        $mock->addResponse(new GuzzleResponse(
            $statusCode,
            ['Content-Type' => 'application/json'],
            GuzzleStream::factory(\json_encode([
                'errors' => [["code" => "INVALID_TYPE", "value" => 'not_valid']],
            ]))
        ));

        $guzzleClient->getEmitter()->attach($mock);
        $this->appleNewsApiClient->put('/channels/{channel_id}/articles', ['channel_id' => 11]);
    }

    /**
     * @test testDeleteRequestInvalidStatusCode
     * @param string $statusCode
     * @dataProvider providerTestRequestInvalidStatusCode
     *
     * @expectedException \Exception
     */
    public function testDeleteRequestInvalidStatusCode($statusCode)
    {
        /* @var GuzzleClient $guzzleClient */
        $guzzleClient = $this->invokeMethod($this->appleNewsApiClient, 'getGuzzleClient', []);

        $mock = new GuzzleMock();
        $mock->addResponse(new GuzzleResponse(
            $statusCode,
            ['Content-Type' => 'application/json'],
            GuzzleStream::factory(\json_encode([
                'errors' => [["code" => "INVALID_TYPE", "value" => 'not_valid']],
            ]))
        ));
        $mock->addResponse(new GuzzleResponse($statusCode));

        $guzzleClient->getEmitter()->attach($mock);
        $this->appleNewsApiClient->delete('/channels/{channel_id}/articles', ['channel_id' => 11]);
    }

    /**
     * providerTestGenerateEndPoint
     *
     * @return array
     */
    public function providerTestGenerateEndPoint()
    {
        return [
            ['/channels/{channel_id}/articles', ['channel_id' => 11], '/channels/11/articles'],
            ['/articles/{article_id}', ['article_id' => 11], '/articles/11'],
            ['/articles/{channel_id}/{article_id}', ['channel_id' => 11, 'article_id' => 12], '/articles/11/12'],
            ['/articles/{channel_id}', ['article_id' => 12], '/articles/{channel_id}'],
            ['/articles/{channel_id}', [], '/articles/{channel_id}'],
        ];
    }

    /**
     * providerTestPostRequestInvalidStatusCode
     *
     * @return array
     */
    public function providerTestRequestInvalidStatusCode()
    {
        /* status code*/
        return [
            [500],
            [501],
            [503],
            [401],
            [403],
            [404]
        ];
    }
}
