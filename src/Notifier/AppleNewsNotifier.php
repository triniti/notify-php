<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier;

use Gdbots\Schemas\Iam\Mixin\App\App;
use Gdbots\Schemas\Pbjx\Enum\Code;
use Triniti\Notify\Notifier;
use Triniti\Schemas\Canvas\Mixin\Block\Block;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\NotifierResult;
use Triniti\Schemas\Notify\NotifierResultV1;

class AppleNewsNotifier implements Notifier
{
    /**
     * {@inheritdoc}
     */
    public function send(Notification $notification, App $app, ?HasNotifications $content = null): NotifierResult
    {
        // @see https://developer.apple.com/documentation/apple_news/creating_and_publishing_an_article_main_steps
        // create document object
        // set default properties for document object
        // handle layout
        // set primary media (video, gallery, image). This is the header media of document
        // metadata
        // add content (handle blocks)
        $components = $this->getComponents($notification, $app, $content);
        // position? style?
        // json_encode document object
        // publish/post/send document to apple news
        // create result from apple news response
        return NotifierResultV1::create()
            ->set('ok', false)
            ->set('code', Code::UNIMPLEMENTED)
            ->set('error_name', 'AppleNewsNotifierNotImplemented');
    }

    /**
     * @param Notification $notification
     * @param App $app
     * @param null|HasNotifications $content
     * @return array
     */
    protected function getComponents(Notification $notification, App $app, ?HasNotifications $content = null): array {
        $data = [];

        /** @var Block $block */
        foreach ($content->get('blocks', []) as $block) {
            $method = 'transform' . ucfirst($block::schema()->getHandlerMethodName(false));
            if (is_callable([$this, $method])) {
                $componentData = $this->$method($notification, $app, $content, $block);
                array_push($data, json_encode($componentData));
            } else {
                // fallback method? skip?
            }
        }

        return $data;
    }

    /**
     * @param Notification $notification
     * @param App $app
     * @param null|HasNotifications $content
     * @param Block $block
     * @return array
     */
    protected function transformYoutubeVideoBlock(
        Notification $notification,
        App $app,
        ?HasNotifications $content = null,
        Block $block
    ): array {
        return [];
        // convert block to apple news format
        // and either apply to data or return array
    }
}
