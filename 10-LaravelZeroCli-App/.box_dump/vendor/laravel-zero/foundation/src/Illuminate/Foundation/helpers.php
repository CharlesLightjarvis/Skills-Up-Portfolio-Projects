<?php

use Carbon\CarbonInterface;
use Illuminate\Broadcasting\FakePendingBroadcast;
use Illuminate\Broadcasting\PendingBroadcast;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Cookie\CookieJar;
use Illuminate\Foundation\Bus\PendingClosureDispatch;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\LogManager;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Support\Defer\DeferredCallback;
use Illuminate\Support\Defer\DeferredCallbackCollection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Uri;
use League\Uri\Contracts\UriInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

use function Illuminate\Support\enum_value;

if (! function_exists('abort')) {











function abort($code, $message = '', array $headers = [])
{
if ($code instanceof Response) {
throw new HttpResponseException($code);
} elseif ($code instanceof Responsable) {
throw new HttpResponseException($code->toResponse(request()));
}

app()->abort($code, $message, $headers);
}
}

if (! function_exists('abort_if')) {










function abort_if($boolean, $code, $message = '', array $headers = []): void
{
if ($boolean) {
abort($code, $message, $headers);
}
}
}

if (! function_exists('abort_unless')) {










function abort_unless($boolean, $code, $message = '', array $headers = []): void
{
if (! $boolean) {
abort($code, $message, $headers);
}
}
}

if (! function_exists('action')) {







function action($name, $parameters = [], $absolute = true): string
{
return app('url')->action($name, $parameters, $absolute);
}
}

if (! function_exists('app')) {
/**
@template





*/
function app($abstract = null, array $parameters = [])
{
if (is_null($abstract)) {
return Container::getInstance();
}

return Container::getInstance()->make($abstract, $parameters);
}
}

if (! function_exists('app_path')) {





function app_path($path = ''): string
{
return app()->path($path);
}
}

if (! function_exists('asset')) {






function asset($path, $secure = null): string
{
return app('url')->asset($path, $secure);
}
}

if (! function_exists('auth')) {






function auth($guard = null): AuthFactory|Guard
{
if (is_null($guard)) {
return app(AuthFactory::class);
}

return app(AuthFactory::class)->guard($guard);
}
}

if (! function_exists('back')) {







function back($status = 302, $headers = [], $fallback = false): RedirectResponse
{
return app('redirect')->back($status, $headers, $fallback);
}
}

if (! function_exists('base_path')) {





function base_path($path = ''): string
{
return app()->basePath($path);
}
}

if (! function_exists('bcrypt')) {






function bcrypt($value, $options = []): string
{
return app('hash')->driver('bcrypt')->make($value, $options);
}
}

if (! function_exists('broadcast')) {





function broadcast($event = null): PendingBroadcast
{
return app(BroadcastFactory::class)->event($event);
}
}

if (! function_exists('broadcast_if')) {






function broadcast_if($boolean, $event = null): PendingBroadcast
{
if ($boolean) {
return app(BroadcastFactory::class)->event(value($event));
} else {
return new FakePendingBroadcast;
}
}
}

if (! function_exists('broadcast_unless')) {






function broadcast_unless($boolean, $event = null): PendingBroadcast
{
if (! $boolean) {
return app(BroadcastFactory::class)->event(value($event));
} else {
return new FakePendingBroadcast;
}
}
}

if (! function_exists('cache')) {











function cache($key = null, $default = null)
{
if (is_null($key)) {
return app('cache');
}

if (is_string($key)) {
return app('cache')->get($key, $default);
}

if (! is_array($key)) {
throw new InvalidArgumentException(
'When setting a value in the cache, you must pass an array of key / value pairs.'
);
}

return app('cache')->put(key($key), array_first($key), ttl: $default);
}
}

if (! function_exists('config')) {









function config($key = null, $default = null)
{
if (is_null($key)) {
return app('config');
}

if (is_array($key)) {
return app('config')->set($key);
}

return app('config')->get($key, $default);
}
}

if (! function_exists('config_path')) {





function config_path($path = ''): string
{
return app()->configPath($path);
}
}

if (! function_exists('context')) {







function context($key = null, $default = null)
{
$context = app(ContextRepository::class);

return match (true) {
is_null($key) => $context,
is_array($key) => $context->add($key),
default => $context->get($key, $default),
};
}
}

if (! function_exists('cookie')) {














function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null): CookieJar|Cookie
{
$cookie = app(CookieFactory::class);

if (is_null($name)) {
return $cookie;
}

return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
}
}

if (! function_exists('csrf_field')) {



function csrf_field(): HtmlString
{
return new HtmlString('<input type="hidden" name="_token" value="'.csrf_token().'" autocomplete="off">');
}
}

if (! function_exists('csrf_token')) {





function csrf_token(): ?string
{
$session = app('session');

if (isset($session)) {
return $session->token();
}

throw new RuntimeException('Application session store not set.');
}
}

if (! function_exists('database_path')) {





function database_path($path = ''): string
{
return app()->databasePath($path);
}
}

if (! function_exists('decrypt')) {







function decrypt($value, $unserialize = true)
{
return app('encrypter')->decrypt($value, $unserialize);
}
}

if (! function_exists('defer')) {





function defer(?callable $callback = null, ?string $name = null, bool $always = false): DeferredCallback|DeferredCallbackCollection
{
return \Illuminate\Support\defer($callback, $name, $always);
}
}

if (! function_exists('dispatch')) {






function dispatch($job): PendingDispatch|PendingClosureDispatch
{
return $job instanceof Closure
? new PendingClosureDispatch(CallQueuedClosure::create($job))
: new PendingDispatch($job);
}
}

if (! function_exists('dispatch_sync')) {









function dispatch_sync($job, $handler = null)
{
return app(Dispatcher::class)->dispatchSync($job, $handler);
}
}

if (! function_exists('encrypt')) {






function encrypt($value, $serialize = true): string
{
return app('encrypter')->encrypt($value, $serialize);
}
}

if (! function_exists('event')) {








function event(...$args)
{
return app('events')->dispatch(...$args);
}
}

if (! function_exists('fake') && class_exists(\Faker\Factory::class)) {





function fake($locale = null): \Faker\Generator
{
if (app()->bound('config')) {
$locale ??= app('config')->get('app.faker_locale');
}

$locale ??= 'en_US';

$abstract = \Faker\Generator::class.':'.$locale;

if (! app()->bound($abstract)) {
app()->singleton($abstract, fn () => \Faker\Factory::create($locale));
}

return app()->make($abstract);
}
}

if (! function_exists('info')) {






function info($message, $context = []): void
{
app('log')->info($message, $context);
}
}

if (! function_exists('lang_path')) {





function lang_path($path = ''): string
{
return app()->langPath($path);
}
}

if (! function_exists('logger')) {






function logger($message = null, array $context = []): ?LoggerInterface
{
if (is_null($message)) {
return app('log');
}

return app('log')->debug($message, $context);
}
}

if (! function_exists('logs')) {






function logs($driver = null): LoggerInterface|LogManager
{
return $driver ? app('log')->driver($driver) : app('log');
}
}

if (! function_exists('method_field')) {





function method_field($method): HtmlString
{
return new HtmlString('<input type="hidden" name="_method" value="'.$method.'">');
}
}

if (! function_exists('mix')) {








function mix($path, $manifestDirectory = ''): HtmlString|string
{
return app(Mix::class)(...func_get_args());
}
}

if (! function_exists('now')) {





function now($tz = null): CarbonInterface
{
return Date::now(enum_value($tz));
}
}

if (! function_exists('old')) {







function old($key = null, $default = null)
{
return app('request')->old($key, $default);
}
}

if (! function_exists('policy')) {








function policy($class)
{
return app(Gate::class)->getPolicyFor($class);
}
}

if (! function_exists('precognitive')) {






function precognitive($callable = null)
{
$callable ??= function () {

};

$payload = $callable(function ($default, $precognition = null) {
$response = request()->isPrecognitive()
? ($precognition ?? $default)
: $default;

abort(Router::toResponse(request(), value($response)));
});

if (request()->isPrecognitive()) {
abort(204, headers: ['Precognition-Success' => 'true']);
}

return $payload;
}
}

if (! function_exists('public_path')) {





function public_path($path = ''): string
{
return app()->publicPath($path);
}
}

if (! function_exists('redirect')) {









function redirect($to = null, $status = 302, $headers = [], $secure = null): Redirector|RedirectResponse
{
if (is_null($to)) {
return app('redirect');
}

return app('redirect')->to($to, $status, $headers, $secure);
}
}

if (! function_exists('report')) {





function report($exception): void
{
if (is_string($exception)) {
$exception = new Exception($exception);
}

app(ExceptionHandler::class)->report($exception);
}
}

if (! function_exists('report_if')) {






function report_if($boolean, $exception): void
{
if ($boolean) {
report($exception);
}
}
}

if (! function_exists('report_unless')) {






function report_unless($boolean, $exception): void
{
if (! $boolean) {
report($exception);
}
}
}

if (! function_exists('request')) {







function request($key = null, $default = null)
{
if (is_null($key)) {
return app('request');
}

if (is_array($key)) {
return app('request')->only($key);
}

$value = app('request')->__get($key);

return is_null($value) ? value($default) : $value;
}
}

if (! function_exists('rescue')) {
/**
@template
@template







*/
function rescue(callable $callback, $rescue = null, $report = true)
{
try {
return $callback();
} catch (Throwable $e) {
if (value($report, $e)) {
report($e);
}

return value($rescue, $e);
}
}
}

if (! function_exists('resolve')) {
/**
@template





*/
function resolve($name, array $parameters = [])
{
return app($name, $parameters);
}
}

if (! function_exists('resource_path')) {





function resource_path($path = ''): string
{
return app()->resourcePath($path);
}
}

if (! function_exists('response')) {







function response($content = null, $status = 200, array $headers = []): ResponseFactory|IlluminateResponse
{
$factory = app(ResponseFactory::class);

if (func_num_args() === 0) {
return $factory;
}

return $factory->make($content ?? '', $status, $headers);
}
}

if (! function_exists('route')) {







function route($name, $parameters = [], $absolute = true): string
{
return app('url')->route($name, $parameters, $absolute);
}
}

if (! function_exists('secure_asset')) {





function secure_asset($path): string
{
return asset($path, true);
}
}

if (! function_exists('secure_url')) {







function secure_url($path, $parameters = [])
{
return url($path, $parameters, true);
}
}

if (! function_exists('session')) {









function session($key = null, $default = null)
{
if (is_null($key)) {
return app('session');
}

if (is_array($key)) {
return app('session')->put($key);
}

return app('session')->get($key, $default);
}
}

if (! function_exists('storage_path')) {





function storage_path($path = ''): string
{
return app()->storagePath($path);
}
}

if (! function_exists('to_action')) {









function to_action($action, $parameters = [], $status = 302, $headers = [])
{
return redirect()->action($action, $parameters, $status, $headers);
}
}

if (! function_exists('to_route')) {









function to_route($route, $parameters = [], $status = 302, $headers = [])
{
return redirect()->route($route, $parameters, $status, $headers);
}
}

if (! function_exists('today')) {






function today($tz = null): CarbonInterface
{
return Date::today(enum_value($tz));
}
}

if (! function_exists('trans')) {








function trans($key = null, $replace = [], $locale = null): Translator|array|string
{
if (is_null($key)) {
return app('translator');
}

return app('translator')->get($key, $replace, $locale);
}
}

if (! function_exists('trans_choice')) {







function trans_choice($key, $number, array $replace = [], $locale = null): string
{
return app('translator')->choice($key, $number, $replace, $locale);
}
}

if (! function_exists('__')) {







function __($key = null, $replace = [], $locale = null): string|array|null
{
if (is_null($key)) {
return $key;
}

return trans($key, $replace, $locale);
}
}

if (! function_exists('uri')) {



function uri(UriInterface|Stringable|array|string $uri, mixed $parameters = [], bool $absolute = true): Uri
{
return match (true) {
is_array($uri) || str_contains($uri, '\\') => Uri::action($uri, $parameters, $absolute),
str_contains($uri, '.') && Route::has($uri) => Uri::route($uri, $parameters, $absolute),
default => Uri::of($uri),
};
}
}

if (! function_exists('url')) {








function url($path = null, $parameters = [], $secure = null): UrlGenerator|string
{
if (is_null($path)) {
return app(UrlGenerator::class);
}

return app(UrlGenerator::class)->to($path, $parameters, $secure);
}
}

if (! function_exists('validator')) {





function validator(?array $data = null, array $rules = [], array $messages = [], array $attributes = []): ValidatorContract|ValidationFactory
{
$factory = app(ValidationFactory::class);

if (func_num_args() === 0) {
return $factory;
}

return $factory->make($data ?? [], $rules, $messages, $attributes);
}
}

if (! function_exists('view')) {








function view($view = null, $data = [], $mergeData = []): ViewFactory|ViewContract
{
$factory = app(ViewFactory::class);

if (func_num_args() === 0) {
return $factory;
}

return $factory->make($view, $data, $mergeData);
}
}
