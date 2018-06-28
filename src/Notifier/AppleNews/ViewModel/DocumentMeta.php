<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Metadata.html#//apple_ref/doc/uid/TP40015408-CH3-SW1
 */
class DocumentMeta extends Base
{
    /** @var string[] */
    public $authors = [];

    /** @var string */
    public $canonicalURL;

    /** @var \stdClass */
    public $campaignData;

    /** @var CoverArtObject[] */
    public $coverArt;

    /** @var string */
    public $dateCreated;

    /** @var string */
    public $dateModified;

    /** @var string */
    public $datePublished;

    /** @var string */
    public $excerpt;

    /** @var string */
    public $generatorIdentifier = 'CFAppleNewsPlugin';

    /** @var string */
    public $generatorName = 'Crowdfusion Apple News Plugin';

    /** @var string */
    public $generatorVersion = '0.9';

    /** @var string[] */
    public $keywords;

    /** @var string */
    public $thumbnailURL;

    /** @var boolean */
    public $transparentToolbar;

    /** @var string */
    public $videoURL;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'authors' => 'string[]',
            'canonicalURL' => 'string',
            'campaignData' => 'stdClass',
            'coverArt' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\CoverArtObject[]',
            'dateCreated' => 'string',
            'dateModified' => 'string',
            'datePublished' => 'string',
            'excerpt' => 'string',
            'generatorIdentifier' => 'string',
            'generatorName' => 'string',
            'generatorVersion' => 'string',
            'keywords' => 'string[]',
            'thumbnailURL' => 'string',
            'transparentToolbar' => 'boolean',
            'videoURL' => 'string'
        ));
    }
}
