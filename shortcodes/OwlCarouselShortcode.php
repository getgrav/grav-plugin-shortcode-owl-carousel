<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;


class OwlCarouselShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('owl-carousel', function(ShortcodeInterface $sc) {

            // Add assets
            $this->shortcode->addAssets('js', ['jquery', 101]);
            $this->shortcode->addAssets('js', 'plugin://shortcode-owl-carousel/js/owl.carousel.min.js');
            $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/owl.carousel.min.css');
            $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/owl.theme.default.min.css');
            // load animate.css if required
            if ($sc->getParameter('animate') == 'true') {
                $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/animate.css');
            }
            // load built-in-css
            if ($this->config->get('plugins.shortcode-owl-carousel.built_in_css', false)) {
                $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/shortcode.owl.carousel.css');
            }

            $hash = $this->shortcode->getId($sc);

            $output = $this->twig->processTemplate('partials/owl-carousel.html.twig', [
                'hash' => $hash,
                'params' => $sc->getParameters(),
                'owl_items' => $sc->getContent(),
            ]);

            return $output;
        });

    }
}