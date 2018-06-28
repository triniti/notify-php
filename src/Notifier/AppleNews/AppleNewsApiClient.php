<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

//fixme: convert to guzzle 6

class AppleNewsApiClient
{
    /* @var GuzzleClient */
    protected $guzzleClient;

    /* @var LoggerInterface */
    protected $logger;

    /* @var string */
    protected $apiUrl;

    /* @var string */
    protected $apiKey;

    /* @var string */
    protected $apiSecret;

    /* @var string */
    protected $channelId;

    /* @var string */
    protected $boundary;

    /** @var (datetime) ISO 8601 datetime. */
    protected $dateTime;

    /** @var array valid mime types for resources */
    protected $validMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/octet-stream'
    ];

    /**
     * Apple News Api client constructor
     *
     * @param LoggerInterface $logger
     * @param string $apiUrl
     * @param string $apiKey
     * @param string $apiSecret
     *
     */
    public function __construct(LoggerInterface $logger, $apiUrl, $apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->apiSecret = $apiSecret;
        $this->logger = $logger;
        $this->boundary = md5(uniqid() . microtime());
    }

    /**
     * Performs a get request to Apple News Api
     *
     * @param String $endPoint
     * @param array $resourceId
     *
     * @throws \Exception
     * @return mixed
     */
    public function get($endPoint, array $resourceId = [])
    {
        $returnJson = null;
        $endPoint = $this->generateEndPoint($endPoint, $resourceId);
        $headers = ['Authorization' => $this->authorizationHeader($endPoint, 'GET')];

        try {
            $response = $this->getGuzzleClient()->get($endPoint, ['headers' => $headers, 'timeout' => 15]);
            $returnJson = json_decode($response->getBody());
        } catch (BadResponseException $errorResponse) {
            $this->parseResponseErrors($errorResponse->getResponse());
        }

        return $returnJson;
    }

    /**
     * Generate HTTP request URL.
     * @param String $endPoint
     * @param array $pathId
     *
     * @return string URL to create request.
     */
    protected function generateEndPoint($endPoint, array $pathId)
    {
        $params = [];
        // Take arguments and pass them to the path by replacing {argument} tokens.
        foreach ($pathId as $argument => $value) {
            $params["{{$argument}}"] = $value;
        }
        $endPoint = str_replace(array_keys($params), array_values($params), $endPoint);
        return $endPoint;
    }

    /**
     * Create authorization header
     *
     * @param string $string used by Post request for canonical
     * @param String $requestMethod
     * @param String $endPoint
     *
     * @return string HMAC cryptographic hash
     */
    final protected function authorizationHeader($endPoint, $requestMethod, $string = '')
    {
        // ISO 8601 date and time format.
        $dateTime = gmdate(\DateTime::ISO8601);
        $canonical = strtoupper($requestMethod) . $this->apiUrl . $endPoint . strval($dateTime) . $string;
        $signature = $this->hhmac($canonical);

        return sprintf('HHMAC; key=%s; signature=%s; date=%s',
            $this->apiKey,
            $signature,
            $dateTime
        );
    }

    /**
     * Generate HMAC cryptographic hash.
     *
     * @param string $string Message to be hashed.
     *
     * @return string Authorization signature
     */
    final protected function hhmac($string)
    {
        $decoded = base64_decode($this->apiSecret);
        $hashed = hash_hmac('sha256', $string, $decoded, true);
        $encoded = base64_encode($hashed);
        $signature = rtrim($encoded, "\n");

        return strval($signature);
    }

    /**
     * @return GuzzleClient
     */
    protected function getGuzzleClient()
    {
        if (null === $this->guzzleClient) {
            $this->guzzleClient = new GuzzleClient(['base_url' => $this->apiUrl]);
        }

        return $this->guzzleClient;
    }

    /**
     * Processes HTTP error response.
     * @param ResponseInterface $errorResponse
     *
     * @throws \Exception
     * @return null
     */
    protected function parseResponseErrors($errorResponse)
    {
        $errorJson = json_decode($errorResponse->getBody()->getContents());
        $errorMsg = '';

        foreach ($errorJson->errors as $error) {
            $errorMsg .= 'code: [' . $error->code . ']';

            if (isset($error->keyPath)) {
                $errorMsg .= '~keypath: [' . implode(',', $error->keyPath) . ']';
            }

            if (isset($error->value)) {
                $errorMsg .= '~value: [' . $error->value . ']';
            }
            $errorMsg .= '|';
        }
        $errorMsg = rtrim($errorMsg, '|');

        throw new \Exception(
            sprintf(
                'Apple News Api request for [%s] failed with response [%s].',
                $errorResponse->getEffectiveUrl(),
                $errorMsg
            )
        );
    }

    /**
     * Performs a put request update to Apple News Api
     *
     * @param String $endPoint
     * @param array $resourceId
     * @param array $params
     *              [document - apple news document json string
     *              resourceFile - array of resource files (bundle://)
     *              metadata - metadata json string]
     *
     * @throws \Exception
     * @return mixed
     */
    public function put($endPoint, array $resourceId = [], array $params = [])
    {
        $returnJson = null;

        $returnJson = $this->post($endPoint, $resourceId, $params);

        return $returnJson;
    }

    /**
     * Performs a notification request to Apple News Api
     *
     * @param String $appleNewsArticleId
     * @param array $data
     *              [
     *                 'alertBody': 'This is an example alert.',
     *                 'countries': ['US', 'GB'] // Optional
     *              ]
     *
     * @throws \Exception
     * @return mixed
     */
    public function notification($appleNewsArticleId, array $data)
    {
        $returnJson = null;

        // Set content type and boundary token.
        $contentType = 'application/json';

        $endPoint = "/articles/$appleNewsArticleId/notifications";
        $jsonBody = json_encode(['data' => $data ]);

        // Create post request
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => '',
            'Content-Type' => $contentType,
            'Content-Length' => strlen($jsonBody),
            'Authorization' => $this->authorizationHeader($endPoint, 'POST', $contentType . $jsonBody)
        ];


        try {
            $response = $this->getGuzzleClient()->post(
                $endPoint,
                [
                    'headers' => $headers,
                    'body' => $jsonBody
                ]
            );
            $returnJson = json_decode($response->getBody());
        } catch (BadResponseException $errorResponse) {
            $this->parseResponseErrors($errorResponse->getResponse());
        }

        return $returnJson;
    }

    /**
     * Performs a post request to Apple News Api
     *
     * @param String $endPoint
     * @param array $resourceId
     * @param array $data
     *              [document - apple news document json string
     *              resourceFiles - array of resource files (bundle://)
     *              metadata - metadata json string]
     *
     * @throws \Exception
     * @return mixed
     */
    public function post($endPoint, array $resourceId = [], array $data = [])
    {
        $returnJson = null;
        $mimeParts = [];

        $documentJson = isset($data['document']) ? $data['document'] : '';
        $resourceFiles = isset($data['resourceFiles']) ? $data['resourceFiles'] : [];
        $metadata = isset($data['metadata']) ? $data['metadata'] : '';

        if ('' === $documentJson) {
            throw new \Exception('Apple News document JSON cannot be empty');
        }

        $mimeParts[] = $this->createAppleNewsMetadata($metadata);
        $mimeParts[] = $this->createAppleNewsDocument($documentJson);
        $resourceParts = $this->createAppleNewsResource($resourceFiles);
        $mimeParts = array_merge($mimeParts, $resourceParts);

        // Set content type and boundary token.
        $mainContentType = sprintf('multipart/form-data; boundary=%s', $this->boundary);

        // Combine all MIME parts
        $mainContents = '';
        foreach ($mimeParts as $mimePart) {
            $mainContents .= $mimePart;
        }

        $mainContents .= '--' . $this->boundary . '--';
        $mainContents .= "\r\n";

        // String to use for Authorization signature
        $string = $mainContentType . $mainContents;

        // Create post request
        $endPoint = $this->generateEndPoint($endPoint, $resourceId);
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => '',
            'Content-Type' => $mainContentType,
            'Content-Length' => strlen($mainContents),
            'Authorization' => $this->authorizationHeader($endPoint, 'POST', $string)
        ];

        try {
            $response = $this->getGuzzleClient()->post(
                $endPoint,
                [
                    'headers' => $headers,
                    'body' => $mainContents
                ]
            );

            $returnJson = json_decode($response->getBody());
        } catch (BadResponseException $errorResponse) {
            $this->parseResponseErrors($errorResponse->getResponse());
        }

        return $returnJson;
    }

    /**
     * Generate Apple News metadata MIME part
     *
     * @param string $metadataJson Apple News document JSON
     *
     * @throws \Exception
     * @return string MIME chunk
     */
    protected function createAppleNewsMetadata($metadataJson)
    {
        if ('' === $metadataJson) {
            return;
        }

        $contentDisposition = ['name' => 'metadata'];

        return ($this->createMimePart($contentDisposition, 'application/json', $metadataJson));
    }

    /**
     * Generate individual MIME parts
     *
     * @param array $contentDisposition array with content disposition data [filename, name, size]
     * @param string $mimetype Multipart MIME type.
     * @param string $contents Contents of the MIME part
     *
     * @return string MIME chunk
     *
     */
    protected function createMimePart(Array $contentDisposition, $mimetype = null, $contents = null)
    {
        $multipart = '';
        $contentDispositionParts = [];

        foreach ($contentDisposition as $name => $value) {
            $contentDispositionParts[] = $name . '=' . $value;
        }

        // Generate MIME part chunk.
        $multipart .= '--' . $this->boundary . "\r\n";
        $multipart .= 'Content-Type: ' . $mimetype . "\r\n";
        $multipart .= 'Content-Disposition: form-data; ' . join('; ', $contentDispositionParts) . "\r\n";
        $multipart .= "\r\n" . $contents . "\r\n";

        return $multipart;
    }

    /**
     * Generate Apple News Document MIME part
     *
     * @param array $documentJson Apple News document JSON
     *
     * @throws \Exception
     * @return string MIME chunk
     */
    protected function createAppleNewsDocument($documentJson)
    {
        if ('' === $documentJson) {
            throw new \Exception('Apple News document JSON cannot be empty');
        }

        $contentDisposition = [
            'name' => 'article',
            'filename' => 'article.json',
            'size' => strlen($documentJson)
        ];

        return ($this->createMimePart($contentDisposition, 'application/json', $documentJson));
    }

    /**
     * Generate Apple News resource MIME part
     *
     * @param array $resources Resource array of bundle:// files
     *
     * @throws \Exception
     * @return array MIME chunk
     */
    protected function createAppleNewsResource(array $resources)
    {
        $resourceParts = [];

        if (empty($resources)) {
            return [];
        }
        // Process each resource file and create mime part
        foreach ($resources as $filename => $filePath) {
            $filePathInfo = pathinfo($filePath);
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($fileInfo, $filePath);

            if (!in_array($mimetype, $this->validMimes)) {
                throw new \Exception('Invalid mime type for resource');
            }

            $contents = file_get_contents($filePath);
            $contentDisposition = [
                'filename' => $filename,
                'name' => 'a_' . str_replace(' ', '_', $filePathInfo['filename']),
                'size' => strlen($contents),
            ];
            $resourceParts[] = $this->createMimePart($contentDisposition, $mimetype, $contents);
        }

        return $resourceParts;
    }

    /**
     * Performs a delete request to Apple News Api
     *
     * @param String $endPoint
     * @param array $resourceId
     *
     * @throws \Exception
     * @return boolean
     */
    public function delete($endPoint, array $resourceId = [])
    {
        $endPoint = $this->generateEndPoint($endPoint, $resourceId);
        $headers = ['Authorization' => $this->authorizationHeader($endPoint, 'DELETE')];

        try {
            $response = $this->getGuzzleClient()->delete($endPoint, ['headers' => $headers, 'timeout' => 15]);
        } catch (BadResponseException $errorResponse) {
            $this->parseResponseErrors($errorResponse->getResponse());
        }

        return true;
    }
}
