<?php

namespace Modules\Parse;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PageSpeedManager
{
    /**
     * Store HTML content.
     */
    private $html = '';

    /**
     * Store class attributes.
     */
    private $class = [];

    /**
     * Store style attributes.
     */
    private $style = [];

    /**
     * Store inline styles.
     */
    private $inline = [];

    /**
     * Store global variable mapping for consistent minification across script tags
     */
    private $globalVariableMap = [];

    /**
     * Store global function mapping for consistent minification across script tags
     */
    private $globalFunctionMap = [];

    /**
     * Is Laravel Page Speed enabled
     *
     * @var bool
     */
    protected static $isEnabled;

    /**
     * The void elements.
     *
     * @var array
     */
    protected static $voidElements = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
        'link', 'meta', 'param', 'source', 'track', 'wbr',
    ];

    /**
     * Should Process
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     * @return bool
     */
    public function shouldProcessPageSpeed($request, $response)
    {
        if (!$this->isEnable()) {
            return false;
        }

        if ($response instanceof BinaryFileResponse) {
            return false;
        }

        if ($response instanceof StreamedResponse) {
            return false;
        }

        foreach (config('page-speed.skip', []) as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Parse content and apply Page Speed optimizations
     *
     * @param string $content
     * @return string
     */
    public function parseContent($content)
    {
        $options = config('page-speed.middleware', []);

        if ($options['inline_css']['enable']) {
            $content = $this->implementInlineCss($content);
        }

        if ($options['elide_attributes']['enable']) {
            $content = $this->replace(
                $options['elide_attributes']['regex'],
                $content
            );
        }

        if ($options['insert_dns_prefetch']['enable']) {
            $content = $this->implementDnsPrefetch($content);
        }

        // beta, need more testing
        if ($options['remove_comments']['enable']) {
            $content = $this->replace(
                $options['remove_comments']['regex'],
                $content
            );
        }

        // beta, need more testing
        if ($options['minify_javascript']['enable']) {
            $content = $this->implementJavaScriptMinification(
                $content,
                $options['minify_javascript']
            );
        }

        if ($options['collapse_whitespace']['enable']) {
            $content = $this->replace(
                $options['collapse_whitespace']['regex'],
                $content
            );
        }

        if ($options['trim_urls']['enable']) {
            $content = $this->replaceInsideHtmlTags(
                ['a', 'img', 'link', 'script', 'iframe', 'source', 'video', 'audio'],
                $options['trim_urls']['regex'],
                '',
                $content
            );
        }

        if ($options['remove_quotes']['enable']) {
            $content = $this->replace(
                $options['remove_quotes']['regex'],
                $content
            );
        }

        if ($options['defer_javascript']['enable']) {
            $content = $this->replace(
                $options['defer_javascript']['regex'],
                $content
            );
        }

        return $content;
    }

    /**
     * Check Laravel Page Speed is enabled or not
     *
     * @return bool
     */
    protected function isEnable()
    {
        if (!is_null(static::$isEnabled)) {
            return static::$isEnabled;
        }

        static::$isEnabled = (bool) config('page-speed.enable', true);

        return static::$isEnabled;
    }

    /**
     * Replace content response.
     *
     * @param  array $replace
     * @param  string $buffer
     * @return string
     */
    protected function replace(array $replace, $buffer)
    {
        return preg_replace(array_keys($replace), array_values($replace), $buffer);
    }

    /**
     * Match all occurrences of the html tags given
     *
     * @param array  $tags   Html tags to match in the given buffer
     * @param string $buffer Middleware response buffer
     *
     * @return array $matches Html tags found in the buffer
     */
    protected function matchAllHtmlTag(array $tags, string $buffer): array
    {
        $voidTags = array_intersect($tags, static::$voidElements);
        $normalTags = array_diff($tags, $voidTags);

        return array_merge(
            $this->matchTags($voidTags, '/\<\s*(%tags)[^>]*\>/', $buffer),
            $this->matchTags($normalTags, '/\<\s*(%tags)[^>]*\>((.|\n)*?)\<\s*\/\s*(%tags)\>/', $buffer)
        );
    }

    /**
     * Match occurrences of the html tags given
     *
     * @param array  $tags   Html tags to match in the given buffer
     * @param string $pattern Regex pattern to match the html tags
     * @param string $buffer Middleware response buffer
     *
     * @return array $matches Html tags found in the buffer
     */
    protected function matchTags(array $tags, string $pattern, string $buffer): array
    {
        if (empty($tags)) {
            return [];
        }

        $normalizedPattern = str_replace('%tags', implode('|', $tags), $pattern);

        preg_match_all($normalizedPattern, $buffer, $matches);

        return $matches[0];
    }

    /**
     * Replace occurrences of regex pattern inside of given HTML tags
     *
     * @param array  $tags    Html tags to match and run regex to replace occurrences
     * @param string $regex   Regex rule to match on the given HTML tags
     * @param string $replace Content to replace
     * @param string $buffer  Middleware response buffer
     *
     * @return string $buffer Middleware response buffer
     */
    protected function replaceInsideHtmlTags(array $tags, string $regex, string $replace, string $buffer): string
    {
        foreach ($this->matchAllHtmlTag($tags, $buffer) as $tagMatched) {
            preg_match_all($regex, $tagMatched, $contentsMatched);

            $tagAfterReplace = str_replace($contentsMatched[0], $replace, $tagMatched);
            $buffer = str_replace($tagMatched, $tagAfterReplace, $buffer);
        }

        return $buffer;
    }

    /**
     * Replace occurrences of regex pattern inside of given HTML tags
     *
     * @param array  $tags    Html tags to match and run regex to replace occurrences
     * @param string $regex   Regex rule to match on the given HTML tags
     * @param string $replace Content to replace
     * @param string $buffer  Middleware response buffer
     *
     * @return string $buffer Middleware response buffer
     */
    private function implementDnsPrefetch($content)
    {
        preg_match_all(
            '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            $content,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        $dnsPrefetch = collect($matches[0])->map(function ($item) {
            $domain = $this->replace(['/https:/' => '', '/http:/' => ''], $item[0]);
            $domain = explode(
                '/',
                str_replace('//', '', $domain)
            );

            return "<link rel=\"dns-prefetch\" href=\"//{$domain[0]}\">";
        })->unique()->implode("\n");

        $replace = [
            '#<head>(.*?)#' => "<head>\n{$dnsPrefetch}"
        ];

        return $this->replace($replace, $content);
    }

    /**
     * Implement inline CSS
     *
     * @param string $content
     * @return string
     */
    private function implementInlineCss($content)
    {
        $this->html = $content;

        preg_match_all(
            '#style="(.*?)"#',
            $this->html,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        $this->class = collect($matches[1])->mapWithKeys(function ($item) {
            return [ 'page_speed_'.rand() => $item[0] ];
        })->unique();

        return $this->injectStyle()
            ->injectClass()
            ->fixHTML()
            ->html;
    }

    /**
     * Replace style attributes with class attributes
     *
     * @return $this
     */
    private function injectStyle()
    {
        collect($this->class)->each(function ($attributes, $class) {

            $this->inline[] = ".{$class}{ {$attributes} }";

            $this->style[] = [
                'class' => $class,
                'attributes' => preg_quote($attributes, '/')];
        });

        $injectStyle = implode(' ', $this->inline);

        $replace = [
            '#</head>(.*?)#' => "\n<style nonce=\"".app('csp-nonce')."\"> {$injectStyle}</style>\n</head>"
        ];

        $this->html = $this->replace($replace, $this->html);

        return $this;
    }

    /**
     * Replace style attributes with class attributes
     *
     * @return $this
     */
    private function injectClass()
    {
        collect($this->style)->each(function ($item) {
            $replace = [
                '/style="'.$item['attributes'].'"/' => "class=\"{$item['class']}\"",
            ];

            $this->html = $this->replace($replace, $this->html);
        });

        return $this;
    }

    /**
     * Fix HTML structure
     *
     * @return $this
     */
    private function fixHTML()
    {
        $newHTML = [];
        $tmp = explode('<', $this->html);

        $replaceClass = [
            '/class="(.*?)"/' => "",
        ];

        foreach ($tmp as $value) {
            preg_match_all('/class="(.*?)"/', $value, $matches);

            if (count($matches[1]) > 1) {
                $replace = [
                    '/>/' => "class=\"".implode(' ', $matches[1])."\">",
                ];

                $newHTML[] = str_replace(
                    '  ',
                    ' ',
                    $this->replace($replace, $this->replace($replaceClass, $value))
                );
            } else {
                $newHTML[] = $value;
            }
        }

        $this->html = implode('<', $newHTML);

        return $this;
    }

    /**
     * Implement JavaScript minification
     *
     * @param string $content
     * @param array $options
     * @return string
     */
    private function implementJavaScriptMinification($content, $options = [])
    {
        // Reset global mappings for fresh minification
        $this->globalVariableMap = [];
        $this->globalFunctionMap = [];

        // First pass: collect all variables and functions from all script tags
        preg_match_all('/<script(?![^>]*src=)([^>]*)>(.*?)<\/script>/is', $content, $allMatches, PREG_SET_ORDER);

        $allVariables = [];
        $allFunctions = [];

        foreach ($allMatches as $match) {
            $jsCode = $match[2];

            // Skip if script tag is empty
            if (empty(trim($jsCode))) {
                continue;
            }

            // Collect variables and functions from this script
            $scriptVars = $this->extractVariables($jsCode);
            $scriptFuncs = $this->extractFunctions($jsCode);

            $allVariables = array_merge($allVariables, $scriptVars);
            $allFunctions = array_merge($allFunctions, $scriptFuncs);
        }

        // Create global mappings for all variables and functions
        $this->createGlobalMappings($allVariables, $allFunctions, $options);

        // Second pass: apply minification to each script tag using global mappings
        foreach ($allMatches as $match) {
            $fullTag = $match[0];
            $attributes = $match[1];
            $jsCode = $match[2];

            // Skip if script tag has src attribute or is empty
            if (empty(trim($jsCode)) || strpos($attributes, 'src=') !== false) {
                continue;
            }

            // Minify the JavaScript code using global mappings
            $minifiedJs = $this->minifyJavaScript($jsCode, $options);

            // Replace the original script with minified version
            $minifiedTag = '<script' . $attributes . '>' . $minifiedJs . '</script>';
            $content = str_replace($fullTag, $minifiedTag, $content);
        }

        return $content;
    }

    /**
     * Minify JavaScript code
     *
     * @param string $jsCode
     * @param array $options
     * @return string
     */
    private function minifyJavaScript($jsCode, $options = [])
    {
        $preserveVars = $options['preserve_variables'] ?? [];
        $preserveFuncs = $options['preserve_functions'] ?? [];

        // Remove extra whitespace and newlines
        $jsCode = preg_replace('/\s+/', ' ', $jsCode);

        // Remove spaces around operators and punctuation
        $jsCode = preg_replace('/\s*([{}();,=+\-*\/&|!<>])\s*/', '$1', $jsCode);

        // Remove spaces around dots
        $jsCode = preg_replace('/\s*\.\s*/', '.', $jsCode);

        // Remove spaces around brackets
        $jsCode = preg_replace('/\s*\[\s*/', '[', $jsCode);
        $jsCode = preg_replace('/\s*\]\s*/', ']', $jsCode);

        // Minify variable names (simple approach)
        $jsCode = $this->minifyVariableNames($jsCode, $preserveVars, $preserveFuncs);

        // Remove unnecessary semicolons before closing braces
        $jsCode = preg_replace('/;(\s*})/', '$1', $jsCode);

        // Trim whitespace
        $jsCode = trim($jsCode);

        return $jsCode;
    }

    /**
     * Minify variable and function names using global mappings
     *
     * @param string $jsCode
     * @param array $preserveVars
     * @param array $preserveFuncs
     * @return string
     */
    private function minifyVariableNames($jsCode, $preserveVars = [], $preserveFuncs = [])
    {
        // Apply global variable mappings
        foreach ($this->globalVariableMap as $originalName => $minifiedName) {
            if (!in_array($originalName, $preserveVars)) {
                $jsCode = preg_replace('/\b' . preg_quote($originalName, '/') . '\b/', $minifiedName, $jsCode);
            }
        }

        // Apply global function mappings
        foreach ($this->globalFunctionMap as $originalName => $minifiedName) {
            if (!in_array($originalName, $preserveFuncs)) {
                $jsCode = preg_replace('/\b' . preg_quote($originalName, '/') . '\b/', $minifiedName, $jsCode);
            }
        }

        return $jsCode;
    }

    /**
     * Extract variables from JavaScript code
     *
     * @param string $jsCode
     * @return array
     */
    private function extractVariables($jsCode)
    {
        // Find variable declarations
        preg_match_all('/\b(?:var|let|const)\s+([a-zA-Z_$][a-zA-Z0-9_$]*)/i', $jsCode, $varMatches);

        return array_unique($varMatches[1]);
    }

    /**
     * Extract functions from JavaScript code
     *
     * @param string $jsCode
     * @return array
     */
    private function extractFunctions($jsCode)
    {
        // Find function declarations
        preg_match_all('/\bfunction\s+([a-zA-Z_$][a-zA-Z0-9_$]*)/i', $jsCode, $funcMatches);

        return array_unique($funcMatches[1]);
    }

    /**
     * Create global mappings for variables and functions
     *
     * @param array $allVariables
     * @param array $allFunctions
     * @param array $options
     * @return void
     */
    private function createGlobalMappings($allVariables, $allFunctions, $options)
    {
        $preserveVars = $options['preserve_variables'] ?? [];
        $preserveFuncs = $options['preserve_functions'] ?? [];

        // Remove duplicates and filter out preserved items
        $allVariables = array_unique($allVariables);
        $allFunctions = array_unique($allFunctions);

        $filteredVars = array_filter($allVariables, function($var) use ($preserveVars) {
            return !in_array($var, $preserveVars) && !$this->isReservedKeyword($var);
        });

        $filteredFuncs = array_filter($allFunctions, function($func) use ($preserveFuncs) {
            return !in_array($func, $preserveFuncs) && !$this->isReservedKeyword($func);
        });

        // Generate short names for all items
        $totalCount = count($filteredVars) + count($filteredFuncs);
        $shortNames = $this->generateShortNames($totalCount);
        $nameIndex = 0;

        // Create variable mappings
        foreach ($filteredVars as $varName) {
            $this->globalVariableMap[$varName] = $shortNames[$nameIndex++];
        }

        // Create function mappings
        foreach ($filteredFuncs as $funcName) {
            $this->globalFunctionMap[$funcName] = $shortNames[$nameIndex++];
        }
    }

    /**
     * Generate short variable names
     *
     * @param int $count
     * @return array
     */
    private function generateShortNames($count)
    {
        $names = [];

        // make generating names more robust using laravel cache
        if (Cache::has('page_speed_short_names')) {
            $names = Cache::get('page_speed_short_names');
        } else {
            $alphabet = 'abcdefghijklmnopqrstuvwxyz';

            // Single letter names first
            for ($i = 0; $i < 26; $i++) {
                $names[] = $alphabet[$i];
            }

            // Double letter names
            for ($i = 0; $i < 26; $i++) {
                for ($j = 0; $j < 26; $j++) {
                    $names[] = $alphabet[$i] . $alphabet[$j];
                }
            }

            // Triple letter names if needed
            for ($i = 0; $i < 26; $i++) {
                for ($j = 0; $j < 26; $j++) {
                    for ($k = 0; $k < 26; $k++) {
                        $names[] = $alphabet[$i] . $alphabet[$j] . $alphabet[$k];
                    }
                }
            }

            // Store generated names in cache for future use store forever
            Cache::put('page_speed_short_names', $names, 60 * 60 * 24 * 365); // 1 year
        }

        return array_slice($names, 0, $count);
    }

    /**
     * Check if a name is a reserved JavaScript keyword
     *
     * @param string $name
     * @return bool
     */
    private function isReservedKeyword($name)
    {
        $reservedKeywords = [
            'abstract', 'arguments', 'await', 'boolean', 'break', 'byte', 'case', 'catch',
            'char', 'class', 'const', 'continue', 'debugger', 'default', 'delete', 'do',
            'double', 'else', 'enum', 'eval', 'export', 'extends', 'false', 'final',
            'finally', 'float', 'for', 'function', 'goto', 'if', 'implements', 'import',
            'in', 'instanceof', 'int', 'interface', 'let', 'long', 'native', 'new',
            'null', 'package', 'private', 'protected', 'public', 'return', 'short',
            'static', 'super', 'switch', 'synchronized', 'this', 'throw', 'throws',
            'transient', 'true', 'try', 'typeof', 'var', 'void', 'volatile', 'while',
            'with', 'yield', 'console', 'window', 'document', 'alert', 'confirm',
            'prompt', 'setTimeout', 'setInterval', 'clearTimeout', 'clearInterval',
            'parseInt', 'parseFloat', 'isNaN', 'isFinite', 'escape', 'unescape',
            'encodeURI', 'encodeURIComponent', 'decodeURI', 'decodeURIComponent'
        ];

        return in_array(strtolower($name), $reservedKeywords);
    }
}
