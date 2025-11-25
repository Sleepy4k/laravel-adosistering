<?php

namespace App\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;
use Spatie\Csp\Value;

class BasicPolicy implements Preset
{
    /**
     * Configure csp policies for general and other policy
     *
     * @return void
     */
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::BASE, Directive::DEFAULT], Keyword::SELF)
            ->add([Directive::FORM_ACTION, Directive::MEDIA, Directive::FRAME], Keyword::SELF)
            ->add([Directive::MANIFEST, Directive::CHILD, Directive::CONNECT], Keyword::SELF)
            ->add([Directive::OBJECT, Directive::WEB_RTC], Keyword::NONE)
            ->add([Directive::FONT], [Keyword::SELF, Scheme::DATA])
            ->add([Directive::BLOCK_ALL_MIXED_CONTENT], Value::NO_VALUE);

        if (app()->environment(['production', 'staging'])) {
            $policy
                ->add([Directive::WORKER], Keyword::NONE)
                ->add([Directive::IMG], [Keyword::SELF, Scheme::DATA])
                ->add([Directive::UPGRADE_INSECURE_REQUESTS], Value::NO_VALUE)
                ->add([Directive::SCRIPT, Directive::STYLE], [Keyword::SELF, Keyword::STRICT_DYNAMIC])
                ->add([Directive::SCRIPT_ELEM], [Keyword::SELF, Keyword::STRICT_DYNAMIC])
                ->add([Directive::STYLE_ELEM], [Keyword::SELF, Keyword::STRICT_DYNAMIC])
                ->add([Directive::SCRIPT_ATTR, Directive::STYLE_ATTR], Keyword::NONE)
                ->addNonce(Directive::STYLE_ELEM)
                ->addNonce(Directive::SCRIPT_ELEM);
        } else {
            $policy
                ->add([Directive::WORKER], [Keyword::SELF, Scheme::WS, Scheme::WSS])
                ->add([Directive::IMG], ['*', Scheme::BLOB, Scheme::DATA])
                ->add([Directive::SCRIPT], ['*', Keyword::SELF, Keyword::UNSAFE_INLINE])
                ->add([Directive::STYLE], ['*', Keyword::SELF, Keyword::UNSAFE_INLINE]);
        }
    }
}
