<?php
namespace App\Components;

use MatthiasMullie\Minify\JS;

class View implements ViewInterface
{
    protected $templateVars;
    protected $templates = array();


    public function render()
    {
        $this->cacheWarmup();

        $templateContent = '';
        foreach ($this->templates as $template) {
            if (file_exists($template)) {
                $templateContent .= file_get_contents($template);
            }
        }

        $templateContent = str_replace(array_keys($this->templateVars), $this->templateVars, $templateContent);

        echo $templateContent;
    }


    public function assign(string $key, $value)
    {
        $this->templateVars['$$$' . $key . '$$$'] = $value;
    }


    public function addTemplate(string $template)
    {
        $this->templates[] = THEMEDIR . '/' . $template;
    }


    public function cacheWarmup()
    {
        $lessFile = THEMEDIR . '/less/all.less';
        $jsFile = THEMEDIR . '/js/all.js';
        $cssFile = CACHEDIR . '/all.css';
        $jsOutput = CACHEDIR . '/all.js';

        $htmlParser = new HtmlMinify();

        if (ENV != 'dev') {
            foreach ($this->templates as &$template) {
                $cacheTemplate = str_replace(THEMEDIR, CACHEDIR, $template);
                if (!file_exists($cacheTemplate)) {
                    $minHtml = $htmlParser->minify_html(file_get_contents($template));
                    file_put_contents($cacheTemplate, $minHtml);
                }
                $template = $cacheTemplate;
            }
        }

        if (ENV == 'dev' || !file_exists($cssFile)) {
            $lessParser = new \Less_Parser(array(
                'compress' => ENV != 'dev'
            ));

            file_put_contents($cssFile, $lessParser->parseFile($lessFile)->getCss());
        }

        if (ENV == 'dev' || !file_exists($jsOutput)) {
            $jsParser = new JS($jsFile);
            $jsParser->minify($jsOutput);
        }

        $this->assign('css', '<style>' . file_get_contents($cssFile) . '</style>');
        $this->assign('js', '<script>' . file_get_contents($jsOutput) . '</script>');
    }
}