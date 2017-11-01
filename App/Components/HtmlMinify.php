<?php

namespace App\Components;

/**
 * Based on https://gist.github.com/tovic/d7b310dea3b33e4732c0
 */
class HtmlMinify
{
    private $CH = '<\!--[\s\S]*?-->';


    public function __construct()
    {
        define('X', "\x1A"); // a placeholder character
    }


    public function minify_html($input)
    {
        if (!$input = trim($input)) return $input;

        // Keep important white-space(s) after self-closing HTML tag(s)
        $input = preg_replace('#(<(?:img|input)(?:\s[^<>]*?)?\s*\/?>)\s+#i', '$1' . X . '\s', $input);
        // Create chunk(s) of HTML tag(s), ignored HTML group(s), HTML comment(s) and text
        $input = preg_split('#(' . $this->CH . '|<pre(?:>|\s[^<>]*?>)[\s\S]*?<\/pre>|<code(?:>|\s[^<>]*?>)[\s\S]*?<\/code>|<script(?:>|\s[^<>]*?>)[\s\S]*?<\/script>|<style(?:>|\s[^<>]*?>)[\s\S]*?<\/style>|<textarea(?:>|\s[^<>]*?>)[\s\S]*?<\/textarea>|<[^<>]+?>)#i', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $output = "";
        foreach ($input as $v) {
            if ($v !== ' ' && trim($v) === "") continue;
            if ($v[0] === '<' && substr($v, -1) === '>') {
                if ($v[1] === '!' && strpos($v, '<!--') === 0) { // HTML comment ...
                    // Remove if not detected as IE comment(s) ...
                    if (substr($v, -12) !== '<![endif]-->') continue;
                    $output .= $v;
                } else {
                    $output .= $this->__minify_x($this->_minify_html($v));
                }
            } else {
                // Force line-break with `&#10;` or `&#xa;`
                $v = str_replace(array('&#10;', '&#xA;', '&#xa;'), X . '\n', $v);
                // Force white-space with `&#32;` or `&#x20;`
                $v = str_replace(array('&#32;', '&#x20;'), X . '\s', $v);
                // Replace multiple white-space(s) with a space
                $output .= preg_replace('#\s+#', ' ', $v);
            }
        }
        // Clean up ...
        $output = preg_replace(
            array(
                // Remove two or more white-space(s) between tag [^1]
                '#>([\n\r\t]\s*|\s{2,})<#',
                // Remove white-space(s) before tag-close [^2]
                '#\s+(<\/[^\s]+?>)#'
            ),
            array(
                // [^1]
                '><',
                // [^2]
                '$1'
            ),
            $output);
        $output = $this->__minify_v($output);

        // Remove white-space(s) after ignored tag-open and before ignored tag-close (except `<textarea>`)
        return preg_replace('#<(code|pre|script|style)(>|\s[^<>]*?>)\s*([\s\S]*?)\s*<\/\1>#i', '<$1$2$3</$1>', $output);
    }


    private function __minify_x($input)
    {
        return str_replace(array("\n", "\t", ' '), array(X . '\n', X . '\t', X . '\s'), $input);
    }


    private function __minify_v($input)
    {
        return str_replace(array(X . '\n', X . '\t', X . '\s'), array("\n", "\t", ' '), $input);
    }


    private function _minify_html($input)
    {
        return preg_replace_callback('#<\s*([^\/\s]+)\s*(?:>|(\s[^<>]+?)\s*>)#', function ($m) {
            if (isset($m[2])) {
                // Minify inline CSS declaration(s)
                if (stripos($m[2], ' style=') !== false) {
                    $m[2] = preg_replace_callback('#( style=)([\'"]?)(.*?)\2#i', function ($m) {
                        // return $m[1] . $m[2] . $this->minify_css($m[3]) . $m[2];
                        return $m[1] . $m[2] . $m[3] . $m[2];
                    }, $m[2]);
                }

                return '<' . $m[1] . preg_replace(
                        array(
                            // From `defer="defer"`, `defer='defer'`, `defer="true"`, `defer='true'`, `defer=""` and `defer=''` to `defer` [^1]
                            '#\s(checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped)(?:=([\'"]?)(?:true|\1)?\2)#i',
                            // Remove extra white-space(s) between HTML attribute(s) [^2]
                            '#\s*([^\s=]+?)(=(?:\S+|([\'"]?).*?\3)|$)#',
                            // From `<img />` to `<img/>` [^3]
                            '#\s+\/$#'
                        ),
                        array(
                            // [^1]
                            ' $1',
                            // [^2]
                            ' $1$2',
                            // [^3]
                            '/'
                        ),
                        str_replace("\n", ' ', $m[2])) . '>';
            }

            return '<' . $m[1] . '>';
        }, $input);
    }
}