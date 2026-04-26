<?php

declare(strict_types=1);

namespace Doctrine\Inflector\Rules\Italian;

use Doctrine\Inflector\Rules\Pattern;
use Doctrine\Inflector\Rules\Substitution;
use Doctrine\Inflector\Rules\Transformation;
use Doctrine\Inflector\Rules\Word;

class Inflectible
{

public static function getSingular(): iterable
{

yield new Transformation(new Pattern('([aeiou])sce$'), '\\1scia');


yield new Transformation(new Pattern('cie$'), 'cia');


yield new Transformation(new Pattern('gie$'), 'gia');


yield new Transformation(new Pattern('([^aeiou])ce$'), '\1cia');


yield new Transformation(new Pattern('([^aeiou])ge$'), '\1gia');


yield new Transformation(new Pattern('([bcdfghjklmnpqrstvwxyz][aeiou])chi$'), '\1co');


yield new Transformation(new Pattern('([bcdfghjklmnpqrstvwxyz][aeiou])ghi$'), '\1go');


yield new Transformation(new Pattern('([aeiou][bcdfghjklmnpqrstvwxyz])ci$'), '\1co');


yield new Transformation(new Pattern('([aeiou][bcdfghjklmnpqrstvwxyz])gi$'), '\1go');



yield new Transformation(new Pattern('([^aeiou])i$'), '\1io');


yield new Transformation(new Pattern('([^aeiou])ci$'), '\1co');
yield new Transformation(new Pattern('([^aeiou])gi$'), '\1go');


yield new Transformation(new Pattern('e$'), 'a');


yield new Transformation(new Pattern('i$'), 'e');


yield new Transformation(new Pattern('i$'), 'o');
}


public static function getPlural(): iterable
{

yield new Transformation(new Pattern('([aeiou])scia$'), '\\1sce');


yield new Transformation(new Pattern('cia$'), 'cie'); 
yield new Transformation(new Pattern('gia$'), 'gie'); 


yield new Transformation(new Pattern('([^aeiou])cia$'), '\\1ce'); 
yield new Transformation(new Pattern('([^aeiou])gia$'), '\\1ge'); 


yield new Transformation(new Pattern('([bcdfghjklmnpqrstvwxyz][aeiou])co$'), '\\1chi'); 
yield new Transformation(new Pattern('([bcdfghjklmnpqrstvwxyz][aeiou])go$'), '\\1ghi'); 


yield new Transformation(new Pattern('([aeiou][bcdfghjklmnpqrstvwxyz])co$'), '\\1ci'); 
yield new Transformation(new Pattern('([aeiou][bcdfghjklmnpqrstvwxyz])go$'), '\\1gi'); 


yield new Transformation(new Pattern('([^aeiou])io$'), '\\1i'); 


yield new Transformation(new Pattern('([aeiou])io$'), '\\1i'); 


yield new Transformation(new Pattern('a$'), 'e'); 
yield new Transformation(new Pattern('e$'), 'i'); 
yield new Transformation(new Pattern('o$'), 'i'); 
}


public static function getIrregular(): iterable
{

$irregulars = [
'ala' => 'ali',
'albergo' => 'alberghi',
'amica' => 'amiche',
'amico' => 'amici',
'ampio' => 'ampi',
'arancia' => 'arance',
'arma' => 'armi',
'asparago' => 'asparagi',
'banca' => 'banche',
'belga' => 'belgi',
'braccio' => 'braccia',
'budello' => 'budella',
'bue' => 'buoi',
'caccia' => 'cacce',
'calcagno' => 'calcagna',
'camicia' => 'camicie',
'cane' => 'cani',
'capitale' => 'capitali',
'carcere' => 'carceri',
'casa' => 'case',
'cavaliere' => 'cavalieri',
'centinaio' => 'centinaia',
'cerchio' => 'cerchia',
'cervello' => 'cervella',
'chiave' => 'chiavi',
'chirurgo' => 'chirurgi',
'ciglio' => 'ciglia',
'città' => 'città',
'corno' => 'corna',
'corpo' => 'corpi',
'crisi' => 'crisi',
'dente' => 'denti',
'dio' => 'dei',
'dito' => 'dita',
'dottore' => 'dottori',
'fiore' => 'fiori',
'fratello' => 'fratelli',
'fuoco' => 'fuochi',
'gamba' => 'gambe',
'ginocchio' => 'ginocchia',
'gioco' => 'giochi',
'giornale' => 'giornali',
'giraffa' => 'giraffe',
'labbro' => 'labbra',
'lenzuolo' => 'lenzuola',
'libro' => 'libri',
'madre' => 'madri',
'maestro' => 'maestri',
'magico' => 'magici',
'mago' => 'maghi',
'maniaco' => 'maniaci',
'manico' => 'manici',
'mano' => 'mani',
'medico' => 'medici',
'membro' => 'membri',
'metropoli' => 'metropoli',
'migliaio' => 'migliaia',
'miglio' => 'miglia',
'mille' => 'mila',
'mio' => 'miei',
'moglie' => 'mogli',
'mosaico' => 'mosaici',
'muro' => 'muri',
'nemico' => 'nemici',
'nome' => 'nomi',
'occhio' => 'occhi',
'orecchio' => 'orecchi',
'osso' => 'ossa',
'paio' => 'paia',
'pane' => 'pani',
'papa' => 'papi',
'pasta' => 'paste',
'penna' => 'penne',
'pesce' => 'pesci',
'piede' => 'piedi',
'pittore' => 'pittori',
'poeta' => 'poeti',
'porco' => 'porci',
'porto' => 'porti',
'problema' => 'problemi',
'ragazzo' => 'ragazzi',
're' => 're',
'rene' => 'reni',
'riso' => 'risa',
'rosa' => 'rosa',
'sale' => 'sali',
'sarto' => 'sarti',
'scuola' => 'scuole',
'serie' => 'serie',
'serramento' => 'serramenta',
'sorella' => 'sorelle',
'specie' => 'specie',
'staio' => 'staia',
'stazione' => 'stazioni',
'strido' => 'strida',
'strillo' => 'strilla',
'studio' => 'studi',
'suo' => 'suoi',
'superficie' => 'superfici',
'tavolo' => 'tavoli',
'tempio' => 'templi',
'treno' => 'treni',
'tuo' => 'tuoi',
'uomo' => 'uomini',
'uovo' => 'uova',
'urlo' => 'urla',
'valigia' => 'valigie',
'vestigio' => 'vestigia',
'vino' => 'vini',
'viola' => 'viola',
'zio' => 'zii',
];

foreach ($irregulars as $singular => $plural) {
yield new Substitution(new Word($singular), new Word($plural));
}
}
}
