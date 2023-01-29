<?php

namespace app\site\control;

use app\site\logging\Log;

class DefaultPageControl {

    private array $stylesheets = [];
    private array $script_links = [];

    private array $scripts = [];

    function __construct(protected array   $url_path = [],
                         protected ?string $page_template = null,
                         protected ?string $title = null,
                         protected ?string $body_contents = null) {
        Log::debug(__METHOD__, $this->url_path);
    }

    public function getUrlPath(): array {
        return $this->url_path;
    }

    public function setUrlPath(array $url_path): void {
        $this->url_path = $url_path;
    }

    /**
     * @return string|null
     */
    public function getPageTemplate(): ?string {
        return $this->page_template;
    }

    public function setPageTemplate(?string $page_template): void {
        $this->page_template = $page_template;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getBodyContents(): ?string {
        return $this->body_contents;
    }

    public function setBodyContents(?string $body_contents): void {
        $this->body_contents = $body_contents;
    }

    public function addStylesheet($styleSheet): void {
        $this->stylesheets[] = $styleSheet;
    }

    public function addScriptLink(string $fileName): void {
        $this->script_links[] = $fileName;
    }

    public function addScript(string $script): void {
        $this->scripts[] = $script;
    }

    public function renderPage(): void {
        require $this->getTemplate();
    }

    private function getTemplate(): string {
        if (empty($this->page_template)) $this->page_template = self::getDefaultPageTemplate();
        return $this->page_template;
    }

    /**
     * Get the default page template.
     *
     * @return string absolute filepath to a php template containing boilerplate html-code.
     * @see ../../templates/pages/a_page.php
     */
    public static function getDefaultPageTemplate(): string {
        return dirname(__FILE__, 3)
            . DIRECTORY_SEPARATOR . "templates"
            . DIRECTORY_SEPARATOR . "pages"
            . DIRECTORY_SEPARATOR . "a_page.php";
    }

    protected function renderTitle() {
        echo $this->title;
    }

    protected function renderStylesheets() {
        foreach (array_unique($this->stylesheets) as $value) {
            echo '<link rel="stylesheet" href="' . $value . '">';
        }
    }

    protected function renderScriptLinks() {
        //TODO defer and async
        foreach (array_unique($this->script_links) as $value) {
            echo '<script type="text/javascript" src="' . $value . '"></script>';
        }
    }

    protected function renderCanonicalURI() {
        if (isset($this->canonicalURI)) {
            echo '<link rel="canonical" href="' . $this->canonicalURI . '">';
        } else {
            echo '';
        }
    }

    protected function renderContent() {
        if (isset($this->body_content)) {
            if (file_exists($this->body_content)) {
                //Log::log()->info('Render content: ' . $this->body_content);
                require $this->body_content;
            } else {
                echo "else";
                //Log::log()->error('content file "' . $this->body_content . '" does not exist');
            }
        }
    }

    protected function renderScripts() {
        foreach (array_unique($this->scripts) as $script) {
            echo '<script>';
            require_once $script;
            echo '</script>';
        }
    }
}