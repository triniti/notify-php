<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Gdbots\Common\Util\ClassUtils;
use Gdbots\Common\Util\StringUtils;
use Gdbots\Ncr\Ncr;
use Gdbots\Pbj\Message;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Iam\Mixin\App\App;
use Gdbots\Schemas\Pbjx\Enum\Code;
use Triniti\AppleNews\AppleNewsApi;
use Triniti\AppleNews\ArticleDocumentMarshaler;
use Triniti\Notify\Exception\InvalidNotificationContent;
use Triniti\Notify\Exception\RequiredFieldNotSet;
use Triniti\Notify\Notifier;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Enum\SearchNotificationsSort;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\SearchNotificationsRequest\SearchNotificationsRequestV1Mixin;
use Triniti\Schemas\Notify\NotifierResult;
use Triniti\Schemas\Notify\NotifierResultV1;
use Triniti\Sys\Flags;

class AppleNewsNotifier implements Notifier
{
    /** @var Flags */
    protected $flags;

    /** @var Key */
    protected $key;

    /** @var Pbjx */
    protected $pbjx;

    /** @var ArticleDocumentMarshaler */
    protected $marshaler;

    /** @var AppleNewsApi */
    protected $api;

    /** @var Ncr */
    protected $ncr;

    /**
     * @param Flags                    $flags
     * @param Key                      $key
     * @param Pbjx                     $pbjx
     * @param ArticleDocumentMarshaler $marshaler
     * @param Ncr                      $ncr
     */
    public function __construct(Flags $flags, Key $key, Pbjx $pbjx, ArticleDocumentMarshaler $marshaler, Ncr $ncr)
    {
        $this->flags = $flags;
        $this->key = $key;
        $this->pbjx = $pbjx;
        $this->marshaler = $marshaler;
        $this->ncr = $ncr;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Notification $notification, App $app, ?HasNotifications $content = null): NotifierResult
    {
        if (null === $content) {
            return NotifierResultV1::create()
                ->set('ok', false)
                ->set('code', Code::INVALID_ARGUMENT)
                ->set('error_name', 'NullContent')
                ->set('error_message', 'Content cannot be null');
        }

        if ($this->flags->getBoolean('apple_news_notifier_disabled')) {
            return NotifierResultV1::create()
                ->set('ok', false)
                ->set('code', Code::CANCELLED)
                ->set('error_name', 'AppleNewsNotifierDisabled')
                ->set('error_message', 'Flag [apple_news_notifier_disabled] is true');
        }

        try {
            $this->createApi($notification, $app, $content);
            $operation = $notification->get('apple_news_operation');
            switch ($operation) {
                case 'create':
                    $result = $this->createArticle($notification, $app, $content);
                    break;

                case 'update':
                    $result = $this->updateArticle($notification, $app, $content);
                    break;

                case 'delete':
                    $result = $this->deleteArticle($notification, $app, $content);
                    break;

                case 'notification':
                    $result = $this->createArticleNotification($notification, $app, $content);
                    break;

                default:
                    throw new InvalidNotificationContent("AppleNews operation [{$operation}] is not supported.");
            }
        } catch (\Throwable $e) {
            $code = $e->getCode() > 0 ? $e->getCode() : Code::UNKNOWN;
            return NotifierResultV1::create()
                ->set('ok', false)
                ->set('code', $code)
                ->set('error_name', ClassUtils::getShortName($e))
                ->set('error_message', substr($e->getMessage(), 0, 2048));
        }

        $response = $result['response'] ?? [];
        $result = NotifierResultV1::fromArray($result)
            ->set('raw_response', $response ? json_encode($response) : '{}')
            ->addToMap('tags', 'apple_news_operation', $operation);

        if ($result->get('ok') && isset($response['data'], $response['data']['id'])) {
            $newsId = $response['data']['id'] ?? null;
            $shareUrl = $response['data']['shareUrl'] ?? null;
            $revision = $response['data']['revision'] ?? null;
            $revision = $revision ? StringUtils::urlsafeB64Encode($revision) : $revision;
            $result
                ->addToMap('tags', 'apple_news_id', $newsId)
                ->addToMap('tags', 'apple_news_share_url', $shareUrl)
                ->addToMap('tags', 'apple_news_revision', $revision);
        }

        return $result;
    }

    /**
     * @param Message $notification
     * @param Message $app
     * @param Message $article
     *
     * @return array
     */
    protected function createArticleNotification(Message $notification, Message $app, Message $article): array
    {
        if (!$article->has('apple_news_id')) {
            throw new RequiredFieldNotSet('Article [apple_news_id] is required');
        }

        return $this->api->createArticleNotification((string)$article->get('apple_news_id'), [
            'alertBody' => $notification->get('body', $article->get('title')),
        ]);
    }

    /**
     * @param Message $notification
     * @param Message $app
     * @param Message $article
     *
     * @return array
     */
    protected function createArticle(Message $notification, Message $app, Message $article): array
    {
        if (!$app->has('channel_id')) {
            throw new RequiredFieldNotSet('App [channel_id] is required');
        }

        $document = $this->marshaler->marshal($article);
        $metadata = $this->createArticleMetadata($article);
        return $this->api->createArticle($app->get('channel_id'), $document, $metadata);
    }

    /**
     * @param Message $notification
     * @param Message $app
     * @param Message $article
     *
     * @return array
     */
    protected function updateArticle(Message $notification, Message $app, Message $article): array
    {
        if (!$article->has('apple_news_id')) {
            throw new RequiredFieldNotSet('Article [apple_news_id] is required');
        }

        if (!$article->has('apple_news_revision')) {
            throw new RequiredFieldNotSet('Article [apple_news_revision] is required');
        }

        $document = $this->marshaler->marshal($article);
        $metadata = $this->createArticleMetadata($article);
        $metadata['revision'] = $article->get('apple_news_revision');
        $result = $this->api->updateArticle((string)$article->get('apple_news_id'), $document, $metadata);

        if ($result['ok']) {
            return $result;
        }

        $code = $result['response']['errors'][0]['code'] ?? null;
        if ('WRONG_REVISION' !== $code) {
            return $result;
        }

        $latestRevision = $this->getLatestRevision($notification);
        if (null === $latestRevision || $article->get('apple_news_revision') === $latestRevision) {
            return $result;
        }

        $metadata['revision'] = $latestRevision;
        return $this->api->updateArticle((string)$article->get('apple_news_id'), $document, $metadata);
    }

    /**
     * @param Message $notification
     * @param Message $app
     * @param Message $article
     *
     * @return array
     */
    protected function deleteArticle(Message $notification, Message $app, Message $article): array
    {
        if (!$article->has('apple_news_id')) {
            throw new RequiredFieldNotSet('Article [apple_news_id] is required');
        }

        return $this->api->deleteArticle((string)$article->get('apple_news_id'));
    }

    /**
     * @param Message $notification
     * @param Message $app
     * @param Message $article
     */
    protected function createApi(Message $notification, Message $app, Message $article): void
    {
        $this->api = new AppleNewsApi(
            $app->get('api_key'),
            Crypto::decrypt($app->get('api_secret'), $this->key)
        );
    }

    /**
     * @param Message $notification
     *
     * @return string
     */
    protected function getLatestRevision(Message $notification): ?string
    {
        $request = SearchNotificationsRequestV1Mixin::findOne()->createMessage()
            ->addToSet('types', ['apple-news-notification'])
            ->set('q', '+apple_news_operation:(update OR create)')
            ->set('send_status', NotificationSendStatus::SENT())
            ->set('app_ref', $notification->get('app_ref'))
            ->set('content_ref', $notification->get('content_ref'))
            ->set('ctx_causator_ref', $notification->generateMessageRef())
            ->set('count', 1)
            ->set('sort', SearchNotificationsSort::SENT_AT_DESC());

        $response = $this->pbjx->request($request);
        if (!$response->has('nodes')) {
            return null;
        }

        /** @var Message $result */
        $result = $response->getFromListAt('nodes', 0)->get('notifier_result');
        $revision = $result->getFromMap('tags', 'apple_news_revision');
        return $revision ? StringUtils::urlsafeB64Decode($revision) : $revision;
    }

    /**
     * @param Message $article
     *
     * @return array
     */
    protected function createArticleMetadata(Message $article): array
    {
        $sections = $this->createArticleSections($article);
        if (empty($sections)) {
            return [];
        }

        return [
            'links' => [
                'sections' => $sections,
            ],
        ];
    }

    /**
     * @param Message $article
     *
     * @return string[]
     */
    protected function createArticleSections(Message $article): array
    {
        $sections = [];
        $defaultSection = $this->flags->getString('apple_news_default_section_url');
        if ('' !== $defaultSection && $article->get('is_homepage_news')) {
            $sections[] = $defaultSection;
        }

        if (!$article->has('channel_ref')) {
            return $sections;
        }

        try {
            $channel = $this->ncr->getNode($article->get('channel_ref'));
        } catch (\Throwable $e) {
            return $sections;
        }

        if (!$channel->isInMap('tags', 'apple_news_section_url')) {
            return $sections;
        }

        $sections[] = $channel->getFromMap('tags', 'apple_news_section_url');
        return $sections;
    }
}
