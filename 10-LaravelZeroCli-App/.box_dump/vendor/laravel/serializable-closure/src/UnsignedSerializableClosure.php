<?php

namespace Laravel\SerializableClosure;

use Closure;

class UnsignedSerializableClosure
{





protected $serializable;







public function __construct(Closure $closure)
{
$this->serializable = new Serializers\Native($closure);
}






public function __invoke()
{
return call_user_func_array($this->serializable, func_get_args());
}






public function getClosure()
{
return $this->serializable->getClosure();
}






public function __serialize()
{
return [
'serializable' => $this->serializable,
];
}







public function __unserialize($data)
{
$this->serializable = $data['serializable'];
}
}
