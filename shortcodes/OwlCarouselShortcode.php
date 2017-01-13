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

            $hash = $this->shortcode->getId($sc);

            $output = $this->twig->processTemplate('partials/owl-carousel.html.twig', [
                'hash' => $hash,
//                'active' => $sc->getParameter('active', 0),
//                'position' => $sc->getParameter('position', 'top-left'),
//                'theme' => $sc->getParameter('theme', $this->config->get('plugins.shortcode-ui.theme.tabs', 'default')),
                'owl_items' => $this->shortcode->getStates($hash),
            ]);

            return $output;
        });

        $this->shortcode->getHandlers()->add('owl-item', function(ShortcodeInterface $sc) {
            // Add tab to tab state using parent tabs id
            $hash = $this->shortcode->getId($sc->getParent());
            $this->shortcode->setStates($hash, $sc);
            return;
        });
    }
}