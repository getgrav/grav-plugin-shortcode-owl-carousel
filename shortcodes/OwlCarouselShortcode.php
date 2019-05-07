<?php

namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class OwlCarouselShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('owl-carousel', function(ShortcodeInterface $sc) {

            $id = 'owl-' . $this->shortcode->getId($sc);

            // Add assets
            $this->shortcode->addAssets('js', ['jquery', 101]);
            $this->shortcode->addAssets('js', 'plugin://shortcode-owl-carousel/js/owl.carousel.min.js');
            $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/owl.carousel.min.css');
            $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/owl.theme.default.min.css');
            $this->shortcode->addAssets('inlinejs', '$(document).ready(function(){ $("#' . $id . '").owlCarousel(' . json_encode($sc->getParameters(), JSON_NUMERIC_CHECK) . '); }); ');

            // load animate.css if required
            if ($sc->getParameter('animate') == 'true') {
                $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/animate.css');
            }
            // load built-in-css
            if ($this->config->get('plugins.shortcode-owl-carousel.built_in_css', false)) {
                $this->shortcode->addAssets('css', 'plugin://shortcode-owl-carousel/css/shortcode.owl.carousel.css');
            }

            $output = $this->twig->processTemplate('partials/owl-carousel.html.twig', [
                'owl_id' => $id,
                'owl_items' => $sc->getContent()
            ]);

            return $output;
        });

    }
}