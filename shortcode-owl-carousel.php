<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;


class ShortcodeOwlCarouselPlugin extends Plugin
{
    protected $handlers;
    protected $assets;

    protected $child_states;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
//            'onTwigExtensions' => ['onTwigExtensions', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
        ];
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

//    public function onTwigExtensions()
//    {
//        require_once(__DIR__ . '/twig/ShortcodeUITwigExtension.php');
//        $this->grav['twig']->twig->addExtension(new ShortcodeUiTwigExtension());
//    }

    /**
     * Initialize configuration
     *
     * @param Event $e
     */
    public function onShortcodeHandlers(Event $e)
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__.'/shortcodes');
    }

}
