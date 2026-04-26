<?php

namespace Illuminate\Filesystem;

use Closure;
use Illuminate\Support\Traits\Conditionable;
use RuntimeException;

class LocalFilesystemAdapter extends FilesystemAdapter
{
use Conditionable;






protected $disk;






protected $shouldServeSignedUrls = false;






protected $urlGeneratorResolver;






public function providesTemporaryUrls()
{
return $this->temporaryUrlCallback || (
$this->shouldServeSignedUrls && $this->urlGeneratorResolver instanceof Closure
);
}






public function providesTemporaryUploadUrls()
{
return $this->temporaryUploadUrlCallback || (
$this->shouldServeSignedUrls && $this->urlGeneratorResolver instanceof Closure
);
}









public function temporaryUrl($path, $expiration, array $options = [])
{
if ($this->temporaryUrlCallback) {
return $this->temporaryUrlCallback->bindTo($this, static::class)(
$path, $expiration, $options
);
}

if (! $this->providesTemporaryUrls()) {
throw new RuntimeException('This driver does not support creating temporary URLs.');
}

$url = call_user_func($this->urlGeneratorResolver);

return $url->to($url->temporarySignedRoute(
'storage.'.$this->disk,
$expiration,
['path' => $path],
absolute: false
));
}









public function temporaryUploadUrl($path, $expiration, array $options = [])
{
if ($this->temporaryUploadUrlCallback) {
return $this->temporaryUploadUrlCallback->bindTo($this, static::class)(
$path, $expiration, $options
);
}

if (! $this->providesTemporaryUploadUrls()) {
throw new RuntimeException('This driver does not support creating temporary upload URLs.');
}

$url = call_user_func($this->urlGeneratorResolver);

return [
'url' => $url->to($url->temporarySignedRoute(
'storage.'.$this->disk.'.upload',
$expiration,
['path' => $path, 'upload' => true],
absolute: false
)),
'headers' => [],
];
}







public function diskName(string $disk)
{
$this->disk = $disk;

return $this;
}








public function shouldServeSignedUrls(bool $serve = true, ?Closure $urlGeneratorResolver = null)
{
$this->shouldServeSignedUrls = $serve;
$this->urlGeneratorResolver = $urlGeneratorResolver;

return $this;
}
}
