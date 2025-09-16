<?php

namespace System\Template;

/**
 * Fame Framework - Template Engine
 * Custom #directive syntax
 */
class TemplateEngine
{
    protected string $viewsPath;
    protected string $cachePath;
    protected array $sections = [];
    protected ?string $currentSection = null;
    protected ?string $parentTemplate = null;

    public function __construct(string $viewsPath, string $cachePath)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->cachePath = rtrim($cachePath, '/');

        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }

    /**
     * Render a view
     */
    public function render(string $view, array $data = []): string
    {
        $compiledPath = $this->compile($view);

        extract($data, EXTR_SKIP);

        ob_start();
        include $compiledPath;
        $content = ob_get_clean();

        // If extends() is used, render parent layout
        if ($this->parentTemplate) {
            $parent = $this->parentTemplate;
            $this->parentTemplate = null;
            $content = $this->render($parent, $data);
        }

        return $content;
    }

    /**
     * Compile a view file into cached PHP
     */
    protected function compile(string $view): string
    {
        $viewFile = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.fame.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View [$view] not found.");
        }

        $compiledFile = $this->cachePath . '/' . md5($viewFile) . '.php';

        if (!file_exists($compiledFile) || filemtime($viewFile) > filemtime($compiledFile)) {
            $contents = file_get_contents($viewFile);
            $compiled = $this->compileString($contents);
            file_put_contents($compiledFile, $compiled);
        }

        return $compiledFile;
    }

    /**
     * Convert Fame syntax into PHP
     */
    protected function compileString(string $value): string
    {
        // Echo: {{ $var }}
        $value = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= htmlspecialchars($1, ENT_QUOTES, "UTF-8") ?>', $value);

        // Raw Echo: {!! $var !!}
        $value = preg_replace('/\{!!\s*(.+?)\s*!!\}/', '<?= $1 ?>', $value);

        // If / Else
        $value = preg_replace('/#if\s*\((.+)\)/', '<?php if ($1): ?>', $value);
        $value = preg_replace('/#elseif\s*\((.+)\)/', '<?php elseif ($1): ?>', $value);
        $value = str_replace('#else', '<?php else: ?>', $value);
        $value = str_replace('#endif', '<?php endif; ?>', $value);

        // Loops
        $value = preg_replace('/#foreach\s*\((.+)\)/', '<?php foreach ($1): ?>', $value);
        $value = str_replace('#endforeach', '<?php endforeach; ?>', $value);

        $value = preg_replace('/#for\s*\((.+)\)/', '<?php for ($1): ?>', $value);
        $value = str_replace('#endfor', '<?php endfor; ?>', $value);

        $value = preg_replace('/#while\s*\((.+)\)/', '<?php while ($1): ?>', $value);
        $value = str_replace('#endwhile', '<?php endwhile; ?>', $value);

        // Include
        $value = preg_replace(
            '/#include\s*\(\s*[\'"](.+)[\'"]\s*\)/',
            '<?= $this->render("$1", get_defined_vars()) ?>',
            $value
        );

        // Extends
        $value = preg_replace(
            '/#extends\s*\(\s*[\'"](.+)[\'"]\s*\)/',
            '<?php $this->extend("$1"); ?>',
            $value
        );

        // Section
        $value = preg_replace(
            '/#section\s*\(\s*[\'"](.+)[\'"]\s*\)/',
            '<?php $this->startSection("$1"); ?>',
            $value
        );
        $value = str_replace('#endsection', '<?php $this->endSection(); ?>', $value);

        // Yield
        $value = preg_replace(
            '/#yield\s*\(\s*[\'"](.+)[\'"]\s*\)/',
            '<?= $this->yieldSection("$1"); ?>',
            $value
        );

        // CSRF
        $value = str_replace('#csrf', '<?= $this->csrfField() ?>', $value);

        return $value;
    }

    // ============= Layout System =============

    public function extend(string $view): void
    {
        $this->parentTemplate = $view;
    }

    public function startSection(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection(): void
    {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }

    public function yieldSection(string $name): string
    {
        return $this->sections[$name] ?? '';
    }

    public function csrfField(): string
    {
        $tokenName  = $_SESSION['_csrf_name'] ?? 'csrf_token';
        $tokenValue = $_SESSION['_csrf_value'] ?? bin2hex(random_bytes(32));

        $_SESSION['_csrf_name']  = $tokenName;
        $_SESSION['_csrf_value'] = $tokenValue;

        return '<input type="hidden" name="' 
            . htmlspecialchars($tokenName, ENT_QUOTES, 'UTF-8') 
            . '" value="' 
            . htmlspecialchars($tokenValue, ENT_QUOTES, 'UTF-8') 
            . '">';
    }
}
