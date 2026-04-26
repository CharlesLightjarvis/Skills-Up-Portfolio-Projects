<?php

namespace Illuminate\Foundation\Exceptions\Renderer;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Exceptions\Renderer\Mappers\BladeMapper;
use Illuminate\Http\Request;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Throwable;

class Renderer
{





protected const DIST = __DIR__.'/../../resources/exceptions/renderer/dist/';






protected $viewFactory;






protected $listener;






protected $htmlErrorRenderer;






protected $bladeMapper;






protected $basePath;










public function __construct(
Factory $viewFactory,
Listener $listener,
HtmlErrorRenderer $htmlErrorRenderer,
BladeMapper $bladeMapper,
string $basePath,
) {
$this->viewFactory = $viewFactory;
$this->listener = $listener;
$this->htmlErrorRenderer = $htmlErrorRenderer;
$this->bladeMapper = $bladeMapper;
$this->basePath = $basePath;
}








public function render(Request $request, Throwable $throwable)
{
$flattenException = $this->bladeMapper->map(
$this->htmlErrorRenderer->render($throwable),
);

$exception = new Exception($flattenException, $request, $this->listener, $this->basePath);

$exceptionAsMarkdown = $this->viewFactory->make('laravel-exceptions-renderer::markdown', [
'exception' => $exception,
])->render();

return $this->viewFactory->make('laravel-exceptions-renderer::show', [
'exception' => $exception,
'exceptionAsMarkdown' => $exceptionAsMarkdown,
])->render();
}






public static function css()
{
return '<style>'.file_get_contents(static::DIST.'styles.css').'</style>';
}






public static function js()
{
$viteJsAutoRefresh = '';

$vite = app(\Illuminate\Foundation\Vite::class);

if (is_file($vite->hotFile())) {
$viteJsAutoRefresh = $vite->__invoke([]);
}

return '<script>'
.file_get_contents(static::DIST.'scripts.js')
.'</script>'.$viteJsAutoRefresh;
}
}
