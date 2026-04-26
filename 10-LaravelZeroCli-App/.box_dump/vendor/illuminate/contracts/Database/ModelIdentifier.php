<?php

namespace Illuminate\Contracts\Database;

use Illuminate\Database\Eloquent\Relations\Relation;

class ModelIdentifier
{



protected static bool $useMorphMap = false;






public $class;








public $id;






public $relations;






public $connection;






public $collectionClass;









public function __construct($class, $id, array $relations, $connection)
{
if ($class !== null && self::$useMorphMap) {
$class = Relation::getMorphAlias($class);
}

$this->class = $class;
$this->id = $id;
$this->relations = $relations;
$this->connection = $connection;
}







public function useCollectionClass(?string $collectionClass)
{
$this->collectionClass = $collectionClass;

return $this;
}






public function getClass(): ?string
{
if (self::$useMorphMap && $this->class !== null) {
return Relation::getMorphedModel($this->class) ?? $this->class;
}

return $this->class;
}




public static function useMorphMap(bool $useMorphMap = true): void
{
static::$useMorphMap = $useMorphMap;
}
}
