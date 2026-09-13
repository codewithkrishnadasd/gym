<?php declare(strict_types = 1);

// osfsl-/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/QueriesRelationships.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\Concerns\QueriesRelationships
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-33452e2d263e1a34caa0d5c243ba138724b88156482829a15b20c9cf4c75b321-8.4.4-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Concerns/QueriesRelationships.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
    'name' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
    'shortName' => 'QueriesRelationships',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/** @mixin \\Illuminate\\Database\\Eloquent\\Builder */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 1139,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'has' => 
      array (
        'name' => 'has',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 25,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 119,
                'startFilePos' => 1438,
                'endTokenPos' => 119,
                'endFilePos' => 1441,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 126,
                'startFilePos' => 1453,
                'endTokenPos' => 126,
                'endFilePos' => 1453,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 54,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 133,
                'startFilePos' => 1467,
                'endTokenPos' => 133,
                'endFilePos' => 1471,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 66,
            'endColumn' => 81,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 39,
                'endLine' => 39,
                'startTokenPos' => 143,
                'startFilePos' => 1495,
                'endTokenPos' => 143,
                'endFilePos' => 1498,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 84,
            'endColumn' => 108,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @param  string  $boolean
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @return $this
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 39,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'hasNested' => 
      array (
        'name' => 'hasNested',
        'parameters' => 
        array (
          'relations' => 
          array (
            'name' => 'relations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 34,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 373,
                'startFilePos' => 3480,
                'endTokenPos' => 373,
                'endFilePos' => 3483,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 46,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 380,
                'startFilePos' => 3495,
                'endTokenPos' => 380,
                'endFilePos' => 3495,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 64,
            'endColumn' => 73,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 387,
                'startFilePos' => 3509,
                'endTokenPos' => 387,
                'endFilePos' => 3513,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 76,
            'endColumn' => 91,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 394,
                'startFilePos' => 3528,
                'endTokenPos' => 394,
                'endFilePos' => 3531,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 94,
            'endColumn' => 109,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add nested relationship count / exists conditions to the query.
 *
 * Sets up recursive call to whereHas until we finish the nested relation.
 *
 * @param  string  $relations
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @param  string  $boolean
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<*>): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 88,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orHas' => 
      array (
        'name' => 'orHas',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 27,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 128,
                'endLine' => 128,
                'startTokenPos' => 647,
                'startFilePos' => 5111,
                'endTokenPos' => 647,
                'endFilePos' => 5114,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 128,
                'endLine' => 128,
                'startTokenPos' => 654,
                'startFilePos' => 5126,
                'endTokenPos' => 654,
                'endFilePos' => 5126,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 56,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query with an "or".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>|string  $relation
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 128,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'doesntHave' => 
      array (
        'name' => 'doesntHave',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 695,
                'startFilePos' => 5682,
                'endTokenPos' => 695,
                'endFilePos' => 5686,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 705,
                'startFilePos' => 5710,
                'endTokenPos' => 705,
                'endFilePos' => 5713,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 61,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  string  $boolean
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 143,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orDoesntHave' => 
      array (
        'name' => 'orDoesntHave',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 34,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query with an "or".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>|string  $relation
 * @return $this
 */',
        'startLine' => 154,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereHas' => 
      array (
        'name' => 'whereHas',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 170,
            'endLine' => 170,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 170,
                'endLine' => 170,
                'startTokenPos' => 780,
                'startFilePos' => 6697,
                'endTokenPos' => 780,
                'endFilePos' => 6700,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 170,
            'endLine' => 170,
            'startColumn' => 41,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 170,
                'endLine' => 170,
                'startTokenPos' => 787,
                'startFilePos' => 6715,
                'endTokenPos' => 787,
                'endFilePos' => 6718,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 170,
            'endLine' => 170,
            'startColumn' => 68,
            'endColumn' => 83,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 170,
                'endLine' => 170,
                'startTokenPos' => 794,
                'startFilePos' => 6730,
                'endTokenPos' => 794,
                'endFilePos' => 6730,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 170,
            'endLine' => 170,
            'startColumn' => 86,
            'endColumn' => 95,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query with where clauses.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 170,
        'endLine' => 173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withWhereHas' => 
      array (
        'name' => 'withWhereHas',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 34,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 841,
                'startFilePos' => 7370,
                'endTokenPos' => 841,
                'endFilePos' => 7373,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 45,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 848,
                'startFilePos' => 7388,
                'endTokenPos' => 848,
                'endFilePos' => 7391,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 72,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 855,
                'startFilePos' => 7403,
                'endTokenPos' => 855,
                'endFilePos' => 7403,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
            'startColumn' => 90,
            'endColumn' => 99,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query with where clauses.
 *
 * Also load the relationship with the same condition.
 *
 * @param  string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>): mixed)|null  $callback
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 186,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereHas' => 
      array (
        'name' => 'orWhereHas',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 940,
                'startFilePos' => 8203,
                'endTokenPos' => 940,
                'endFilePos' => 8206,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 43,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 947,
                'startFilePos' => 8221,
                'endTokenPos' => 947,
                'endFilePos' => 8224,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 70,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 954,
                'startFilePos' => 8236,
                'endTokenPos' => 954,
                'endFilePos' => 8236,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 88,
            'endColumn' => 97,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a relationship count / exists condition to the query with where clauses and an "or".
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 203,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereDoesntHave' => 
      array (
        'name' => 'whereDoesntHave',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 217,
            'endLine' => 217,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 217,
                'endLine' => 217,
                'startTokenPos' => 1001,
                'startFilePos' => 8805,
                'endTokenPos' => 1001,
                'endFilePos' => 8808,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 217,
            'endLine' => 217,
            'startColumn' => 48,
            'endColumn' => 72,
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
 * Add a relationship count / exists condition to the query with where clauses.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 217,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereDoesntHave' => 
      array (
        'name' => 'orWhereDoesntHave',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 231,
                'endLine' => 231,
                'startTokenPos' => 1042,
                'startFilePos' => 9380,
                'endTokenPos' => 1042,
                'endFilePos' => 9383,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 231,
            'endLine' => 231,
            'startColumn' => 50,
            'endColumn' => 74,
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
 * Add a relationship count / exists condition to the query with where clauses and an "or".
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 231,
        'endLine' => 234,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'hasMorph' => 
      array (
        'name' => 'hasMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 249,
                'endLine' => 249,
                'startTokenPos' => 1083,
                'startFilePos' => 10118,
                'endTokenPos' => 1083,
                'endFilePos' => 10121,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 49,
            'endColumn' => 64,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 249,
                'endLine' => 249,
                'startTokenPos' => 1090,
                'startFilePos' => 10133,
                'endTokenPos' => 1090,
                'endFilePos' => 10133,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 67,
            'endColumn' => 76,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 249,
                'endLine' => 249,
                'startTokenPos' => 1097,
                'startFilePos' => 10147,
                'endTokenPos' => 1097,
                'endFilePos' => 10151,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 79,
            'endColumn' => 94,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 249,
                'endLine' => 249,
                'startTokenPos' => 1107,
                'startFilePos' => 10175,
                'endTokenPos' => 1107,
                'endFilePos' => 10178,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 97,
            'endColumn' => 121,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @param  string  $boolean
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 249,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'getBelongsToRelation' => 
      array (
        'name' => 'getBelongsToRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 45,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 64,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the BelongsTo relationship for a single polymorphic type.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 * @template TDeclaringModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, TDeclaringModel>  $relation
 * @param  class-string<TRelatedModel>  $type
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo<TRelatedModel, TDeclaringModel>
 */',
        'startLine' => 308,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orHasMorph' => 
      array (
        'name' => 'orHasMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 332,
                'endLine' => 332,
                'startTokenPos' => 1718,
                'startFilePos' => 13414,
                'endTokenPos' => 1718,
                'endFilePos' => 13417,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 51,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 332,
                'endLine' => 332,
                'startTokenPos' => 1725,
                'startFilePos' => 13429,
                'endTokenPos' => 1725,
                'endFilePos' => 13429,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 332,
            'endLine' => 332,
            'startColumn' => 69,
            'endColumn' => 78,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with an "or".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 332,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'doesntHaveMorph' => 
      array (
        'name' => 'doesntHaveMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 48,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 348,
                'endLine' => 348,
                'startTokenPos' => 1772,
                'startFilePos' => 14076,
                'endTokenPos' => 1772,
                'endFilePos' => 14080,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 56,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 348,
                'endLine' => 348,
                'startTokenPos' => 1782,
                'startFilePos' => 14104,
                'endTokenPos' => 1782,
                'endFilePos' => 14107,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 74,
            'endColumn' => 98,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  string  $boolean
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 348,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orDoesntHaveMorph' => 
      array (
        'name' => 'orDoesntHaveMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 360,
            'endLine' => 360,
            'startColumn' => 50,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with an "or".
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @return $this
 */',
        'startLine' => 360,
        'endLine' => 363,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereHasMorph' => 
      array (
        'name' => 'whereHasMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 377,
            'endLine' => 377,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 377,
            'endLine' => 377,
            'startColumn' => 46,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 377,
                'endLine' => 377,
                'startTokenPos' => 1869,
                'startFilePos' => 15265,
                'endTokenPos' => 1869,
                'endFilePos' => 15268,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 377,
            'endLine' => 377,
            'startColumn' => 54,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 377,
                'endLine' => 377,
                'startTokenPos' => 1876,
                'startFilePos' => 15283,
                'endTokenPos' => 1876,
                'endFilePos' => 15286,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 377,
            'endLine' => 377,
            'startColumn' => 81,
            'endColumn' => 96,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 377,
                'endLine' => 377,
                'startTokenPos' => 1883,
                'startFilePos' => 15298,
                'endTokenPos' => 1883,
                'endFilePos' => 15298,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 377,
            'endLine' => 377,
            'startColumn' => 99,
            'endColumn' => 108,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with where clauses.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 377,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereHasMorph' => 
      array (
        'name' => 'orWhereHasMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 394,
            'endLine' => 394,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 394,
            'endLine' => 394,
            'startColumn' => 48,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 394,
                'endLine' => 394,
                'startTokenPos' => 1936,
                'startFilePos' => 16074,
                'endTokenPos' => 1936,
                'endFilePos' => 16077,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 394,
            'endLine' => 394,
            'startColumn' => 56,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 394,
                'endLine' => 394,
                'startTokenPos' => 1943,
                'startFilePos' => 16092,
                'endTokenPos' => 1943,
                'endFilePos' => 16095,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 394,
            'endLine' => 394,
            'startColumn' => 83,
            'endColumn' => 98,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 394,
                'endLine' => 394,
                'startTokenPos' => 1950,
                'startFilePos' => 16107,
                'endTokenPos' => 1950,
                'endFilePos' => 16107,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 394,
            'endLine' => 394,
            'startColumn' => 101,
            'endColumn' => 110,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return $this
 */',
        'startLine' => 394,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereDoesntHaveMorph' => 
      array (
        'name' => 'whereDoesntHaveMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 409,
            'endLine' => 409,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 409,
            'endLine' => 409,
            'startColumn' => 53,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 409,
                'endLine' => 409,
                'startTokenPos' => 2003,
                'startFilePos' => 16767,
                'endTokenPos' => 2003,
                'endFilePos' => 16770,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 409,
            'endLine' => 409,
            'startColumn' => 61,
            'endColumn' => 85,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with where clauses.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 409,
        'endLine' => 412,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereDoesntHaveMorph' => 
      array (
        'name' => 'orWhereDoesntHaveMorph',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 424,
            'endLine' => 424,
            'startColumn' => 44,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 424,
            'endLine' => 424,
            'startColumn' => 55,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 424,
                'endLine' => 424,
                'startTokenPos' => 2050,
                'startFilePos' => 17433,
                'endTokenPos' => 2050,
                'endFilePos' => 17436,
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
                      'name' => 'Closure',
                      'isIdentifier' => false,
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
            'startLine' => 424,
            'endLine' => 424,
            'startColumn' => 63,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>, string): mixed)|null  $callback
 * @return $this
 */',
        'startLine' => 424,
        'endLine' => 427,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereRelation' => 
      array (
        'name' => 'whereRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 46,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 440,
                'endLine' => 440,
                'startTokenPos' => 2094,
                'startFilePos' => 18092,
                'endTokenPos' => 2094,
                'endFilePos' => 18095,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 55,
            'endColumn' => 70,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 440,
                'endLine' => 440,
                'startTokenPos' => 2101,
                'startFilePos' => 18107,
                'endTokenPos' => 2101,
                'endFilePos' => 18110,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 440,
            'endLine' => 440,
            'startColumn' => 73,
            'endColumn' => 85,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a basic where clause to a relationship query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 440,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withWhereRelation' => 
      array (
        'name' => 'withWhereRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 50,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 2199,
                'startFilePos' => 18880,
                'endTokenPos' => 2199,
                'endFilePos' => 18883,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 59,
            'endColumn' => 74,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 2206,
                'startFilePos' => 18895,
                'endTokenPos' => 2206,
                'endFilePos' => 18898,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 77,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a basic where clause to a relationship query and eager-load the relationship with the same conditions.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>|string  $relation
 * @param  \\Closure|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 460,
        'endLine' => 468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereRelation' => 
      array (
        'name' => 'orWhereRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 48,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 481,
                'endLine' => 481,
                'startTokenPos' => 2301,
                'startFilePos' => 19774,
                'endTokenPos' => 2301,
                'endFilePos' => 19777,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 57,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 481,
                'endLine' => 481,
                'startTokenPos' => 2308,
                'startFilePos' => 19789,
                'endTokenPos' => 2308,
                'endFilePos' => 19792,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 75,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add an "or where" clause to a relationship query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 481,
        'endLine' => 490,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereDoesntHaveRelation' => 
      array (
        'name' => 'whereDoesntHaveRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 503,
            'endLine' => 503,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 503,
            'endLine' => 503,
            'startColumn' => 56,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 503,
                'endLine' => 503,
                'startTokenPos' => 2406,
                'startFilePos' => 20677,
                'endTokenPos' => 2406,
                'endFilePos' => 20680,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 503,
            'endLine' => 503,
            'startColumn' => 65,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 503,
                'endLine' => 503,
                'startTokenPos' => 2413,
                'startFilePos' => 20692,
                'endTokenPos' => 2413,
                'endFilePos' => 20695,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 503,
            'endLine' => 503,
            'startColumn' => 83,
            'endColumn' => 95,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a basic count / exists condition to a relationship query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 503,
        'endLine' => 512,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereDoesntHaveRelation' => 
      array (
        'name' => 'orWhereDoesntHaveRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 47,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 58,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 525,
                'endLine' => 525,
                'startTokenPos' => 2511,
                'startFilePos' => 21575,
                'endTokenPos' => 2511,
                'endFilePos' => 21578,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 67,
            'endColumn' => 82,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 525,
                'endLine' => 525,
                'startTokenPos' => 2518,
                'startFilePos' => 21590,
                'endTokenPos' => 2518,
                'endFilePos' => 21593,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 525,
            'endLine' => 525,
            'startColumn' => 85,
            'endColumn' => 97,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add an "or where" clause to a relationship query.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<TRelatedModel, *, *>|string  $relation
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 525,
        'endLine' => 534,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereMorphRelation' => 
      array (
        'name' => 'whereMorphRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 548,
            'endLine' => 548,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 548,
            'endLine' => 548,
            'startColumn' => 51,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 548,
            'endLine' => 548,
            'startColumn' => 59,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 548,
                'endLine' => 548,
                'startTokenPos' => 2619,
                'startFilePos' => 22546,
                'endTokenPos' => 2619,
                'endFilePos' => 22549,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 548,
            'endLine' => 548,
            'startColumn' => 68,
            'endColumn' => 83,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 548,
                'endLine' => 548,
                'startTokenPos' => 2626,
                'startFilePos' => 22561,
                'endTokenPos' => 2626,
                'endFilePos' => 22564,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 548,
            'endLine' => 548,
            'startColumn' => 86,
            'endColumn' => 98,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship condition to the query with a where clause.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 548,
        'endLine' => 553,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereMorphRelation' => 
      array (
        'name' => 'orWhereMorphRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 53,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 61,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 567,
                'endLine' => 567,
                'startTokenPos' => 2704,
                'startFilePos' => 23411,
                'endTokenPos' => 2704,
                'endFilePos' => 23414,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 70,
            'endColumn' => 85,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 567,
                'endLine' => 567,
                'startTokenPos' => 2711,
                'startFilePos' => 23426,
                'endTokenPos' => 2711,
                'endFilePos' => 23429,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 88,
            'endColumn' => 100,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship condition to the query with an "or where" clause.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 567,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereMorphDoesntHaveRelation' => 
      array (
        'name' => 'whereMorphDoesntHaveRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 50,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 61,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 69,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 586,
                'endLine' => 586,
                'startTokenPos' => 2789,
                'startFilePos' => 24287,
                'endTokenPos' => 2789,
                'endFilePos' => 24290,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 78,
            'endColumn' => 93,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 586,
                'endLine' => 586,
                'startTokenPos' => 2796,
                'startFilePos' => 24302,
                'endTokenPos' => 2796,
                'endFilePos' => 24305,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 586,
            'endLine' => 586,
            'startColumn' => 96,
            'endColumn' => 108,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship condition to the query with a doesn\'t have clause.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 586,
        'endLine' => 591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereMorphDoesntHaveRelation' => 
      array (
        'name' => 'orWhereMorphDoesntHaveRelation',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 605,
            'endLine' => 605,
            'startColumn' => 52,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'types' => 
          array (
            'name' => 'types',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 605,
            'endLine' => 605,
            'startColumn' => 63,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 605,
            'endLine' => 605,
            'startColumn' => 71,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 605,
                'endLine' => 605,
                'startTokenPos' => 2874,
                'startFilePos' => 25176,
                'endTokenPos' => 2874,
                'endFilePos' => 25179,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 605,
            'endLine' => 605,
            'startColumn' => 80,
            'endColumn' => 95,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 605,
                'endLine' => 605,
                'startTokenPos' => 2881,
                'startFilePos' => 25191,
                'endTokenPos' => 2881,
                'endFilePos' => 25194,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 605,
            'endLine' => 605,
            'startColumn' => 98,
            'endColumn' => 110,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a polymorphic relationship condition to the query with an "or doesn\'t have" clause.
 *
 * @template TRelatedModel of \\Illuminate\\Database\\Eloquent\\Model
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<TRelatedModel, *>|string  $relation
 * @param  string|array<int, string>  $types
 * @param  (\\Closure(\\Illuminate\\Database\\Eloquent\\Builder<TRelatedModel>): mixed)|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 605,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereMorphedTo' => 
      array (
        'name' => 'whereMorphedTo',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 621,
            'endLine' => 621,
            'startColumn' => 36,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 621,
            'endLine' => 621,
            'startColumn' => 47,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 621,
                'endLine' => 621,
                'startTokenPos' => 2956,
                'startFilePos' => 25819,
                'endTokenPos' => 2956,
                'endFilePos' => 25823,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 621,
            'endLine' => 621,
            'startColumn' => 55,
            'endColumn' => 70,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a morph-to relationship condition to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  \\Illuminate\\Database\\Eloquent\\Model|iterable<int, \\Illuminate\\Database\\Eloquent\\Model>|string|null  $model
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 621,
        'endLine' => 655,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereNotMorphedTo' => 
      array (
        'name' => 'whereNotMorphedTo',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 50,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 666,
                'endLine' => 666,
                'startTokenPos' => 3325,
                'startFilePos' => 27661,
                'endTokenPos' => 3325,
                'endFilePos' => 27665,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 666,
            'endLine' => 666,
            'startColumn' => 58,
            'endColumn' => 73,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a not morph-to relationship condition to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  \\Illuminate\\Database\\Eloquent\\Model|iterable<int, \\Illuminate\\Database\\Eloquent\\Model>|string  $model
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 666,
        'endLine' => 698,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereMorphedTo' => 
      array (
        'name' => 'orWhereMorphedTo',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 707,
            'endLine' => 707,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 707,
            'endLine' => 707,
            'startColumn' => 49,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a morph-to relationship condition to the query with an "or where" clause.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  \\Illuminate\\Database\\Eloquent\\Model|iterable<int, \\Illuminate\\Database\\Eloquent\\Model>|string|null  $model
 * @return $this
 */',
        'startLine' => 707,
        'endLine' => 710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereNotMorphedTo' => 
      array (
        'name' => 'orWhereNotMorphedTo',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 719,
            'endLine' => 719,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 719,
            'endLine' => 719,
            'startColumn' => 52,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a not morph-to relationship condition to the query with an "or where" clause.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo<*, *>|string  $relation
 * @param  \\Illuminate\\Database\\Eloquent\\Model|iterable<int, \\Illuminate\\Database\\Eloquent\\Model>|string  $model
 * @return $this
 */',
        'startLine' => 719,
        'endLine' => 722,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereBelongsTo' => 
      array (
        'name' => 'whereBelongsTo',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 735,
            'endLine' => 735,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relationshipName' => 
          array (
            'name' => 'relationshipName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 735,
                'endLine' => 735,
                'startTokenPos' => 3742,
                'startFilePos' => 30498,
                'endTokenPos' => 3742,
                'endFilePos' => 30501,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 735,
            'endLine' => 735,
            'startColumn' => 46,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 735,
                'endLine' => 735,
                'startTokenPos' => 3749,
                'startFilePos' => 30515,
                'endTokenPos' => 3749,
                'endFilePos' => 30519,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 735,
            'endLine' => 735,
            'startColumn' => 72,
            'endColumn' => 87,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "belongs to" relationship where clause to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|\\Illuminate\\Database\\Eloquent\\Collection<int, \\Illuminate\\Database\\Eloquent\\Model>  $related
 * @param  string|null  $relationshipName
 * @param  string  $boolean
 * @return $this
 *
 * @throws \\InvalidArgumentException
 * @throws \\Illuminate\\Database\\Eloquent\\RelationNotFoundException
 */',
        'startLine' => 735,
        'endLine' => 770,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereBelongsTo' => 
      array (
        'name' => 'orWhereBelongsTo',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 779,
            'endLine' => 779,
            'startColumn' => 38,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relationshipName' => 
          array (
            'name' => 'relationshipName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 779,
                'endLine' => 779,
                'startTokenPos' => 3999,
                'startFilePos' => 31974,
                'endTokenPos' => 3999,
                'endFilePos' => 31977,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 779,
            'endLine' => 779,
            'startColumn' => 48,
            'endColumn' => 71,
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
 * Add a "BelongsTo" relationship with an "or where" clause to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $related
 * @param  string|null  $relationshipName
 * @return $this
 */',
        'startLine' => 779,
        'endLine' => 782,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'whereAttachedTo' => 
      array (
        'name' => 'whereAttachedTo',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 795,
            'endLine' => 795,
            'startColumn' => 37,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relationshipName' => 
          array (
            'name' => 'relationshipName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 795,
                'endLine' => 795,
                'startTokenPos' => 4037,
                'startFilePos' => 32588,
                'endTokenPos' => 4037,
                'endFilePos' => 32591,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 795,
            'endLine' => 795,
            'startColumn' => 47,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 795,
                'endLine' => 795,
                'startTokenPos' => 4044,
                'startFilePos' => 32605,
                'endTokenPos' => 4044,
                'endFilePos' => 32609,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 795,
            'endLine' => 795,
            'startColumn' => 73,
            'endColumn' => 88,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "belongs to many" relationship where clause to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|\\Illuminate\\Database\\Eloquent\\Collection<int, \\Illuminate\\Database\\Eloquent\\Model>  $related
 * @param  string|null  $relationshipName
 * @param  string  $boolean
 * @return $this
 *
 * @throws \\InvalidArgumentException
 * @throws \\Illuminate\\Database\\Eloquent\\RelationNotFoundException
 */',
        'startLine' => 795,
        'endLine' => 826,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'orWhereAttachedTo' => 
      array (
        'name' => 'orWhereAttachedTo',
        'parameters' => 
        array (
          'related' => 
          array (
            'name' => 'related',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 837,
            'endLine' => 837,
            'startColumn' => 39,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relationshipName' => 
          array (
            'name' => 'relationshipName',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 837,
                'endLine' => 837,
                'startTokenPos' => 4295,
                'startFilePos' => 34060,
                'endTokenPos' => 4295,
                'endFilePos' => 34063,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 837,
            'endLine' => 837,
            'startColumn' => 49,
            'endColumn' => 72,
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
 * Add a "belongs to many" relationship with an "or where" clause to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $related
 * @param  string|null  $relationshipName
 * @return $this
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 837,
        'endLine' => 840,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withAggregate' => 
      array (
        'name' => 'withAggregate',
        'parameters' => 
        array (
          'relations' => 
          array (
            'name' => 'relations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 850,
            'endLine' => 850,
            'startColumn' => 35,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 850,
            'endLine' => 850,
            'startColumn' => 47,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'function' => 
          array (
            'name' => 'function',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 850,
                'endLine' => 850,
                'startTokenPos' => 4336,
                'startFilePos' => 34493,
                'endTokenPos' => 4336,
                'endFilePos' => 34496,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 850,
            'endLine' => 850,
            'startColumn' => 56,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include an aggregate value for a relationship.
 *
 * @param  mixed  $relations
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string|null  $function
 * @return $this
 */',
        'startLine' => 850,
        'endLine' => 939,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'getRelationHashedColumn' => 
      array (
        'name' => 'getRelationHashedColumn',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 948,
            'endLine' => 948,
            'startColumn' => 48,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 948,
            'endLine' => 948,
            'startColumn' => 57,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the relation hashed column name for the given column and relation.
 *
 * @param  string  $column
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>  $relation
 * @return string
 */',
        'startLine' => 948,
        'endLine' => 957,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withCount' => 
      array (
        'name' => 'withCount',
        'parameters' => 
        array (
          'relations' => 
          array (
            'name' => 'relations',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 965,
            'endLine' => 965,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to count the relations.
 *
 * @param  mixed  $relations
 * @return $this
 */',
        'startLine' => 965,
        'endLine' => 968,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withMax' => 
      array (
        'name' => 'withMax',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 977,
            'endLine' => 977,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 977,
            'endLine' => 977,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include the max of the relation\'s column.
 *
 * @param  string|array  $relation
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 977,
        'endLine' => 980,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withMin' => 
      array (
        'name' => 'withMin',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 989,
            'endLine' => 989,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 989,
            'endLine' => 989,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include the min of the relation\'s column.
 *
 * @param  string|array  $relation
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 989,
        'endLine' => 992,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withSum' => 
      array (
        'name' => 'withSum',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1001,
            'endLine' => 1001,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1001,
            'endLine' => 1001,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include the sum of the relation\'s column.
 *
 * @param  string|array  $relation
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1001,
        'endLine' => 1004,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withAvg' => 
      array (
        'name' => 'withAvg',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1013,
            'endLine' => 1013,
            'startColumn' => 29,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1013,
            'endLine' => 1013,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include the average of the relation\'s column.
 *
 * @param  string|array  $relation
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1013,
        'endLine' => 1016,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'withExists' => 
      array (
        'name' => 'withExists',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1024,
            'endLine' => 1024,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add subselect queries to include the existence of related models.
 *
 * @param  string|array  $relation
 * @return $this
 */',
        'startLine' => 1024,
        'endLine' => 1027,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'addHasWhere' => 
      array (
        'name' => 'addHasWhere',
        'parameters' => 
        array (
          'hasQuery' => 
          array (
            'name' => 'hasQuery',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Relations\\Relation',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 55,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 75,
            'endColumn' => 83,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 86,
            'endColumn' => 91,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 94,
            'endColumn' => 101,
            'parameterIndex' => 4,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add the "has" condition where clause to the query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $hasQuery
 * @param  \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>  $relation
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1039,
        'endLine' => 1046,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'mergeConstraintsFrom' => 
      array (
        'name' => 'mergeConstraintsFrom',
        'parameters' => 
        array (
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1054,
            'endLine' => 1054,
            'startColumn' => 42,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Merge the where constraints from another query to the current query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<*>  $from
 * @return $this
 */',
        'startLine' => 1054,
        'endLine' => 1073,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'requalifyWhereTables' => 
      array (
        'name' => 'requalifyWhereTables',
        'parameters' => 
        array (
          'wheres' => 
          array (
            'name' => 'wheres',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1083,
            'endLine' => 1083,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'from' => 
          array (
            'name' => 'from',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1083,
            'endLine' => 1083,
            'startColumn' => 60,
            'endColumn' => 71,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'to' => 
          array (
            'name' => 'to',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1083,
            'endLine' => 1083,
            'startColumn' => 74,
            'endColumn' => 83,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Updates the table name for any columns with a new qualified name.
 *
 * @param  array  $wheres
 * @param  string  $from
 * @param  string  $to
 * @return array
 */',
        'startLine' => 1083,
        'endLine' => 1092,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'addWhereCountQuery' => 
      array (
        'name' => 'addWhereCountQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Query\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1103,
            'endLine' => 1103,
            'startColumn' => 43,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => '\'>=\'',
              'attributes' => 
              array (
                'startLine' => 1103,
                'endLine' => 1103,
                'startTokenPos' => 5731,
                'startFilePos' => 44037,
                'endTokenPos' => 5731,
                'endFilePos' => 44040,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1103,
            'endLine' => 1103,
            'startColumn' => 64,
            'endColumn' => 79,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 1103,
                'endLine' => 1103,
                'startTokenPos' => 5738,
                'startFilePos' => 44052,
                'endTokenPos' => 5738,
                'endFilePos' => 44052,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1103,
            'endLine' => 1103,
            'startColumn' => 82,
            'endColumn' => 91,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'boolean' => 
          array (
            'name' => 'boolean',
            'default' => 
            array (
              'code' => '\'and\'',
              'attributes' => 
              array (
                'startLine' => 1103,
                'endLine' => 1103,
                'startTokenPos' => 5745,
                'startFilePos' => 44066,
                'endTokenPos' => 5745,
                'endFilePos' => 44070,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1103,
            'endLine' => 1103,
            'startColumn' => 94,
            'endColumn' => 109,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a sub-query count clause to this query.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder  $query
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1103,
        'endLine' => 1113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'getRelationWithoutConstraints' => 
      array (
        'name' => 'getRelationWithoutConstraints',
        'parameters' => 
        array (
          'relation' => 
          array (
            'name' => 'relation',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1121,
            'endLine' => 1121,
            'startColumn' => 54,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "has relation" base query instance.
 *
 * @param  string  $relation
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\Relation<*, *, *>
 */',
        'startLine' => 1121,
        'endLine' => 1126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
      'canUseExistsForExistenceCheck' => 
      array (
        'name' => 'canUseExistsForExistenceCheck',
        'parameters' => 
        array (
          'operator' => 
          array (
            'name' => 'operator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1135,
            'endLine' => 1135,
            'startColumn' => 54,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'count' => 
          array (
            'name' => 'count',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1135,
            'endLine' => 1135,
            'startColumn' => 65,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if we can run an "exists" query to optimize performance.
 *
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|int  $count
 * @return bool
 */',
        'startLine' => 1135,
        'endLine' => 1138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Concerns\\QueriesRelationships',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));