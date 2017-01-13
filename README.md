# Grav Shortcode UI Plugin

## About

The **Shortcode UI** plugin provides several useful UI elements for Grav as _shortcodes_. As such it requires the **Shortcode Core** plugin to function.

It currently provides:

* Tabs
* CSS browser wrapper
* Callout for images with hover-tooltips
* Dual image comparison with drag handle
* Variety of animated text effects

## Installation

Typically a plugin should be installed via [GPM](http://learn.getgrav.org/advanced/grav-gpm) (Grav Package Manager):

```
$ bin/gpm install shortcode-ui
```

Alternatively it can be installed via the [Admin Plugin](http://learn.getgrav.org/admin-panel/plugins)

## Configuration Defaults

The **Shortcode Core** plugin only has a few options to configure.  The default values are:

```yaml
enabled: true
active: true
enabled_admin: true
load_fontawesome: false
```

* `enabled: true|false` toggles if the shortcodes plugin is turned on or off
* `active: true|false` toggles if shortcodes will be enabled site-wide or not
* `active_admin: true|false` toggles if shortcodes will be processed in the admin plugin
* `load_fontawesome: true|false` toggles if the fontawesome icon library should be loaded or not

## Configuration Modifications

The best approach to make modifications to the core plugin settings is to copy the `shortcode-core.yaml` file from the plugin into your `user/config/plugins/` folder (create it if it doesn't exist).  You can modify the settings there.

> NOTE: If you have the admin plugin installed, you can make modifications to the settings via the **Plugins** page and it will create that overridden file automatically.

## Per-Page Configuration

Sometimes you may want to only enable shortcodes on a _page-by-page_ basis.  To accomplish this set your plugin defaults to:

```yaml
enabled: true
active: false
```

This will ensure the plugin is loaded, but not **active**, then on the page you wish to process shortcodes on simply add this to the page header:

```yaml
shortcode-core:
    active: true
```

This will ensure the shortcodes are processed on this page only.

## Available Shortcodes

The core plugin contains a few simple shortcodes that can be used as basic examples:

#### Underline

Underline a section of text

```
This is some [u]bb style underline[/u] and not much else
```

#### Font Size

Set the size of some text to a specific pixel size

```
This is [size=30]bigger text[/size]
```

#### Left Align

Left align the text between this shortcode

```
[left]This text is left aligned[/left]
```

#### Center Align

Center a selection of text between this shortcode

```
[center]This text is centered[/center]
```

#### Right Align

Right align the text between this shortcode

```
[right]This text is right aligned[/right]
```

#### Raw

Do not process the shortcodes between these raw shortcode tags

```
[raw]This is some [u]bb style underline[/u] and not much else[/raw]
```

#### Safe-Email

Encode an email address so that it's not so easily 'scrapable' by nefarious scripts.  This one has a couple of options: `autolink` toggle to turn the email into a link, and an `icon` option that lets you pick a font-awesome icon to prefix the email.  Both settings are optional.

```
Safe-Email Address: [safe-email autolink="true" icon="envelope-o"]user@domain.com[/safe-email] 
```

## Developing Shortcode Plugins

The **Shortcode Core** plugin is developed on the back of the [Thunderer Advanced Shortcode Engine](https://github.com/thunderer/Shortcode) and as such loads the libraries and classes required to build 3rd party shortcode plugins.  Also we introduce a new event called `onShortcodeHandlers()` that allows a 3rd party plugin to create and add their own custom handlers.  These are then all processed by the core plugin in one shot.

I think examples are the best way to show functionality.  Let's take the `safe-email` shortcode that is included in the core, and use it to document how you could create a standalone plugin with this functionality.  If you have not already done so, I suggest reading the [Grav Plugin Tutorial](http://learn.getgrav.org/plugins/plugin-tutorial) first to gain a full understanding of what you need to develop a Grav plugin: 

```
<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;


class ShortcodeSafeEmailPlugin extends Plugin
{
    protected $handlers;
    protected $assets;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
        ];
    }
    
    /**
     * Shortcode Event 
     *
     * @param Event $e
     */
    public function onShortcodeHandlers(Event $e)
    {
        // Set handlers and assets from event
        $this->handlers = $e['handlers'];
        $this->assets = $e['assets'];

        $this->handlers->add('safe-email', function(ShortcodeInterface $shortcode) {
            // Load assets if required
            if ($this->config->get('plugins.shortcode-safe-eamil.load_fontawesome', false)) {
                $this->assets->add('css', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
            }
    
            // Get shortcode content and parameters
            $str = $shortcode->getContent();
            $icon = $shortcode->getParameter('icon', false);
            $autolink = $shortcode->getParameter('autolink', false);
    
            // Encode email
            $email = '';
            $str_len = strlen($str);
            for ($i = 0; $i < $str_len; $i++) {
                $email .= "&#" . ord($str[$i]). ";";
            }
    
            // Handle autolinking
            if ($autolink) {
                $output = '<a href="mailto:'.$email.'">'.$email.'</a>';
            } else {
                $output = $email;
            }
    
            // Handle icon option
            if ($icon) {
                $output = '<i class="fa fa-'.$icon.'"></i> ' . $output;
            }
    
            return $output;
        });
    }
}
```
