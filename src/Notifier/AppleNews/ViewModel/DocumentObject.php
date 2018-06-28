<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BaseComponent;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DocumentStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Properties.html#//apple_ref/doc/uid/TP40015408-CH2-SW1
 */
class DocumentObject extends Base
{
    /** @var string */
    public $version = "1.4";

    /** @var string */
    public $identifier;

    /** @var string */
    public $language = 'en';

    /** @var string */
    public $title;

    /** @var LayoutObject */
    public $layout;

    /** @var BaseComponent[] */
    public $components = [];

    /** @var AdvertisingSettings */
    public $advertisingSettings;

    /** @var string */
    public $subtitle;

    /** @var DocumentMeta */
    public $metadata;

    /** @var DocumentStyle */
    public $documentStyle;

    /** @var TextStyle[] */
    public $textStyles = [];

    /** @var ComponentLayoutObject[] */
    public $componentLayouts = [];

    /** @var ComponentStyle[] */
    public $componentStyles = [];

    /** @var ComponentTextStyle[] */
    public $componentTextStyles = [];

    public function addComponent(BaseComponent $component)
    {
        $this->components[] = $component;
    }

    /**
     * @param $key
     * @return null|ComponentTextStyle
     */
    public function getComponentTextStyle($key)
    {
        return (isset($this->componentTextStyles[$key]) ? $this->componentTextStyles[$key] : null);
    }

    /**
     * @return Style\ComponentTextStyle[]
     */
    public function getComponentTextStyles()
    {
        return $this->componentTextStyles;
    }

    /**
     * @param string $name
     * @param ComponentTextStyle $componentTextStyle
     * @return $this
     */
    public function addComponentTextStyle($name, ComponentTextStyle $componentTextStyle)
    {
        $this->componentTextStyles[(string)$name] = $componentTextStyle;
        return $this;
    }

    /**
     * @param $key
     * @return null|TextStyle
     */
    public function getInlineTextStyle($key)
    {
        return ((isset($this->textStyles[$key]) ? $this->textStyles[$key] : null));
    }

    /**
     * @return Style\TextStyle[]
     */
    public function getInlineTextStyles()
    {
        return $this->textStyles;
    }

    /**
     * @param string $name
     * @param TextStyle $textStyle
     * @return $this
     */
    public function addInlineTextStyles($name, TextStyle $textStyle)
    {
        $this->textStyles[(string)$name] = $textStyle;
        return $this;
    }

    /**
     * @param $key
     * @return null|ComponentStyle
     */
    public function getComponentStyle($key)
    {
        return ((isset($this->componentStyles[$key]) ? $this->componentStyles[$key] : null));
    }

    /**
     * @return Style\ComponentStyle[]
     */
    public function getComponentStyles()
    {
        return $this->componentStyles;
    }

    /**
     * @param $name
     * @param ComponentStyle $componentStyle
     * @return $this
     */
    public function addComponentStyle($name, ComponentStyle $componentStyle)
    {
        $this->componentStyles[(string)$name] = $componentStyle;
        return $this;
    }

    /**
     * @param $key
     * @return null|ComponentLayoutObject
     */
    public function getComponentLayout($key)
    {
        return ((isset($this->componentLayouts[$key]) ? $this->componentLayouts[$key] : null));
    }

    /**
     * @return ComponentLayoutObject[]
     */
    public function getComponentLayouts()
    {
        return $this->componentLayouts;
    }

    /**
     * @param string $name
     * @param ComponentLayoutObject $componentLayout
     * @return $this
     */

    public function addComponentLayout($name, ComponentLayoutObject $componentLayout)
    {
        $this->componentLayouts[(string)$name] = $componentLayout;
        return $this;
    }

    /**
     * Validates Apple News Document
     *
     * @throws \Exception
     */
    public function validate()
    {
        // Validate all required properties are set and or of the correct type
        $this->validateProperties();

        // Hold every unique referenced global setting
        $layouts = [];
        $styles = [];
        $textStyles = [];
        $componentTextStyles = [];

        foreach ($this->components as $component) {
            $this->validateGlobalSettings($component, $layouts, $styles, $textStyles, $componentTextStyles);
        }

        foreach ($layouts as $layout) {
            Assertion::keyExists(
                $this->componentLayouts,
                $layout,
                sprintf('Global component layout [%s] is not defined.', $layout)
            );
        }

        foreach ($styles as $style) {
            Assertion::keyExists(
                $this->componentStyles,
                $style,
                sprintf('Global component style [%s] is not defined.', $style)
            );
        }

        foreach ($textStyles as $textStyle) {
            Assertion::keyExists(
                $this->textStyles,
                $textStyle,
                sprintf('Global text style [%s] is not defined.', $textStyle)
            );
        }

        foreach ($componentTextStyles as $componentTextStyle) {
            Assertion::keyExists(
                $this->componentTextStyles,
                $componentTextStyle,
                sprintf('Global component text style [%s] is not defined.', $componentTextStyle)
            );
        }
    }

    /**
     * @param $component BaseComponent
     * @param $layouts [] Array of all referenced layouts
     * @param $styles [] Array of all referenced styles
     * @param $textStyles [] Array of all referenced text styles
     * @param $componentTextStyles [] Array of all referenced component text styles
     */
    protected function validateGlobalSettings(BaseComponent $component, array &$layouts, array &$styles, array &$textStyles, array &$componentTextStyles)
    {
        if (isset($component->components) && count($component->components) > 0) {
            // find nested component styles
            foreach ($component->components as $component) {
                $this->validateGlobalSettings($component, $layouts, $styles, $textStyles, $componentTextStyles);
            }
        }

        if (isset($component->layout) && is_string($component->layout)) {
            $layouts[] = $component->layout;
        }

        if (isset($component->style) && is_string($component->style)) {
            $styles[] = $component->style;
        }

        if (isset($component->textStyle) && is_string($component->textStyle)) {
            $componentTextStyles[] = $component->textStyle;
        }

        if (isset($component->inlineTextStyles) && is_array($component->inlineTextStyles)) {
            foreach ($component->inlineTextStyles as $inlineTextStyle) {
                if (isset($inlineTextStyle->textStyle) && is_string($inlineTextStyle->textStyle)) {
                    $textStyles[] = $inlineTextStyle->textStyle;
                }
            }
        }
    }

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'version',
            'identifier',
            'title',
            'language',
            'layout',
            'components'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'version' => 'string',
            'identifier' => 'string',
            'language' => 'string',
            'title' => 'string',
            'layout' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\LayoutObject',
            'components' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BaseComponent[]',
            'advertisingSettings' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AdvertisingSettings',
            'subtitle' => 'string',
            'metadata' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\DocumentMeta',
            'documentStyle' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DocumentStyle',
            'textStyles' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle[]',
            'componentLayouts' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject[]',
            'componentStyles' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle[]',
            'componentTextStyles' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle[]'
        ));
    }
}
