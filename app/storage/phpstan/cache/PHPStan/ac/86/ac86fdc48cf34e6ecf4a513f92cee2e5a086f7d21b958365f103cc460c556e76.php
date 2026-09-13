<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Support/helpers.php-PHPStan\BetterReflection\Reflection\ReflectionFunction-optional
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-cf16ec9f74ecbde2bb64a7c4a93e87372f72c9c58daa117998eff4dc42c77fe6',
   'data' => 
  array (
    'name' => 'optional',
    'parameters' => 
    array (
      'value' => 
      array (
        'name' => 'value',
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 271,
            'endLine' => 271,
            'startTokenPos' => 1190,
            'startFilePos' => 6695,
            'endTokenPos' => 1190,
            'endFilePos' => 6698,
          ),
        ),
        'type' => NULL,
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 271,
        'endLine' => 271,
        'startColumn' => 23,
        'endColumn' => 35,
        'parameterIndex' => 0,
        'isOptional' => true,
      ),
      'callback' => 
      array (
        'name' => 'callback',
        'default' => 
        array (
          'code' => '\\null',
          'attributes' => 
          array (
            'startLine' => 271,
            'endLine' => 271,
            'startTokenPos' => 1200,
            'startFilePos' => 6723,
            'endTokenPos' => 1200,
            'endFilePos' => 6726,
          ),
        ),
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'callable',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'isVariadic' => false,
        'byRef' => false,
        'isPromoted' => false,
        'attributes' => 
        array (
        ),
        'startLine' => 271,
        'endLine' => 271,
        'startColumn' => 38,
        'endColumn' => 63,
        'parameterIndex' => 1,
        'isOptional' => true,
      ),
    ),
    'returnsReference' => false,
    'returnType' => NULL,
    'attributes' => 
    array (
    ),
    'docComment' => '/**
 * Provide access to optional objects.
 *
 * @template TValue
 * @template TReturn
 *
 * @param  TValue  $value
 * @param  (callable(TValue): TReturn)|null  $callback
 * @return ($callback is null ? \\Illuminate\\Support\\Optional : ($value is null ? null : TReturn))
 */',
    'startLine' => 271,
    'endLine' => 278,
    'startColumn' => 5,
    'endColumn' => 5,
    'couldThrow' => false,
    'isClosure' => false,
    'isGenerator' => false,
    'isVariadic' => false,
    'isStatic' => false,
    'namespace' => NULL,
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'optional',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Support/helpers.php',
      ),
    ),
  ),
));