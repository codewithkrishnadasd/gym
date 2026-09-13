<?php declare(strict_types = 1);

// osfsl-/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Database/Query/Builder.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Query\Builder
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-71ad1b8f043d710ab17cef4cf1902311ec2c737df17b44d5dacb8a1fc97da5a7-8.4.4-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Query\\Builder',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Database/Query/Builder.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Query',
    'name' => 'Illuminate\\Database\\Query\\Builder',
    'shortName' => 'Builder',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 5006,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Database\\Query\\Builder',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses',
      1 => 'Illuminate\\Database\\Concerns\\BuildsQueries',
      2 => 'Illuminate\\Database\\Concerns\\ExplainsQueries',
      3 => 'Illuminate\\Support\\Traits\\ForwardsCalls',
      4 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'connection' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'connection',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The database connection instance.
 *
 * @var \\Illuminate\\Database\\ConnectionInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'grammar' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'grammar',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The database query grammar instance.
 *
 * @var \\Illuminate\\Database\\Query\\Grammars\\Grammar
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'processor' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'processor',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The database query post processor instance.
 *
 * @var \\Illuminate\\Database\\Query\\Processors\\Processor
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bindings' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'bindings',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'select\' => [], \'from\' => [], \'join\' => [], \'where\' => [], \'groupBy\' => [], \'having\' => [], \'order\' => [], \'union\' => [], \'unionOrder\' => []]',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 89,
            'startTokenPos' => 234,
            'startFilePos' => 2284,
            'endTokenPos' => 308,
            'endFilePos' => 2504,
          ),
        ),
        'docComment' => '/**
 * The current query value bindings.
 *
 * @var array{
 *     select: list<mixed>,
 *     from: list<mixed>,
 *     join: list<mixed>,
 *     where: list<mixed>,
 *     groupBy: list<mixed>,
 *     having: list<mixed>,
 *     order: list<mixed>,
 *     union: list<mixed>,
 *     unionOrder: list<mixed>,
 * }
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'aggregate' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'aggregate',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * An aggregate function and column to be run.
 *
 * @var array{
 *     function: string,
 *     columns: array<\\Illuminate\\Contracts\\Database\\Query\\Expression|string>
 * }|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'columns' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'columns',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The columns that should be returned.
 *
 * @var array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'distinct' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'distinct',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 115,
            'endLine' => 115,
            'startTokenPos' => 333,
            'startFilePos' => 3116,
            'endTokenPos' => 333,
            'endFilePos' => 3120,
          ),
        ),
        'docComment' => '/**
 * Indicates if the query returns distinct results.
 *
 * Occasionally contains the columns that should be distinct.
 *
 * @var bool|array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 115,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'from' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'from',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The table which the query is targeting.
 *
 * @var \\Illuminate\\Database\\Query\\Expression|string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 122,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'indexHint' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'indexHint',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The index hint for the query.
 *
 * @var \\Illuminate\\Database\\Query\\IndexHint|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'joins' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'joins',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The table joins for the query.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'wheres' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'wheres',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 143,
            'endLine' => 143,
            'startTokenPos' => 365,
            'startFilePos' => 3618,
            'endTokenPos' => 366,
            'endFilePos' => 3619,
          ),
        ),
        'docComment' => '/**
 * The where constraints for the query.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 143,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'groups' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'groups',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The groupings for the query.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 150,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'havings' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'havings',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The having constraints for the query.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 157,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'orders' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'orders',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The orderings for the query.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 164,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'limit' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'limit',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The maximum number of records to return.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 171,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'groupLimit' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'groupLimit',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The maximum number of records to return per group.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 178,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'offset' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'offset',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of records to skip.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'unions' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'unions',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The query union statements.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 192,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'unionLimit' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'unionLimit',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The maximum number of union records to return.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 199,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'unionOffset' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'unionOffset',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The number of union records to skip.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 206,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'unionOrders' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'unionOrders',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The orderings for the union query.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 213,
        'endLine' => 213,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'lock' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'lock',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates whether row locking is being used.
 *
 * @var string|bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 220,
        'endLine' => 220,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timeout' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'timeout',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The query execution timeout in seconds.
 *
 * @var int|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 227,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'beforeQueryCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'beforeQueryCallbacks',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 234,
            'endLine' => 234,
            'startTokenPos' => 461,
            'startFilePos' => 5125,
            'endTokenPos' => 462,
            'endFilePos' => 5126,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be invoked before the query is executed.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 234,
        'endLine' => 234,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'afterQueryCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'afterQueryCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 241,
            'endLine' => 241,
            'startTokenPos' => 473,
            'startFilePos' => 5293,
            'endTokenPos' => 474,
            'endFilePos' => 5294,
          ),
        ),
        'docComment' => '/**
 * The callbacks that should be invoked after retrieving data from the database.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 241,
        'endLine' => 241,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'operators' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'operators',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'=\', \'<\', \'>\', \'<=\', \'>=\', \'<>\', \'!=\', \'<=>\', \'like\', \'like binary\', \'not like\', \'ilike\', \'&\', \'|\', \'^\', \'<<\', \'>>\', \'&~\', \'is\', \'is not\', \'rlike\', \'not rlike\', \'regexp\', \'not regexp\', \'~\', \'~*\', \'!~\', \'!~*\', \'similar to\', \'not similar to\', \'not ilike\', \'~~*\', \'!~~*\']',
          'attributes' => 
          array (
            'startLine' => 248,
            'endLine' => 255,
            'startTokenPos' => 485,
            'startFilePos' => 5412,
            'endTokenPos' => 586,
            'endFilePos' => 5735,
          ),
        ),
        'docComment' => '/**
 * All of the available clause operators.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 248,
        'endLine' => 255,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bitwiseOperators' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'bitwiseOperators',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'&\', \'|\', \'^\', \'<<\', \'>>\', \'&~\']',
          'attributes' => 
          array (
            'startLine' => 262,
            'endLine' => 264,
            'startTokenPos' => 597,
            'startFilePos' => 5861,
            'endTokenPos' => 617,
            'endFilePos' => 5908,
          ),
        ),
        'docComment' => '/**
 * All of the available bitwise operators.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 262,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'useWritePdo' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'useWritePdo',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 271,
            'endLine' => 271,
            'startTokenPos' => 628,
            'startFilePos' => 6026,
            'endTokenPos' => 628,
            'endFilePos' => 6030,
          ),
        ),
        'docComment' => '/**
 * Whether to use write pdo for the select.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 271,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fetchUsing' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'name' => 'fetchUsing',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 278,
            'endLine' => 278,
            'startTokenPos' => 641,
            'startFilePos' => 6184,
            'endTokenPos' => 642,
            'endFilePos' => 6185,
          ),
        ),
        'docComment' => '/**
 * The custom arguments for the PDOStatement::fetchAll / fetch functions.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 278,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\ConnectionInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 284,
            'endLine' => 284,
            'startColumn' => 9,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'grammar' => 
          array (
            'name' => 'grammar',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 285,
                'endLine' => 285,
                'startTokenPos' => 666,
                'startFilePos' => 6351,
                'endTokenPos' => 666,
                'endFilePos' => 6354,
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
                      'name' => 'Illuminate\\Database\\Query\\Grammars\\Grammar',
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
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 9,
            'endColumn' => 32,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'processor' => 
          array (
            'name' => 'processor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 286,
                'endLine' => 286,
                'startTokenPos' => 676,
                'startFilePos' => 6389,
                'endTokenPos' => 676,
                'endFilePos' => 6392,
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
                      'name' => 'Illuminate\\Database\\Query\\Processors\\Processor',
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
            'startLine' => 286,
            'endLine' => 286,
            'startColumn' => 9,
            'endColumn' => 36,
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
 * Create a new query builder instance.
 */',
        'startLine' => 283,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'select' => 
      array (
        'name' => 'select',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 299,
                'endLine' => 299,
                'startTokenPos' => 742,
                'startFilePos' => 6746,
                'endTokenPos' => 744,
                'endFilePos' => 6750,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 299,
            'endLine' => 299,
            'startColumn' => 28,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the columns to be selected.
 *
 * @param  mixed  $columns
 * @return $this
 */',
        'startLine' => 299,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'selectSub' => 
      array (
        'name' => 'selectSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
            'startColumn' => 39,
            'endColumn' => 41,
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
 * Add a subselect expression to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 326,
        'endLine' => 333,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'selectExpression' => 
      array (
        'name' => 'selectExpression',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 38,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 51,
            'endColumn' => 53,
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
 * Add a select expression to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|literal-string  $expression
 * @param  string  $as
 * @return $this
 */',
        'startLine' => 342,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'selectRaw' => 
      array (
        'name' => 'selectRaw',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 355,
            'endLine' => 355,
            'startColumn' => 31,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 355,
                'endLine' => 355,
                'startTokenPos' => 997,
                'startFilePos' => 8294,
                'endTokenPos' => 998,
                'endFilePos' => 8295,
              ),
            ),
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
            'startLine' => 355,
            'endLine' => 355,
            'startColumn' => 44,
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
 * Add a new "raw" select expression to the query.
 *
 * @param  literal-string  $expression
 * @return $this
 */',
        'startLine' => 355,
        'endLine' => 364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'fromSub' => 
      array (
        'name' => 'fromSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 375,
            'endLine' => 375,
            'startColumn' => 29,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 375,
            'endLine' => 375,
            'startColumn' => 37,
            'endColumn' => 39,
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
 * Makes "from" fetch from a subquery.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 375,
        'endLine' => 380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'fromRaw' => 
      array (
        'name' => 'fromRaw',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 389,
            'endLine' => 389,
            'startColumn' => 29,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 389,
                'endLine' => 389,
                'startTokenPos' => 1120,
                'startFilePos' => 9175,
                'endTokenPos' => 1121,
                'endFilePos' => 9176,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 389,
            'endLine' => 389,
            'startColumn' => 42,
            'endColumn' => 55,
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
 * Add a raw "from" clause to the query.
 *
 * @param  literal-string  $expression
 * @param  mixed  $bindings
 * @return $this
 */',
        'startLine' => 389,
        'endLine' => 396,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'createSub' => 
      array (
        'name' => 'createSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 404,
            'endLine' => 404,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Creates a subquery and parse it.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @return array
 */',
        'startLine' => 404,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'parseSub' => 
      array (
        'name' => 'parseSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 426,
            'endLine' => 426,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Parse the subquery into SQL and bindings.
 *
 * @param  mixed  $query
 * @return array
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 426,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'prependDatabaseNameIfCrossDatabaseQuery' => 
      array (
        'name' => 'prependDatabaseNameIfCrossDatabaseQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 447,
            'endLine' => 447,
            'startColumn' => 64,
            'endColumn' => 69,
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
 * Prepend the database name if the given query is on another database.
 *
 * @param  mixed  $query
 * @return mixed
 */',
        'startLine' => 447,
        'endLine' => 459,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addSelect' => 
      array (
        'name' => 'addSelect',
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
            'startLine' => 467,
            'endLine' => 467,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * Add a new select column to the query.
 *
 * @param  mixed  $column
 * @return $this
 */',
        'startLine' => 467,
        'endLine' => 488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'selectVectorDistance' => 
      array (
        'name' => 'selectVectorDistance',
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
            'startLine' => 500,
            'endLine' => 500,
            'startColumn' => 42,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'vector' => 
          array (
            'name' => 'vector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 500,
            'endLine' => 500,
            'startColumn' => 51,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 500,
                'endLine' => 500,
                'startTokenPos' => 1640,
                'startFilePos' => 12559,
                'endTokenPos' => 1640,
                'endFilePos' => 12562,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 500,
            'endLine' => 500,
            'startColumn' => 60,
            'endColumn' => 69,
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
 * Add a vector-similarity selection to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Support\\Collection<int, float>|\\Illuminate\\Contracts\\Support\\Arrayable|array<int, float>|string  $vector
 * @param  string|null  $as
 * @return $this
 *
 * @throws \\JsonException
 */',
        'startLine' => 500,
        'endLine' => 523,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'distinct' => 
      array (
        'name' => 'distinct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Force the query to only return distinct results.
 *
 * @return $this
 */',
        'startLine' => 530,
        'endLine' => 541,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 550,
            'endLine' => 550,
            'startColumn' => 26,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 550,
                'endLine' => 550,
                'startTokenPos' => 1895,
                'startFilePos' => 13957,
                'endTokenPos' => 1895,
                'endFilePos' => 13960,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 550,
            'endLine' => 550,
            'startColumn' => 34,
            'endColumn' => 43,
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
 * Set the table which the query is targeting.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  string|null  $as
 * @return $this
 */',
        'startLine' => 550,
        'endLine' => 559,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'useIndex' => 
      array (
        'name' => 'useIndex',
        'parameters' => 
        array (
          'index' => 
          array (
            'name' => 'index',
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
            'startColumn' => 30,
            'endColumn' => 35,
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
 * Add an index hint to suggest a query index.
 *
 * @param  string  $index
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
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forceIndex' => 
      array (
        'name' => 'forceIndex',
        'parameters' => 
        array (
          'index' => 
          array (
            'name' => 'index',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 580,
            'endLine' => 580,
            'startColumn' => 32,
            'endColumn' => 37,
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
 * Add an index hint to force a query index.
 *
 * @param  string  $index
 * @return $this
 */',
        'startLine' => 580,
        'endLine' => 585,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'ignoreIndex' => 
      array (
        'name' => 'ignoreIndex',
        'parameters' => 
        array (
          'index' => 
          array (
            'name' => 'index',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 593,
            'endLine' => 593,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Add an index hint to ignore a query index.
 *
 * @param  string  $index
 * @return $this
 */',
        'startLine' => 593,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'join' => 
      array (
        'name' => 'join',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 26,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 34,
            'endColumn' => 39,
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
                'startLine' => 611,
                'endLine' => 611,
                'startTokenPos' => 2089,
                'startFilePos' => 15413,
                'endTokenPos' => 2089,
                'endFilePos' => 15416,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 42,
            'endColumn' => 57,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 611,
                'endLine' => 611,
                'startTokenPos' => 2096,
                'startFilePos' => 15429,
                'endTokenPos' => 2096,
                'endFilePos' => 15432,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 60,
            'endColumn' => 73,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'inner\'',
              'attributes' => 
              array (
                'startLine' => 611,
                'endLine' => 611,
                'startTokenPos' => 2103,
                'startFilePos' => 15443,
                'endTokenPos' => 2103,
                'endFilePos' => 15449,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 76,
            'endColumn' => 90,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'where' => 
          array (
            'name' => 'where',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 611,
                'endLine' => 611,
                'startTokenPos' => 2110,
                'startFilePos' => 15461,
                'endTokenPos' => 2110,
                'endFilePos' => 15465,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 611,
            'endLine' => 611,
            'startColumn' => 93,
            'endColumn' => 106,
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
 * Add a "join" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @param  string  $type
 * @param  bool  $where
 * @return $this
 */',
        'startLine' => 611,
        'endLine' => 638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'joinWhere' => 
      array (
        'name' => 'joinWhere',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 39,
            'endColumn' => 44,
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
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 47,
            'endColumn' => 55,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 58,
            'endColumn' => 64,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'inner\'',
              'attributes' => 
              array (
                'startLine' => 650,
                'endLine' => 650,
                'startTokenPos' => 2280,
                'startFilePos' => 16931,
                'endTokenPos' => 2280,
                'endFilePos' => 16937,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 650,
            'endLine' => 650,
            'startColumn' => 67,
            'endColumn' => 81,
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
 * Add a "join where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $second
 * @param  string  $type
 * @return $this
 */',
        'startLine' => 650,
        'endLine' => 653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'joinSub' => 
      array (
        'name' => 'joinSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 29,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 37,
            'endColumn' => 39,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 42,
            'endColumn' => 47,
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
                'startLine' => 669,
                'endLine' => 669,
                'startTokenPos' => 2333,
                'startFilePos' => 17641,
                'endTokenPos' => 2333,
                'endFilePos' => 17644,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 669,
                'endLine' => 669,
                'startTokenPos' => 2340,
                'startFilePos' => 17657,
                'endTokenPos' => 2340,
                'endFilePos' => 17660,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 68,
            'endColumn' => 81,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'inner\'',
              'attributes' => 
              array (
                'startLine' => 669,
                'endLine' => 669,
                'startTokenPos' => 2347,
                'startFilePos' => 17671,
                'endTokenPos' => 2347,
                'endFilePos' => 17677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 84,
            'endColumn' => 98,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'where' => 
          array (
            'name' => 'where',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 669,
                'endLine' => 669,
                'startTokenPos' => 2354,
                'startFilePos' => 17689,
                'endTokenPos' => 2354,
                'endFilePos' => 17693,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 669,
            'endLine' => 669,
            'startColumn' => 101,
            'endColumn' => 114,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "subquery join" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @param  string  $type
 * @param  bool  $where
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 669,
        'endLine' => 678,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'joinLateral' => 
      array (
        'name' => 'joinLateral',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 686,
            'endLine' => 686,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
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
            'startLine' => 686,
            'endLine' => 686,
            'startColumn' => 41,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'inner\'',
              'attributes' => 
              array (
                'startLine' => 686,
                'endLine' => 686,
                'startTokenPos' => 2461,
                'startFilePos' => 18262,
                'endTokenPos' => 2461,
                'endFilePos' => 18268,
              ),
            ),
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
            'startLine' => 686,
            'endLine' => 686,
            'startColumn' => 53,
            'endColumn' => 74,
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
 * Add a "lateral join" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @return $this
 */',
        'startLine' => 686,
        'endLine' => 697,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'leftJoinLateral' => 
      array (
        'name' => 'leftJoinLateral',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 705,
            'endLine' => 705,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
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
            'startLine' => 705,
            'endLine' => 705,
            'startColumn' => 45,
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
 * Add a lateral left join to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @return $this
 */',
        'startLine' => 705,
        'endLine' => 708,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'leftJoin' => 
      array (
        'name' => 'leftJoin',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
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
            'startColumn' => 30,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
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
            'startColumn' => 38,
            'endColumn' => 43,
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
                'startLine' => 719,
                'endLine' => 719,
                'startTokenPos' => 2602,
                'startFilePos' => 19336,
                'endTokenPos' => 2602,
                'endFilePos' => 19339,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 719,
            'endLine' => 719,
            'startColumn' => 46,
            'endColumn' => 61,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 719,
                'endLine' => 719,
                'startTokenPos' => 2609,
                'startFilePos' => 19352,
                'endTokenPos' => 2609,
                'endFilePos' => 19355,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 719,
            'endLine' => 719,
            'startColumn' => 64,
            'endColumn' => 77,
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
 * Add a left join to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
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
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'leftJoinWhere' => 
      array (
        'name' => 'leftJoinWhere',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 733,
            'endLine' => 733,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 733,
            'endLine' => 733,
            'startColumn' => 43,
            'endColumn' => 48,
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
            'startLine' => 733,
            'endLine' => 733,
            'startColumn' => 51,
            'endColumn' => 59,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 733,
            'endLine' => 733,
            'startColumn' => 62,
            'endColumn' => 68,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "join where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 733,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'leftJoinSub' => 
      array (
        'name' => 'leftJoinSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 46,
            'endColumn' => 51,
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
                'startLine' => 748,
                'endLine' => 748,
                'startTokenPos' => 2705,
                'startFilePos' => 20482,
                'endTokenPos' => 2705,
                'endFilePos' => 20485,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 748,
                'endLine' => 748,
                'startTokenPos' => 2712,
                'startFilePos' => 20498,
                'endTokenPos' => 2712,
                'endFilePos' => 20501,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 748,
            'endLine' => 748,
            'startColumn' => 72,
            'endColumn' => 85,
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
 * Add a subquery left join to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 748,
        'endLine' => 751,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'rightJoin' => 
      array (
        'name' => 'rightJoin',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 39,
            'endColumn' => 44,
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
                'startLine' => 762,
                'endLine' => 762,
                'startTokenPos' => 2762,
                'startFilePos' => 20976,
                'endTokenPos' => 2762,
                'endFilePos' => 20979,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 47,
            'endColumn' => 62,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 762,
                'endLine' => 762,
                'startTokenPos' => 2769,
                'startFilePos' => 20992,
                'endTokenPos' => 2769,
                'endFilePos' => 20995,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 762,
            'endLine' => 762,
            'startColumn' => 65,
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
 * Add a right join to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 762,
        'endLine' => 765,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'rightJoinWhere' => 
      array (
        'name' => 'rightJoinWhere',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 776,
            'endLine' => 776,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 776,
            'endLine' => 776,
            'startColumn' => 44,
            'endColumn' => 49,
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
            'startLine' => 776,
            'endLine' => 776,
            'startColumn' => 52,
            'endColumn' => 60,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 776,
            'endLine' => 776,
            'startColumn' => 63,
            'endColumn' => 69,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "right join where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $second
 * @return $this
 */',
        'startLine' => 776,
        'endLine' => 779,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'rightJoinSub' => 
      array (
        'name' => 'rightJoinSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 791,
            'endLine' => 791,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 791,
            'endLine' => 791,
            'startColumn' => 42,
            'endColumn' => 44,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 791,
            'endLine' => 791,
            'startColumn' => 47,
            'endColumn' => 52,
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
                'startLine' => 791,
                'endLine' => 791,
                'startTokenPos' => 2865,
                'startFilePos' => 22128,
                'endTokenPos' => 2865,
                'endFilePos' => 22131,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 791,
            'endLine' => 791,
            'startColumn' => 55,
            'endColumn' => 70,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 791,
                'endLine' => 791,
                'startTokenPos' => 2872,
                'startFilePos' => 22144,
                'endTokenPos' => 2872,
                'endFilePos' => 22147,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 791,
            'endLine' => 791,
            'startColumn' => 73,
            'endColumn' => 86,
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
 * Add a subquery right join to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 791,
        'endLine' => 794,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'crossJoin' => 
      array (
        'name' => 'crossJoin',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 805,
            'endLine' => 805,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 805,
                'endLine' => 805,
                'startTokenPos' => 2919,
                'startFilePos' => 22674,
                'endTokenPos' => 2919,
                'endFilePos' => 22677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 805,
            'endLine' => 805,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'operator' => 
          array (
            'name' => 'operator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 805,
                'endLine' => 805,
                'startTokenPos' => 2926,
                'startFilePos' => 22692,
                'endTokenPos' => 2926,
                'endFilePos' => 22695,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 805,
            'endLine' => 805,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 805,
                'endLine' => 805,
                'startTokenPos' => 2933,
                'startFilePos' => 22708,
                'endTokenPos' => 2933,
                'endFilePos' => 22711,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 805,
            'endLine' => 805,
            'startColumn' => 72,
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
 * Add a "cross join" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 805,
        'endLine' => 814,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'crossJoinSub' => 
      array (
        'name' => 'crossJoinSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 823,
            'endLine' => 823,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 823,
            'endLine' => 823,
            'startColumn' => 42,
            'endColumn' => 44,
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
 * Add a subquery cross join to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @return $this
 */',
        'startLine' => 823,
        'endLine' => 834,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'straightJoin' => 
      array (
        'name' => 'straightJoin',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 845,
            'endLine' => 845,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 845,
            'endLine' => 845,
            'startColumn' => 42,
            'endColumn' => 47,
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
                'startLine' => 845,
                'endLine' => 845,
                'startTokenPos' => 3115,
                'startFilePos' => 23901,
                'endTokenPos' => 3115,
                'endFilePos' => 23904,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 845,
            'endLine' => 845,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 845,
                'endLine' => 845,
                'startTokenPos' => 3122,
                'startFilePos' => 23917,
                'endTokenPos' => 3122,
                'endFilePos' => 23920,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 845,
            'endLine' => 845,
            'startColumn' => 68,
            'endColumn' => 81,
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
 * Add a straight join to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 845,
        'endLine' => 848,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'straightJoinWhere' => 
      array (
        'name' => 'straightJoinWhere',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 859,
            'endLine' => 859,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 859,
            'endLine' => 859,
            'startColumn' => 47,
            'endColumn' => 52,
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
            'startLine' => 859,
            'endLine' => 859,
            'startColumn' => 55,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 859,
            'endLine' => 859,
            'startColumn' => 66,
            'endColumn' => 72,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "straight join where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $second
 * @return $this
 */',
        'startLine' => 859,
        'endLine' => 862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'straightJoinSub' => 
      array (
        'name' => 'straightJoinSub',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 37,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'as' => 
          array (
            'name' => 'as',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 45,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 50,
            'endColumn' => 55,
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
                'startLine' => 874,
                'endLine' => 874,
                'startTokenPos' => 3218,
                'startFilePos' => 25081,
                'endTokenPos' => 3218,
                'endFilePos' => 25084,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 58,
            'endColumn' => 73,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 874,
                'endLine' => 874,
                'startTokenPos' => 3225,
                'startFilePos' => 25097,
                'endTokenPos' => 3225,
                'endFilePos' => 25100,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 874,
            'endLine' => 874,
            'startColumn' => 76,
            'endColumn' => 89,
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
 * Add a subquery straight join to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @param  string  $as
 * @param  \\Closure|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $first
 * @param  string|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $second
 * @return $this
 */',
        'startLine' => 874,
        'endLine' => 877,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'newJoinClause' => 
      array (
        'name' => 'newJoinClause',
        'parameters' => 
        array (
          'parentQuery' => 
          array (
            'name' => 'parentQuery',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 38,
            'endColumn' => 54,
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
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 57,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 886,
            'endLine' => 886,
            'startColumn' => 64,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new "join" clause.
 *
 * @param  string  $type
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @return \\Illuminate\\Database\\Query\\JoinClause
 */',
        'startLine' => 886,
        'endLine' => 889,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'newJoinLateralClause' => 
      array (
        'name' => 'newJoinLateralClause',
        'parameters' => 
        array (
          'parentQuery' => 
          array (
            'name' => 'parentQuery',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 898,
            'endLine' => 898,
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
            'startLine' => 898,
            'endLine' => 898,
            'startColumn' => 64,
            'endColumn' => 68,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 898,
            'endLine' => 898,
            'startColumn' => 71,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new "join lateral" clause.
 *
 * @param  string  $type
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $table
 * @return \\Illuminate\\Database\\Query\\JoinLateralClause
 */',
        'startLine' => 898,
        'endLine' => 901,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'mergeWheres' => 
      array (
        'name' => 'mergeWheres',
        'parameters' => 
        array (
          'wheres' => 
          array (
            'name' => 'wheres',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 910,
            'endLine' => 910,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 910,
            'endLine' => 910,
            'startColumn' => 42,
            'endColumn' => 50,
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
 * Merge an array of "where" clauses and bindings.
 *
 * @param  array  $wheres
 * @param  array  $bindings
 * @return $this
 */',
        'startLine' => 910,
        'endLine' => 919,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'where' => 
      array (
        'name' => 'where',
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
            'startLine' => 930,
            'endLine' => 930,
            'startColumn' => 27,
            'endColumn' => 33,
            'parameterIndex' => 0,
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
                'startLine' => 930,
                'endLine' => 930,
                'startTokenPos' => 3422,
                'startFilePos' => 26726,
                'endTokenPos' => 3422,
                'endFilePos' => 26729,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 930,
            'endLine' => 930,
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 1,
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
                'startLine' => 930,
                'endLine' => 930,
                'startTokenPos' => 3429,
                'startFilePos' => 26741,
                'endTokenPos' => 3429,
                'endFilePos' => 26744,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 930,
            'endLine' => 930,
            'startColumn' => 54,
            'endColumn' => 66,
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
                'startLine' => 930,
                'endLine' => 930,
                'startTokenPos' => 3436,
                'startFilePos' => 26758,
                'endTokenPos' => 3436,
                'endFilePos' => 26762,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 930,
            'endLine' => 930,
            'startColumn' => 69,
            'endColumn' => 84,
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
 * Add a basic "where" clause to the query.
 *
 * @param  \\Closure|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 930,
        'endLine' => 1029,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addArrayOfWheres' => 
      array (
        'name' => 'addArrayOfWheres',
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
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
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
            'startColumn' => 50,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'method' => 
          array (
            'name' => 'method',
            'default' => 
            array (
              'code' => '\'where\'',
              'attributes' => 
              array (
                'startLine' => 1039,
                'endLine' => 1039,
                'startTokenPos' => 4078,
                'startFilePos' => 31401,
                'endTokenPos' => 4078,
                'endFilePos' => 31407,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1039,
            'endLine' => 1039,
            'startColumn' => 60,
            'endColumn' => 76,
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
 * Add an array of "where" clauses to the query.
 *
 * @param  array  $column
 * @param  string  $boolean
 * @param  string  $method
 * @return $this
 */',
        'startLine' => 1039,
        'endLine' => 1050,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'prepareValueAndOperator' => 
      array (
        'name' => 'prepareValueAndOperator',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1062,
            'endLine' => 1062,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 0,
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
            'startLine' => 1062,
            'endLine' => 1062,
            'startColumn' => 53,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'useDefault' => 
          array (
            'name' => 'useDefault',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1062,
                'endLine' => 1062,
                'startTokenPos' => 4219,
                'startFilePos' => 32173,
                'endTokenPos' => 4219,
                'endFilePos' => 32177,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1062,
            'endLine' => 1062,
            'startColumn' => 64,
            'endColumn' => 82,
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
 * Prepare the value and operator for a where clause.
 *
 * @param  string  $value
 * @param  string  $operator
 * @param  bool  $useDefault
 * @return array
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 1062,
        'endLine' => 1071,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'invalidOperatorAndValue' => 
      array (
        'name' => 'invalidOperatorAndValue',
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
            'startLine' => 1082,
            'endLine' => 1082,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1082,
            'endLine' => 1082,
            'startColumn' => 59,
            'endColumn' => 64,
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
 * Determine if the given operator and value combination is legal.
 *
 * Prevents using Null values with invalid operators.
 *
 * @param  string  $operator
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 1082,
        'endLine' => 1086,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'invalidOperator' => 
      array (
        'name' => 'invalidOperator',
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
            'startLine' => 1094,
            'endLine' => 1094,
            'startColumn' => 40,
            'endColumn' => 48,
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
 * Determine if the given operator is supported.
 *
 * @param  string  $operator
 * @return bool
 */',
        'startLine' => 1094,
        'endLine' => 1098,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'isBitwiseOperator' => 
      array (
        'name' => 'isBitwiseOperator',
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
            'startLine' => 1106,
            'endLine' => 1106,
            'startColumn' => 42,
            'endColumn' => 50,
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
 * Determine if the operator is a bitwise operator.
 *
 * @param  string  $operator
 * @return bool
 */',
        'startLine' => 1106,
        'endLine' => 1110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhere' => 
      array (
        'name' => 'orWhere',
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
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
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
                'startLine' => 1120,
                'endLine' => 1120,
                'startTokenPos' => 4487,
                'startFilePos' => 33972,
                'endTokenPos' => 4487,
                'endFilePos' => 33975,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 38,
            'endColumn' => 53,
            'parameterIndex' => 1,
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
                'startLine' => 1120,
                'endLine' => 1120,
                'startTokenPos' => 4494,
                'startFilePos' => 33987,
                'endTokenPos' => 4494,
                'endFilePos' => 33990,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 56,
            'endColumn' => 68,
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
 * Add an "or where" clause to the query.
 *
 * @param  \\Closure|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1120,
        'endLine' => 1127,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNot' => 
      array (
        'name' => 'whereNot',
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
            'startLine' => 1138,
            'endLine' => 1138,
            'startColumn' => 30,
            'endColumn' => 36,
            'parameterIndex' => 0,
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
                'startLine' => 1138,
                'endLine' => 1138,
                'startTokenPos' => 4566,
                'startFilePos' => 34529,
                'endTokenPos' => 4566,
                'endFilePos' => 34532,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1138,
            'endLine' => 1138,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 1,
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
                'startLine' => 1138,
                'endLine' => 1138,
                'startTokenPos' => 4573,
                'startFilePos' => 34544,
                'endTokenPos' => 4573,
                'endFilePos' => 34547,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1138,
            'endLine' => 1138,
            'startColumn' => 57,
            'endColumn' => 69,
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
                'startLine' => 1138,
                'endLine' => 1138,
                'startTokenPos' => 4580,
                'startFilePos' => 34561,
                'endTokenPos' => 4580,
                'endFilePos' => 34565,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1138,
            'endLine' => 1138,
            'startColumn' => 72,
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
 * Add a basic "where not" clause to the query.
 *
 * @param  \\Closure|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1138,
        'endLine' => 1147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNot' => 
      array (
        'name' => 'orWhereNot',
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
            'startLine' => 1157,
            'endLine' => 1157,
            'startColumn' => 32,
            'endColumn' => 38,
            'parameterIndex' => 0,
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
                'startLine' => 1157,
                'endLine' => 1157,
                'startTokenPos' => 4691,
                'startFilePos' => 35203,
                'endTokenPos' => 4691,
                'endFilePos' => 35206,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1157,
            'endLine' => 1157,
            'startColumn' => 41,
            'endColumn' => 56,
            'parameterIndex' => 1,
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
                'startLine' => 1157,
                'endLine' => 1157,
                'startTokenPos' => 4698,
                'startFilePos' => 35218,
                'endTokenPos' => 4698,
                'endFilePos' => 35221,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1157,
            'endLine' => 1157,
            'startColumn' => 59,
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
 * Add an "or where not" clause to the query.
 *
 * @param  \\Closure|string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1157,
        'endLine' => 1160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereColumn' => 
      array (
        'name' => 'whereColumn',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
            'startColumn' => 33,
            'endColumn' => 38,
            'parameterIndex' => 0,
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
                'startLine' => 1171,
                'endLine' => 1171,
                'startTokenPos' => 4739,
                'startFilePos' => 35658,
                'endTokenPos' => 4739,
                'endFilePos' => 35661,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
            'startColumn' => 41,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1171,
                'endLine' => 1171,
                'startTokenPos' => 4746,
                'startFilePos' => 35674,
                'endTokenPos' => 4746,
                'endFilePos' => 35677,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
            'startColumn' => 59,
            'endColumn' => 72,
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
                'startLine' => 1171,
                'endLine' => 1171,
                'startTokenPos' => 4753,
                'startFilePos' => 35691,
                'endTokenPos' => 4753,
                'endFilePos' => 35695,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
            'startColumn' => 75,
            'endColumn' => 90,
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
 * Add a "where" clause comparing two columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|array  $first
 * @param  string|null  $operator
 * @param  string|null  $second
 * @param  string|null  $boolean
 * @return $this
 */',
        'startLine' => 1171,
        'endLine' => 1197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereColumn' => 
      array (
        'name' => 'orWhereColumn',
        'parameters' => 
        array (
          'first' => 
          array (
            'name' => 'first',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1207,
            'endLine' => 1207,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
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
                'startLine' => 1207,
                'endLine' => 1207,
                'startTokenPos' => 4914,
                'startFilePos' => 37218,
                'endTokenPos' => 4914,
                'endFilePos' => 37221,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1207,
            'endLine' => 1207,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1207,
                'endLine' => 1207,
                'startTokenPos' => 4921,
                'startFilePos' => 37234,
                'endTokenPos' => 4921,
                'endFilePos' => 37237,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1207,
            'endLine' => 1207,
            'startColumn' => 61,
            'endColumn' => 74,
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
 * Add an "or where" clause comparing two columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string|array  $first
 * @param  string|null  $operator
 * @param  string|null  $second
 * @return $this
 */',
        'startLine' => 1207,
        'endLine' => 1210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereVectorSimilarTo' => 
      array (
        'name' => 'whereVectorSimilarTo',
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
            'startLine' => 1221,
            'endLine' => 1221,
            'startColumn' => 42,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'vector' => 
          array (
            'name' => 'vector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1221,
            'endLine' => 1221,
            'startColumn' => 51,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'minSimilarity' => 
          array (
            'name' => 'minSimilarity',
            'default' => 
            array (
              'code' => '0.6',
              'attributes' => 
              array (
                'startLine' => 1221,
                'endLine' => 1221,
                'startTokenPos' => 4965,
                'startFilePos' => 37884,
                'endTokenPos' => 4965,
                'endFilePos' => 37886,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1221,
            'endLine' => 1221,
            'startColumn' => 60,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'order' => 
          array (
            'name' => 'order',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1221,
                'endLine' => 1221,
                'startTokenPos' => 4972,
                'startFilePos' => 37898,
                'endTokenPos' => 4972,
                'endFilePos' => 37901,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1221,
            'endLine' => 1221,
            'startColumn' => 82,
            'endColumn' => 94,
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
 * Add a vector similarity clause to the query, filtering by minimum similarity and ordering by similarity.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Support\\Collection<int, float>|\\Illuminate\\Contracts\\Support\\Arrayable|array<int, float>|string  $vector
 * @param  float  $minSimilarity  A value between 0.0 and 1.0, where 1.0 is identical.
 * @param  bool  $order
 * @return $this
 */',
        'startLine' => 1221,
        'endLine' => 1234,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereVectorDistanceLessThan' => 
      array (
        'name' => 'whereVectorDistanceLessThan',
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
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 49,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'vector' => 
          array (
            'name' => 'vector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 58,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxDistance' => 
          array (
            'name' => 'maxDistance',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 67,
            'endColumn' => 78,
            'parameterIndex' => 2,
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
                'startLine' => 1247,
                'endLine' => 1247,
                'startTokenPos' => 5079,
                'startFilePos' => 38744,
                'endTokenPos' => 5079,
                'endFilePos' => 38748,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1247,
            'endLine' => 1247,
            'startColumn' => 81,
            'endColumn' => 96,
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
 * Add a vector distance "where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Support\\Collection<int, float>|\\Illuminate\\Contracts\\Support\\Arrayable|array<int, float>|string  $vector
 * @param  float  $maxDistance
 * @param  string  $boolean
 * @return $this
 *
 * @throws \\JsonException
 */',
        'startLine' => 1247,
        'endLine' => 1268,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereVectorDistanceLessThan' => 
      array (
        'name' => 'orWhereVectorDistanceLessThan',
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
            'startLine' => 1278,
            'endLine' => 1278,
            'startColumn' => 51,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'vector' => 
          array (
            'name' => 'vector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1278,
            'endLine' => 1278,
            'startColumn' => 60,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxDistance' => 
          array (
            'name' => 'maxDistance',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1278,
            'endLine' => 1278,
            'startColumn' => 69,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a vector distance "or where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Support\\Collection<int, float>|\\Illuminate\\Contracts\\Support\\Arrayable|array<int, float>|string  $vector
 * @param  float  $maxDistance
 * @return $this
 */',
        'startLine' => 1278,
        'endLine' => 1281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereRaw' => 
      array (
        'name' => 'whereRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1291,
            'endLine' => 1291,
            'startColumn' => 30,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1291,
                'endLine' => 1291,
                'startTokenPos' => 5250,
                'startFilePos' => 40187,
                'endTokenPos' => 5251,
                'endFilePos' => 40188,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1291,
            'endLine' => 1291,
            'startColumn' => 36,
            'endColumn' => 49,
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
                'startLine' => 1291,
                'endLine' => 1291,
                'startTokenPos' => 5258,
                'startFilePos' => 40202,
                'endTokenPos' => 5258,
                'endFilePos' => 40206,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1291,
            'endLine' => 1291,
            'startColumn' => 52,
            'endColumn' => 67,
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
 * Add a raw "where" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|literal-string  $sql
 * @param  mixed  $bindings
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1291,
        'endLine' => 1298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereRaw' => 
      array (
        'name' => 'orWhereRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1307,
            'endLine' => 1307,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1307,
                'endLine' => 1307,
                'startTokenPos' => 5329,
                'startFilePos' => 40594,
                'endTokenPos' => 5330,
                'endFilePos' => 40595,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1307,
            'endLine' => 1307,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * Add a raw "or where" clause to the query.
 *
 * @param  literal-string  $sql
 * @param  mixed  $bindings
 * @return $this
 */',
        'startLine' => 1307,
        'endLine' => 1310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereBinary' => 
      array (
        'name' => 'whereBinary',
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
            'startLine' => 1321,
            'endLine' => 1321,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1321,
            'endLine' => 1321,
            'startColumn' => 42,
            'endColumn' => 47,
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
                'startLine' => 1321,
                'endLine' => 1321,
                'startTokenPos' => 5371,
                'startFilePos' => 40986,
                'endTokenPos' => 5371,
                'endFilePos' => 40990,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1321,
            'endLine' => 1321,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1321,
                'endLine' => 1321,
                'startTokenPos' => 5378,
                'startFilePos' => 41000,
                'endTokenPos' => 5378,
                'endFilePos' => 41004,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1321,
            'endLine' => 1321,
            'startColumn' => 68,
            'endColumn' => 79,
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
 * Add a "where binary" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1321,
        'endLine' => 1330,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereBinary' => 
      array (
        'name' => 'orWhereBinary',
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
            'startLine' => 1339,
            'endLine' => 1339,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1339,
            'endLine' => 1339,
            'startColumn' => 44,
            'endColumn' => 49,
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
 * Add an "or where binary" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 1339,
        'endLine' => 1342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotBinary' => 
      array (
        'name' => 'whereNotBinary',
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
            'startLine' => 1352,
            'endLine' => 1352,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1352,
            'endLine' => 1352,
            'startColumn' => 45,
            'endColumn' => 50,
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
                'startLine' => 1352,
                'endLine' => 1352,
                'startTokenPos' => 5483,
                'startFilePos' => 41815,
                'endTokenPos' => 5483,
                'endFilePos' => 41819,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1352,
            'endLine' => 1352,
            'startColumn' => 53,
            'endColumn' => 68,
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
 * Add a "where not binary" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1352,
        'endLine' => 1355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotBinary' => 
      array (
        'name' => 'orWhereNotBinary',
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
            'startLine' => 1364,
            'endLine' => 1364,
            'startColumn' => 38,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1364,
            'endLine' => 1364,
            'startColumn' => 47,
            'endColumn' => 52,
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
 * Add an "or where not binary" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 1364,
        'endLine' => 1367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereLike' => 
      array (
        'name' => 'whereLike',
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
            'startLine' => 1379,
            'endLine' => 1379,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1379,
            'endLine' => 1379,
            'startColumn' => 40,
            'endColumn' => 45,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'caseSensitive' => 
          array (
            'name' => 'caseSensitive',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1379,
                'endLine' => 1379,
                'startTokenPos' => 5561,
                'startFilePos' => 42599,
                'endTokenPos' => 5561,
                'endFilePos' => 42603,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1379,
            'endLine' => 1379,
            'startColumn' => 48,
            'endColumn' => 69,
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
                'startLine' => 1379,
                'endLine' => 1379,
                'startTokenPos' => 5568,
                'startFilePos' => 42617,
                'endTokenPos' => 5568,
                'endFilePos' => 42621,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1379,
            'endLine' => 1379,
            'startColumn' => 72,
            'endColumn' => 87,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1379,
                'endLine' => 1379,
                'startTokenPos' => 5575,
                'startFilePos' => 42631,
                'endTokenPos' => 5575,
                'endFilePos' => 42635,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1379,
            'endLine' => 1379,
            'startColumn' => 90,
            'endColumn' => 101,
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
 * Add a "where like" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  bool  $caseSensitive
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1379,
        'endLine' => 1392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereLike' => 
      array (
        'name' => 'orWhereLike',
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
            'startLine' => 1402,
            'endLine' => 1402,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1402,
            'endLine' => 1402,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'caseSensitive' => 
          array (
            'name' => 'caseSensitive',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1402,
                'endLine' => 1402,
                'startTokenPos' => 5707,
                'startFilePos' => 43368,
                'endTokenPos' => 5707,
                'endFilePos' => 43372,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1402,
            'endLine' => 1402,
            'startColumn' => 50,
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
 * Add an "or where like" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  bool  $caseSensitive
 * @return $this
 */',
        'startLine' => 1402,
        'endLine' => 1405,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotLike' => 
      array (
        'name' => 'whereNotLike',
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
            'startLine' => 1416,
            'endLine' => 1416,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1416,
            'endLine' => 1416,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'caseSensitive' => 
          array (
            'name' => 'caseSensitive',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1416,
                'endLine' => 1416,
                'startTokenPos' => 5754,
                'startFilePos' => 43806,
                'endTokenPos' => 5754,
                'endFilePos' => 43810,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1416,
            'endLine' => 1416,
            'startColumn' => 51,
            'endColumn' => 72,
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
                'startLine' => 1416,
                'endLine' => 1416,
                'startTokenPos' => 5761,
                'startFilePos' => 43824,
                'endTokenPos' => 5761,
                'endFilePos' => 43828,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1416,
            'endLine' => 1416,
            'startColumn' => 75,
            'endColumn' => 90,
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
 * Add a "where not like" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  bool  $caseSensitive
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1416,
        'endLine' => 1419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotLike' => 
      array (
        'name' => 'orWhereNotLike',
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
            'startLine' => 1429,
            'endLine' => 1429,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1429,
            'endLine' => 1429,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'caseSensitive' => 
          array (
            'name' => 'caseSensitive',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1429,
                'endLine' => 1429,
                'startTokenPos' => 5808,
                'startFilePos' => 44239,
                'endTokenPos' => 5808,
                'endFilePos' => 44243,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1429,
            'endLine' => 1429,
            'startColumn' => 53,
            'endColumn' => 74,
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
 * Add an "or where not like" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $value
 * @param  bool  $caseSensitive
 * @return $this
 */',
        'startLine' => 1429,
        'endLine' => 1432,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNullSafeEquals' => 
      array (
        'name' => 'whereNullSafeEquals',
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
            'startLine' => 1442,
            'endLine' => 1442,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1442,
            'endLine' => 1442,
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
                'startLine' => 1442,
                'endLine' => 1442,
                'startTokenPos' => 5852,
                'startFilePos' => 44645,
                'endTokenPos' => 5852,
                'endFilePos' => 44649,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1442,
            'endLine' => 1442,
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
 * Add a "where null safe equals" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1442,
        'endLine' => 1453,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNullSafeEquals' => 
      array (
        'name' => 'orWhereNullSafeEquals',
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
            'startLine' => 1462,
            'endLine' => 1462,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1462,
            'endLine' => 1462,
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
 * Add an "or where null safe equals" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1462,
        'endLine' => 1465,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereIn' => 
      array (
        'name' => 'whereIn',
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
            'startLine' => 1478,
            'endLine' => 1478,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1478,
            'endLine' => 1478,
            'startColumn' => 38,
            'endColumn' => 44,
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
                'startLine' => 1478,
                'endLine' => 1478,
                'startTokenPos' => 5993,
                'startFilePos' => 45679,
                'endTokenPos' => 5993,
                'endFilePos' => 45683,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1478,
            'endLine' => 1478,
            'startColumn' => 47,
            'endColumn' => 62,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1478,
                'endLine' => 1478,
                'startTokenPos' => 6000,
                'startFilePos' => 45693,
                'endTokenPos' => 6000,
                'endFilePos' => 45697,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1478,
            'endLine' => 1478,
            'startColumn' => 65,
            'endColumn' => 76,
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
 * Add a "where in" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $values
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 1478,
        'endLine' => 1512,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereIn' => 
      array (
        'name' => 'orWhereIn',
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
            'startLine' => 1521,
            'endLine' => 1521,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1521,
            'endLine' => 1521,
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
 * Add an "or where in" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $values
 * @return $this
 */',
        'startLine' => 1521,
        'endLine' => 1524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotIn' => 
      array (
        'name' => 'whereNotIn',
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
            'startLine' => 1534,
            'endLine' => 1534,
            'startColumn' => 32,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1534,
            'endLine' => 1534,
            'startColumn' => 41,
            'endColumn' => 47,
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
                'startLine' => 1534,
                'endLine' => 1534,
                'startTokenPos' => 6271,
                'startFilePos' => 47844,
                'endTokenPos' => 6271,
                'endFilePos' => 47848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1534,
            'endLine' => 1534,
            'startColumn' => 50,
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
 * Add a "where not in" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $values
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1534,
        'endLine' => 1537,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotIn' => 
      array (
        'name' => 'orWhereNotIn',
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
            'startLine' => 1546,
            'endLine' => 1546,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1546,
            'endLine' => 1546,
            'startColumn' => 43,
            'endColumn' => 49,
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
 * Add an "or where not in" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  mixed  $values
 * @return $this
 */',
        'startLine' => 1546,
        'endLine' => 1549,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereIntegerInRaw' => 
      array (
        'name' => 'whereIntegerInRaw',
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
            'startLine' => 1560,
            'endLine' => 1560,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1560,
            'endLine' => 1560,
            'startColumn' => 48,
            'endColumn' => 54,
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
                'startLine' => 1560,
                'endLine' => 1560,
                'startTokenPos' => 6349,
                'startFilePos' => 48595,
                'endTokenPos' => 6349,
                'endFilePos' => 48599,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1560,
            'endLine' => 1560,
            'startColumn' => 57,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1560,
                'endLine' => 1560,
                'startTokenPos' => 6356,
                'startFilePos' => 48609,
                'endTokenPos' => 6356,
                'endFilePos' => 48613,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1560,
            'endLine' => 1560,
            'startColumn' => 75,
            'endColumn' => 86,
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
 * Add a "where in raw" clause for integer values to the query.
 *
 * @param  string  $column
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array  $values
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1560,
        'endLine' => 1577,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereIntegerInRaw' => 
      array (
        'name' => 'orWhereIntegerInRaw',
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
            'startLine' => 1586,
            'endLine' => 1586,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1586,
            'endLine' => 1586,
            'startColumn' => 50,
            'endColumn' => 56,
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
 * Add an "or where in raw" clause for integer values to the query.
 *
 * @param  string  $column
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array  $values
 * @return $this
 */',
        'startLine' => 1586,
        'endLine' => 1589,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereIntegerNotInRaw' => 
      array (
        'name' => 'whereIntegerNotInRaw',
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
            'startLine' => 1599,
            'endLine' => 1599,
            'startColumn' => 42,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1599,
            'endLine' => 1599,
            'startColumn' => 51,
            'endColumn' => 57,
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
                'startLine' => 1599,
                'endLine' => 1599,
                'startTokenPos' => 6537,
                'startFilePos' => 49716,
                'endTokenPos' => 6537,
                'endFilePos' => 49720,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1599,
            'endLine' => 1599,
            'startColumn' => 60,
            'endColumn' => 75,
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
 * Add a "where not in raw" clause for integer values to the query.
 *
 * @param  string  $column
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array  $values
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1599,
        'endLine' => 1602,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereIntegerNotInRaw' => 
      array (
        'name' => 'orWhereIntegerNotInRaw',
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
            'startLine' => 1611,
            'endLine' => 1611,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1611,
            'endLine' => 1611,
            'startColumn' => 53,
            'endColumn' => 59,
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
 * Add an "or where not in raw" clause for integer values to the query.
 *
 * @param  string  $column
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array  $values
 * @return $this
 */',
        'startLine' => 1611,
        'endLine' => 1614,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNull' => 
      array (
        'name' => 'whereNull',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1624,
            'endLine' => 1624,
            'startColumn' => 31,
            'endColumn' => 38,
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
                'startLine' => 1624,
                'endLine' => 1624,
                'startTokenPos' => 6612,
                'startFilePos' => 50460,
                'endTokenPos' => 6612,
                'endFilePos' => 50464,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1624,
            'endLine' => 1624,
            'startColumn' => 41,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1624,
                'endLine' => 1624,
                'startTokenPos' => 6619,
                'startFilePos' => 50474,
                'endTokenPos' => 6619,
                'endFilePos' => 50478,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1624,
            'endLine' => 1624,
            'startColumn' => 59,
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
 * Add a "where null" clause to the query.
 *
 * @param  string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $columns
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1624,
        'endLine' => 1633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNull' => 
      array (
        'name' => 'orWhereNull',
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
            'startLine' => 1641,
            'endLine' => 1641,
            'startColumn' => 33,
            'endColumn' => 39,
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
 * Add an "or where null" clause to the query.
 *
 * @param  string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $column
 * @return $this
 */',
        'startLine' => 1641,
        'endLine' => 1644,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotNull' => 
      array (
        'name' => 'whereNotNull',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1653,
            'endLine' => 1653,
            'startColumn' => 34,
            'endColumn' => 41,
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
                'startLine' => 1653,
                'endLine' => 1653,
                'startTokenPos' => 6739,
                'startFilePos' => 51264,
                'endTokenPos' => 6739,
                'endFilePos' => 51268,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1653,
            'endLine' => 1653,
            'startColumn' => 44,
            'endColumn' => 59,
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
 * Add a "where not null" clause to the query.
 *
 * @param  string|array|\\Illuminate\\Contracts\\Database\\Query\\Expression  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1653,
        'endLine' => 1656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereBetween' => 
      array (
        'name' => 'whereBetween',
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
            'startLine' => 1666,
            'endLine' => 1666,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1666,
            'endLine' => 1666,
            'startColumn' => 43,
            'endColumn' => 58,
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
                'startLine' => 1666,
                'endLine' => 1666,
                'startTokenPos' => 6782,
                'startFilePos' => 51724,
                'endTokenPos' => 6782,
                'endFilePos' => 51728,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1666,
            'endLine' => 1666,
            'startColumn' => 61,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1666,
                'endLine' => 1666,
                'startTokenPos' => 6789,
                'startFilePos' => 51738,
                'endTokenPos' => 6789,
                'endFilePos' => 51742,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1666,
            'endLine' => 1666,
            'startColumn' => 79,
            'endColumn' => 90,
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
 * Add a "where between" statement to the query.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1666,
        'endLine' => 1686,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereBetweenColumns' => 
      array (
        'name' => 'whereBetweenColumns',
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
            'startLine' => 1696,
            'endLine' => 1696,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 1696,
            'endLine' => 1696,
            'startColumn' => 50,
            'endColumn' => 62,
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
                'startLine' => 1696,
                'endLine' => 1696,
                'startTokenPos' => 6998,
                'startFilePos' => 52735,
                'endTokenPos' => 6998,
                'endFilePos' => 52739,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1696,
            'endLine' => 1696,
            'startColumn' => 65,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1696,
                'endLine' => 1696,
                'startTokenPos' => 7005,
                'startFilePos' => 52749,
                'endTokenPos' => 7005,
                'endFilePos' => 52753,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1696,
            'endLine' => 1696,
            'startColumn' => 83,
            'endColumn' => 94,
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
 * Add a "where between" statement using columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1696,
        'endLine' => 1710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereBetween' => 
      array (
        'name' => 'orWhereBetween',
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
            'startLine' => 1718,
            'endLine' => 1718,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1718,
            'endLine' => 1718,
            'startColumn' => 45,
            'endColumn' => 60,
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
 * Add an "or where between" statement to the query.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1718,
        'endLine' => 1721,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereBetweenColumns' => 
      array (
        'name' => 'orWhereBetweenColumns',
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
            'startLine' => 1729,
            'endLine' => 1729,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 1729,
            'endLine' => 1729,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * Add an "or where between" statement using columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1729,
        'endLine' => 1732,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotBetween' => 
      array (
        'name' => 'whereNotBetween',
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
            'startLine' => 1741,
            'endLine' => 1741,
            'startColumn' => 37,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1741,
            'endLine' => 1741,
            'startColumn' => 46,
            'endColumn' => 61,
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
                'startLine' => 1741,
                'endLine' => 1741,
                'startTokenPos' => 7230,
                'startFilePos' => 54310,
                'endTokenPos' => 7230,
                'endFilePos' => 54314,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1741,
            'endLine' => 1741,
            'startColumn' => 64,
            'endColumn' => 79,
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
 * Add a "where not between" statement to the query.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1741,
        'endLine' => 1744,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotBetweenColumns' => 
      array (
        'name' => 'whereNotBetweenColumns',
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
            'startLine' => 1753,
            'endLine' => 1753,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 1753,
            'endLine' => 1753,
            'startColumn' => 53,
            'endColumn' => 65,
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
                'startLine' => 1753,
                'endLine' => 1753,
                'startTokenPos' => 7276,
                'startFilePos' => 54704,
                'endTokenPos' => 7276,
                'endFilePos' => 54708,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1753,
            'endLine' => 1753,
            'startColumn' => 68,
            'endColumn' => 83,
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
 * Add a "where not between" statement using columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1753,
        'endLine' => 1756,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotBetween' => 
      array (
        'name' => 'orWhereNotBetween',
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
            'startLine' => 1764,
            'endLine' => 1764,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1764,
            'endLine' => 1764,
            'startColumn' => 48,
            'endColumn' => 63,
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
 * Add an "or where not between" statement to the query.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1764,
        'endLine' => 1767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotBetweenColumns' => 
      array (
        'name' => 'orWhereNotBetweenColumns',
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
            'startLine' => 1775,
            'endLine' => 1775,
            'startColumn' => 46,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 1775,
            'endLine' => 1775,
            'startColumn' => 55,
            'endColumn' => 67,
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
 * Add an "or where not between" statement using columns to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1775,
        'endLine' => 1778,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereValueBetween' => 
      array (
        'name' => 'whereValueBetween',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1789,
            'endLine' => 1789,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 1789,
            'endLine' => 1789,
            'startColumn' => 47,
            'endColumn' => 60,
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
                'startLine' => 1789,
                'endLine' => 1789,
                'startTokenPos' => 7394,
                'startFilePos' => 55974,
                'endTokenPos' => 7394,
                'endFilePos' => 55978,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1789,
            'endLine' => 1789,
            'startColumn' => 63,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 1789,
                'endLine' => 1789,
                'startTokenPos' => 7401,
                'startFilePos' => 55988,
                'endTokenPos' => 7401,
                'endFilePos' => 55992,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1789,
            'endLine' => 1789,
            'startColumn' => 81,
            'endColumn' => 92,
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
 * Add a "where between columns" statement using a value to the query.
 *
 * @param  mixed  $value
 * @param  array{\\Illuminate\\Contracts\\Database\\Query\\Expression|string, \\Illuminate\\Contracts\\Database\\Query\\Expression|string}  $columns
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 1789,
        'endLine' => 1798,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereValueBetween' => 
      array (
        'name' => 'orWhereValueBetween',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1807,
            'endLine' => 1807,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 1807,
            'endLine' => 1807,
            'startColumn' => 49,
            'endColumn' => 62,
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
 * Add an "or where between columns" statement using a value to the query.
 *
 * @param  mixed  $value
 * @param  array{\\Illuminate\\Contracts\\Database\\Query\\Expression|string, \\Illuminate\\Contracts\\Database\\Query\\Expression|string}  $columns
 * @return $this
 */',
        'startLine' => 1807,
        'endLine' => 1810,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereValueNotBetween' => 
      array (
        'name' => 'whereValueNotBetween',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1820,
            'endLine' => 1820,
            'startColumn' => 42,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 1820,
            'endLine' => 1820,
            'startColumn' => 50,
            'endColumn' => 63,
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
                'startLine' => 1820,
                'endLine' => 1820,
                'startTokenPos' => 7532,
                'startFilePos' => 57074,
                'endTokenPos' => 7532,
                'endFilePos' => 57078,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1820,
            'endLine' => 1820,
            'startColumn' => 66,
            'endColumn' => 81,
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
 * Add a "where not between columns" statement using a value to the query.
 *
 * @param  mixed  $value
 * @param  array{\\Illuminate\\Contracts\\Database\\Query\\Expression|string, \\Illuminate\\Contracts\\Database\\Query\\Expression|string}  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1820,
        'endLine' => 1823,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereValueNotBetween' => 
      array (
        'name' => 'orWhereValueNotBetween',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1832,
            'endLine' => 1832,
            'startColumn' => 44,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 1832,
            'endLine' => 1832,
            'startColumn' => 52,
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
 * Add an "or where not between columns" statement using a value to the query.
 *
 * @param  mixed  $value
 * @param  array{\\Illuminate\\Contracts\\Database\\Query\\Expression|string, \\Illuminate\\Contracts\\Database\\Query\\Expression|string}  $columns
 * @return $this
 */',
        'startLine' => 1832,
        'endLine' => 1835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotNull' => 
      array (
        'name' => 'orWhereNotNull',
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
            'startLine' => 1843,
            'endLine' => 1843,
            'startColumn' => 36,
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
 * Add an "or where not null" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 1843,
        'endLine' => 1846,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereDate' => 
      array (
        'name' => 'whereDate',
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
            'startLine' => 1857,
            'endLine' => 1857,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
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
            'startLine' => 1857,
            'endLine' => 1857,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1857,
                'endLine' => 1857,
                'startTokenPos' => 7640,
                'startFilePos' => 58277,
                'endTokenPos' => 7640,
                'endFilePos' => 58280,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1857,
            'endLine' => 1857,
            'startColumn' => 51,
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
                'startLine' => 1857,
                'endLine' => 1857,
                'startTokenPos' => 7647,
                'startFilePos' => 58294,
                'endTokenPos' => 7647,
                'endFilePos' => 58298,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1857,
            'endLine' => 1857,
            'startColumn' => 66,
            'endColumn' => 81,
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
 * Add a "where date" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|null  $operator
 * @param  \\DateTimeInterface|string|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1857,
        'endLine' => 1877,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereDate' => 
      array (
        'name' => 'orWhereDate',
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
            'startLine' => 1887,
            'endLine' => 1887,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
            'startLine' => 1887,
            'endLine' => 1887,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1887,
                'endLine' => 1887,
                'startTokenPos' => 7801,
                'startFilePos' => 59388,
                'endTokenPos' => 7801,
                'endFilePos' => 59391,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1887,
            'endLine' => 1887,
            'startColumn' => 53,
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
 * Add an "or where date" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|null  $operator
 * @param  \\DateTimeInterface|string|null  $value
 * @return $this
 */',
        'startLine' => 1887,
        'endLine' => 1894,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereTime' => 
      array (
        'name' => 'whereTime',
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
            'startLine' => 1905,
            'endLine' => 1905,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
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
            'startLine' => 1905,
            'endLine' => 1905,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1905,
                'endLine' => 1905,
                'startTokenPos' => 7876,
                'startFilePos' => 59976,
                'endTokenPos' => 7876,
                'endFilePos' => 59979,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1905,
            'endLine' => 1905,
            'startColumn' => 51,
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
                'startLine' => 1905,
                'endLine' => 1905,
                'startTokenPos' => 7883,
                'startFilePos' => 59993,
                'endTokenPos' => 7883,
                'endFilePos' => 59997,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1905,
            'endLine' => 1905,
            'startColumn' => 66,
            'endColumn' => 81,
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
 * Add a "where time" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|null  $operator
 * @param  \\DateTimeInterface|string|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1905,
        'endLine' => 1925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereTime' => 
      array (
        'name' => 'orWhereTime',
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
            'startLine' => 1935,
            'endLine' => 1935,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
            'startLine' => 1935,
            'endLine' => 1935,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1935,
                'endLine' => 1935,
                'startTokenPos' => 8037,
                'startFilePos' => 61087,
                'endTokenPos' => 8037,
                'endFilePos' => 61090,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1935,
            'endLine' => 1935,
            'startColumn' => 53,
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
 * Add an "or where time" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|null  $operator
 * @param  \\DateTimeInterface|string|null  $value
 * @return $this
 */',
        'startLine' => 1935,
        'endLine' => 1942,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereDay' => 
      array (
        'name' => 'whereDay',
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
            'startLine' => 1953,
            'endLine' => 1953,
            'startColumn' => 30,
            'endColumn' => 36,
            'parameterIndex' => 0,
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
            'startLine' => 1953,
            'endLine' => 1953,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1953,
                'endLine' => 1953,
                'startTokenPos' => 8112,
                'startFilePos' => 61681,
                'endTokenPos' => 8112,
                'endFilePos' => 61684,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1953,
            'endLine' => 1953,
            'startColumn' => 50,
            'endColumn' => 62,
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
                'startLine' => 1953,
                'endLine' => 1953,
                'startTokenPos' => 8119,
                'startFilePos' => 61698,
                'endTokenPos' => 8119,
                'endFilePos' => 61702,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1953,
            'endLine' => 1953,
            'startColumn' => 65,
            'endColumn' => 80,
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
 * Add a "where day" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 1953,
        'endLine' => 1977,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereDay' => 
      array (
        'name' => 'orWhereDay',
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
            'startLine' => 1987,
            'endLine' => 1987,
            'startColumn' => 32,
            'endColumn' => 38,
            'parameterIndex' => 0,
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
            'startLine' => 1987,
            'endLine' => 1987,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1987,
                'endLine' => 1987,
                'startTokenPos' => 8302,
                'startFilePos' => 62904,
                'endTokenPos' => 8302,
                'endFilePos' => 62907,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1987,
            'endLine' => 1987,
            'startColumn' => 52,
            'endColumn' => 64,
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
 * Add an "or where day" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @return $this
 */',
        'startLine' => 1987,
        'endLine' => 1994,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereMonth' => 
      array (
        'name' => 'whereMonth',
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
            'startLine' => 2005,
            'endLine' => 2005,
            'startColumn' => 32,
            'endColumn' => 38,
            'parameterIndex' => 0,
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
            'startLine' => 2005,
            'endLine' => 2005,
            'startColumn' => 41,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2005,
                'endLine' => 2005,
                'startTokenPos' => 8377,
                'startFilePos' => 63501,
                'endTokenPos' => 8377,
                'endFilePos' => 63504,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2005,
            'endLine' => 2005,
            'startColumn' => 52,
            'endColumn' => 64,
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
                'startLine' => 2005,
                'endLine' => 2005,
                'startTokenPos' => 8384,
                'startFilePos' => 63518,
                'endTokenPos' => 8384,
                'endFilePos' => 63522,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2005,
            'endLine' => 2005,
            'startColumn' => 67,
            'endColumn' => 82,
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
 * Add a "where month" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2005,
        'endLine' => 2029,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereMonth' => 
      array (
        'name' => 'orWhereMonth',
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
            'startLine' => 2039,
            'endLine' => 2039,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
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
            'startLine' => 2039,
            'endLine' => 2039,
            'startColumn' => 43,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2039,
                'endLine' => 2039,
                'startTokenPos' => 8567,
                'startFilePos' => 64730,
                'endTokenPos' => 8567,
                'endFilePos' => 64733,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2039,
            'endLine' => 2039,
            'startColumn' => 54,
            'endColumn' => 66,
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
 * Add an "or where month" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @return $this
 */',
        'startLine' => 2039,
        'endLine' => 2046,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereYear' => 
      array (
        'name' => 'whereYear',
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
            'startLine' => 2057,
            'endLine' => 2057,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
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
            'startLine' => 2057,
            'endLine' => 2057,
            'startColumn' => 40,
            'endColumn' => 48,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2057,
                'endLine' => 2057,
                'startTokenPos' => 8642,
                'startFilePos' => 65327,
                'endTokenPos' => 8642,
                'endFilePos' => 65330,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2057,
            'endLine' => 2057,
            'startColumn' => 51,
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
                'startLine' => 2057,
                'endLine' => 2057,
                'startTokenPos' => 8649,
                'startFilePos' => 65344,
                'endTokenPos' => 8649,
                'endFilePos' => 65348,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2057,
            'endLine' => 2057,
            'startColumn' => 66,
            'endColumn' => 81,
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
 * Add a "where year" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2057,
        'endLine' => 2077,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereYear' => 
      array (
        'name' => 'orWhereYear',
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
            'startLine' => 2087,
            'endLine' => 2087,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
            'startLine' => 2087,
            'endLine' => 2087,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2087,
                'endLine' => 2087,
                'startTokenPos' => 8803,
                'startFilePos' => 66442,
                'endTokenPos' => 8803,
                'endFilePos' => 66445,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2087,
            'endLine' => 2087,
            'startColumn' => 53,
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
 * Add an "or where year" statement to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\DateTimeInterface|string|int|null  $operator
 * @param  \\DateTimeInterface|string|int|null  $value
 * @return $this
 */',
        'startLine' => 2087,
        'endLine' => 2094,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addDateBasedWhere' => 
      array (
        'name' => 'addDateBasedWhere',
        'parameters' => 
        array (
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
            'startLine' => 2106,
            'endLine' => 2106,
            'startColumn' => 42,
            'endColumn' => 46,
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
            'startLine' => 2106,
            'endLine' => 2106,
            'startColumn' => 49,
            'endColumn' => 55,
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
            'startLine' => 2106,
            'endLine' => 2106,
            'startColumn' => 58,
            'endColumn' => 66,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2106,
            'endLine' => 2106,
            'startColumn' => 69,
            'endColumn' => 74,
            'parameterIndex' => 3,
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
                'startLine' => 2106,
                'endLine' => 2106,
                'startTokenPos' => 8884,
                'startFilePos' => 67061,
                'endTokenPos' => 8884,
                'endFilePos' => 67065,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2106,
            'endLine' => 2106,
            'startColumn' => 77,
            'endColumn' => 92,
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
 * Add a date based (year, month, day, time) statement to the query.
 *
 * @param  string  $type
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2106,
        'endLine' => 2115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNested' => 
      array (
        'name' => 'whereNested',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2123,
            'endLine' => 2123,
            'startColumn' => 33,
            'endColumn' => 49,
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
                'startLine' => 2123,
                'endLine' => 2123,
                'startTokenPos' => 8985,
                'startFilePos' => 67540,
                'endTokenPos' => 8985,
                'endFilePos' => 67544,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2123,
            'endLine' => 2123,
            'startColumn' => 52,
            'endColumn' => 67,
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
 * Add a nested "where" statement to the query.
 *
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2123,
        'endLine' => 2128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forNestedWhere' => 
      array (
        'name' => 'forNestedWhere',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new query instance for nested where condition.
 *
 * @return \\Illuminate\\Database\\Query\\Builder
 */',
        'startLine' => 2135,
        'endLine' => 2138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addNestedWhereQuery' => 
      array (
        'name' => 'addNestedWhereQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2147,
            'endLine' => 2147,
            'startColumn' => 41,
            'endColumn' => 46,
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
                'startLine' => 2147,
                'endLine' => 2147,
                'startTokenPos' => 9064,
                'startFilePos' => 68180,
                'endTokenPos' => 9064,
                'endFilePos' => 68184,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2147,
            'endLine' => 2147,
            'startColumn' => 49,
            'endColumn' => 64,
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
 * Add another query builder as a nested where to the query builder.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder  $query
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2147,
        'endLine' => 2158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereSub' => 
      array (
        'name' => 'whereSub',
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
            'startLine' => 2169,
            'endLine' => 2169,
            'startColumn' => 33,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
            'startLine' => 2169,
            'endLine' => 2169,
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2169,
            'endLine' => 2169,
            'startColumn' => 53,
            'endColumn' => 61,
            'parameterIndex' => 2,
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
            'startLine' => 2169,
            'endLine' => 2169,
            'startColumn' => 64,
            'endColumn' => 71,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a full sub-select to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string  $operator
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $callback
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2169,
        'endLine' => 2189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereExists' => 
      array (
        'name' => 'whereExists',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2199,
            'endLine' => 2199,
            'startColumn' => 33,
            'endColumn' => 41,
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
                'startLine' => 2199,
                'endLine' => 2199,
                'startTokenPos' => 9324,
                'startFilePos' => 69948,
                'endTokenPos' => 9324,
                'endFilePos' => 69952,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2199,
            'endLine' => 2199,
            'startColumn' => 44,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2199,
                'endLine' => 2199,
                'startTokenPos' => 9331,
                'startFilePos' => 69962,
                'endTokenPos' => 9331,
                'endFilePos' => 69966,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2199,
            'endLine' => 2199,
            'startColumn' => 62,
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
 * Add an "exists" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $callback
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2199,
        'endLine' => 2213,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereExists' => 
      array (
        'name' => 'orWhereExists',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2222,
            'endLine' => 2222,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2222,
                'endLine' => 2222,
                'startTokenPos' => 9435,
                'startFilePos' => 70830,
                'endTokenPos' => 9435,
                'endFilePos' => 70834,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2222,
            'endLine' => 2222,
            'startColumn' => 46,
            'endColumn' => 57,
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
 * Add an "or where exists" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $callback
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2222,
        'endLine' => 2225,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNotExists' => 
      array (
        'name' => 'whereNotExists',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2234,
            'endLine' => 2234,
            'startColumn' => 36,
            'endColumn' => 44,
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
                'startLine' => 2234,
                'endLine' => 2234,
                'startTokenPos' => 9473,
                'startFilePos' => 71205,
                'endTokenPos' => 9473,
                'endFilePos' => 71209,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2234,
            'endLine' => 2234,
            'startColumn' => 47,
            'endColumn' => 62,
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
 * Add a "where not exists" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $callback
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2234,
        'endLine' => 2237,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNotExists' => 
      array (
        'name' => 'orWhereNotExists',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2245,
            'endLine' => 2245,
            'startColumn' => 38,
            'endColumn' => 46,
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
 * Add an "or where not exists" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $callback
 * @return $this
 */',
        'startLine' => 2245,
        'endLine' => 2248,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addWhereExistsQuery' => 
      array (
        'name' => 'addWhereExistsQuery',
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
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2257,
            'endLine' => 2257,
            'startColumn' => 41,
            'endColumn' => 51,
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
                'startLine' => 2257,
                'endLine' => 2257,
                'startTokenPos' => 9541,
                'startFilePos' => 71824,
                'endTokenPos' => 9541,
                'endFilePos' => 71828,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2257,
            'endLine' => 2257,
            'startColumn' => 54,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2257,
                'endLine' => 2257,
                'startTokenPos' => 9548,
                'startFilePos' => 71838,
                'endTokenPos' => 9548,
                'endFilePos' => 71842,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2257,
            'endLine' => 2257,
            'startColumn' => 72,
            'endColumn' => 83,
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
 * Add an "exists" clause to the query.
 *
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2257,
        'endLine' => 2266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereRowValues' => 
      array (
        'name' => 'whereRowValues',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2279,
            'endLine' => 2279,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
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
            'startLine' => 2279,
            'endLine' => 2279,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2279,
            'endLine' => 2279,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
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
                'startLine' => 2279,
                'endLine' => 2279,
                'startTokenPos' => 9642,
                'startFilePos' => 72418,
                'endTokenPos' => 9642,
                'endFilePos' => 72422,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2279,
            'endLine' => 2279,
            'startColumn' => 66,
            'endColumn' => 81,
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
 * Adds a where condition using row values.
 *
 * @param  array  $columns
 * @param  string  $operator
 * @param  array  $values
 * @param  string  $boolean
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 2279,
        'endLine' => 2292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereRowValues' => 
      array (
        'name' => 'orWhereRowValues',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2302,
            'endLine' => 2302,
            'startColumn' => 38,
            'endColumn' => 45,
            'parameterIndex' => 0,
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
            'startLine' => 2302,
            'endLine' => 2302,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2302,
            'endLine' => 2302,
            'startColumn' => 59,
            'endColumn' => 65,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds an or where condition using row values.
 *
 * @param  array  $columns
 * @param  string  $operator
 * @param  array  $values
 * @return $this
 */',
        'startLine' => 2302,
        'endLine' => 2305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonContains' => 
      array (
        'name' => 'whereJsonContains',
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
            'startLine' => 2316,
            'endLine' => 2316,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2316,
            'endLine' => 2316,
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
                'startLine' => 2316,
                'endLine' => 2316,
                'startTokenPos' => 9807,
                'startFilePos' => 73481,
                'endTokenPos' => 9807,
                'endFilePos' => 73485,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2316,
            'endLine' => 2316,
            'startColumn' => 56,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2316,
                'endLine' => 2316,
                'startTokenPos' => 9814,
                'startFilePos' => 73495,
                'endTokenPos' => 9814,
                'endFilePos' => 73499,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2316,
            'endLine' => 2316,
            'startColumn' => 74,
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
 * Add a "where JSON contains" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2316,
        'endLine' => 2327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonContains' => 
      array (
        'name' => 'orWhereJsonContains',
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
            'startLine' => 2336,
            'endLine' => 2336,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2336,
            'endLine' => 2336,
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
 * Add an "or where JSON contains" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2336,
        'endLine' => 2339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonDoesntContain' => 
      array (
        'name' => 'whereJsonDoesntContain',
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
            'startLine' => 2349,
            'endLine' => 2349,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2349,
            'endLine' => 2349,
            'startColumn' => 53,
            'endColumn' => 58,
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
                'startLine' => 2349,
                'endLine' => 2349,
                'startTokenPos' => 9961,
                'startFilePos' => 74410,
                'endTokenPos' => 9961,
                'endFilePos' => 74414,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2349,
            'endLine' => 2349,
            'startColumn' => 61,
            'endColumn' => 76,
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
 * Add a "where JSON not contains" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2349,
        'endLine' => 2352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonDoesntContain' => 
      array (
        'name' => 'orWhereJsonDoesntContain',
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
            'startLine' => 2361,
            'endLine' => 2361,
            'startColumn' => 46,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2361,
            'endLine' => 2361,
            'startColumn' => 55,
            'endColumn' => 60,
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
 * Add an "or where JSON not contains" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2361,
        'endLine' => 2364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonOverlaps' => 
      array (
        'name' => 'whereJsonOverlaps',
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
            'startLine' => 2375,
            'endLine' => 2375,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2375,
            'endLine' => 2375,
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
                'startLine' => 2375,
                'endLine' => 2375,
                'startTokenPos' => 10039,
                'startFilePos' => 75100,
                'endTokenPos' => 10039,
                'endFilePos' => 75104,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2375,
            'endLine' => 2375,
            'startColumn' => 56,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2375,
                'endLine' => 2375,
                'startTokenPos' => 10046,
                'startFilePos' => 75114,
                'endTokenPos' => 10046,
                'endFilePos' => 75118,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2375,
            'endLine' => 2375,
            'startColumn' => 74,
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
 * Add a "where JSON overlaps" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2375,
        'endLine' => 2386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonOverlaps' => 
      array (
        'name' => 'orWhereJsonOverlaps',
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
            'startLine' => 2395,
            'endLine' => 2395,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2395,
            'endLine' => 2395,
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
 * Add an "or where JSON overlaps" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2395,
        'endLine' => 2398,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonDoesntOverlap' => 
      array (
        'name' => 'whereJsonDoesntOverlap',
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
            'startLine' => 2408,
            'endLine' => 2408,
            'startColumn' => 44,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2408,
            'endLine' => 2408,
            'startColumn' => 53,
            'endColumn' => 58,
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
                'startLine' => 2408,
                'endLine' => 2408,
                'startTokenPos' => 10193,
                'startFilePos' => 76028,
                'endTokenPos' => 10193,
                'endFilePos' => 76032,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2408,
            'endLine' => 2408,
            'startColumn' => 61,
            'endColumn' => 76,
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
 * Add a "where JSON not overlap" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2408,
        'endLine' => 2411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonDoesntOverlap' => 
      array (
        'name' => 'orWhereJsonDoesntOverlap',
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
            'startLine' => 2420,
            'endLine' => 2420,
            'startColumn' => 46,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2420,
            'endLine' => 2420,
            'startColumn' => 55,
            'endColumn' => 60,
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
 * Add an "or where JSON not overlap" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2420,
        'endLine' => 2423,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonContainsKey' => 
      array (
        'name' => 'whereJsonContainsKey',
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
            'startLine' => 2433,
            'endLine' => 2433,
            'startColumn' => 42,
            'endColumn' => 48,
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
                'startLine' => 2433,
                'endLine' => 2433,
                'startTokenPos' => 10268,
                'startFilePos' => 76699,
                'endTokenPos' => 10268,
                'endFilePos' => 76703,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2433,
            'endLine' => 2433,
            'startColumn' => 51,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2433,
                'endLine' => 2433,
                'startTokenPos' => 10275,
                'startFilePos' => 76713,
                'endTokenPos' => 10275,
                'endFilePos' => 76717,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2433,
            'endLine' => 2433,
            'startColumn' => 69,
            'endColumn' => 80,
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
 * Add a clause that determines if a JSON path exists to the query.
 *
 * @param  string  $column
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2433,
        'endLine' => 2440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonContainsKey' => 
      array (
        'name' => 'orWhereJsonContainsKey',
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
            'startLine' => 2448,
            'endLine' => 2448,
            'startColumn' => 44,
            'endColumn' => 50,
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
 * Add an "or" clause that determines if a JSON path exists to the query.
 *
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 2448,
        'endLine' => 2451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonDoesntContainKey' => 
      array (
        'name' => 'whereJsonDoesntContainKey',
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
            'startLine' => 2460,
            'endLine' => 2460,
            'startColumn' => 47,
            'endColumn' => 53,
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
                'startLine' => 2460,
                'endLine' => 2460,
                'startTokenPos' => 10375,
                'startFilePos' => 77426,
                'endTokenPos' => 10375,
                'endFilePos' => 77430,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2460,
            'endLine' => 2460,
            'startColumn' => 56,
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
 * Add a clause that determines if a JSON path does not exist to the query.
 *
 * @param  string  $column
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2460,
        'endLine' => 2463,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonDoesntContainKey' => 
      array (
        'name' => 'orWhereJsonDoesntContainKey',
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
            'startLine' => 2471,
            'endLine' => 2471,
            'startColumn' => 49,
            'endColumn' => 55,
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
 * Add an "or" clause that determines if a JSON path does not exist to the query.
 *
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 2471,
        'endLine' => 2474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereJsonLength' => 
      array (
        'name' => 'whereJsonLength',
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
            'startLine' => 2485,
            'endLine' => 2485,
            'startColumn' => 37,
            'endColumn' => 43,
            'parameterIndex' => 0,
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
            'startLine' => 2485,
            'endLine' => 2485,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2485,
                'endLine' => 2485,
                'startTokenPos' => 10444,
                'startFilePos' => 78097,
                'endTokenPos' => 10444,
                'endFilePos' => 78100,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2485,
            'endLine' => 2485,
            'startColumn' => 57,
            'endColumn' => 69,
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
                'startLine' => 2485,
                'endLine' => 2485,
                'startTokenPos' => 10451,
                'startFilePos' => 78114,
                'endTokenPos' => 10451,
                'endFilePos' => 78118,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2485,
            'endLine' => 2485,
            'startColumn' => 72,
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
 * Add a "where JSON length" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2485,
        'endLine' => 2507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereJsonLength' => 
      array (
        'name' => 'orWhereJsonLength',
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
            'startLine' => 2517,
            'endLine' => 2517,
            'startColumn' => 39,
            'endColumn' => 45,
            'parameterIndex' => 0,
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
            'startLine' => 2517,
            'endLine' => 2517,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2517,
                'endLine' => 2517,
                'startTokenPos' => 10633,
                'startFilePos' => 79196,
                'endTokenPos' => 10633,
                'endFilePos' => 79199,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2517,
            'endLine' => 2517,
            'startColumn' => 59,
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
 * Add an "or where JSON length" clause to the query.
 *
 * @param  string  $column
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2517,
        'endLine' => 2524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'dynamicWhere' => 
      array (
        'name' => 'dynamicWhere',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2533,
            'endLine' => 2533,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2533,
            'endLine' => 2533,
            'startColumn' => 43,
            'endColumn' => 53,
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
 * Handles dynamic "where" clauses to the query.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return $this
 */',
        'startLine' => 2533,
        'endLine' => 2567,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addDynamic' => 
      array (
        'name' => 'addDynamic',
        'parameters' => 
        array (
          'segment' => 
          array (
            'name' => 'segment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2578,
            'endLine' => 2578,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'connector' => 
          array (
            'name' => 'connector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2578,
            'endLine' => 2578,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2578,
            'endLine' => 2578,
            'startColumn' => 57,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'index' => 
          array (
            'name' => 'index',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2578,
            'endLine' => 2578,
            'startColumn' => 70,
            'endColumn' => 75,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a single dynamic "where" clause statement to the query.
 *
 * @param  string  $segment
 * @param  string  $connector
 * @param  array  $parameters
 * @param  int  $index
 * @return void
 */',
        'startLine' => 2578,
        'endLine' => 2586,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereFullText' => 
      array (
        'name' => 'whereFullText',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2596,
            'endLine' => 2596,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2596,
            'endLine' => 2596,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 2596,
                'endLine' => 2596,
                'startTokenPos' => 10935,
                'startFilePos' => 81937,
                'endTokenPos' => 10936,
                'endFilePos' => 81938,
              ),
            ),
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
            'startLine' => 2596,
            'endLine' => 2596,
            'startColumn' => 53,
            'endColumn' => 71,
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
                'startLine' => 2596,
                'endLine' => 2596,
                'startTokenPos' => 10943,
                'startFilePos' => 81952,
                'endTokenPos' => 10943,
                'endFilePos' => 81956,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2596,
            'endLine' => 2596,
            'startColumn' => 74,
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
 * Add a "where fulltext" clause to the query.
 *
 * @param  string|string[]  $columns
 * @param  string  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2596,
        'endLine' => 2607,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereFullText' => 
      array (
        'name' => 'orWhereFullText',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2616,
            'endLine' => 2616,
            'startColumn' => 37,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2616,
            'endLine' => 2616,
            'startColumn' => 47,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 2616,
                'endLine' => 2616,
                'startTokenPos' => 11044,
                'startFilePos' => 82472,
                'endTokenPos' => 11045,
                'endFilePos' => 82473,
              ),
            ),
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
            'startLine' => 2616,
            'endLine' => 2616,
            'startColumn' => 55,
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
 * Add an "or where fulltext" clause to the query.
 *
 * @param  string|string[]  $columns
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 2616,
        'endLine' => 2619,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereAll' => 
      array (
        'name' => 'whereAll',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2630,
            'endLine' => 2630,
            'startColumn' => 30,
            'endColumn' => 37,
            'parameterIndex' => 0,
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
                'startLine' => 2630,
                'endLine' => 2630,
                'startTokenPos' => 11086,
                'startFilePos' => 82941,
                'endTokenPos' => 11086,
                'endFilePos' => 82944,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2630,
            'endLine' => 2630,
            'startColumn' => 40,
            'endColumn' => 55,
            'parameterIndex' => 1,
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
                'startLine' => 2630,
                'endLine' => 2630,
                'startTokenPos' => 11093,
                'startFilePos' => 82956,
                'endTokenPos' => 11093,
                'endFilePos' => 82959,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2630,
            'endLine' => 2630,
            'startColumn' => 58,
            'endColumn' => 70,
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
                'startLine' => 2630,
                'endLine' => 2630,
                'startTokenPos' => 11100,
                'startFilePos' => 82973,
                'endTokenPos' => 11100,
                'endFilePos' => 82977,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2630,
            'endLine' => 2630,
            'startColumn' => 73,
            'endColumn' => 88,
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
 * Add a "where" clause to the query for multiple columns with "and" conditions between them.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2630,
        'endLine' => 2643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereAll' => 
      array (
        'name' => 'orWhereAll',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2653,
            'endLine' => 2653,
            'startColumn' => 32,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
                'startLine' => 2653,
                'endLine' => 2653,
                'startTokenPos' => 11220,
                'startFilePos' => 83725,
                'endTokenPos' => 11220,
                'endFilePos' => 83728,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2653,
            'endLine' => 2653,
            'startColumn' => 42,
            'endColumn' => 57,
            'parameterIndex' => 1,
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
                'startLine' => 2653,
                'endLine' => 2653,
                'startTokenPos' => 11227,
                'startFilePos' => 83740,
                'endTokenPos' => 11227,
                'endFilePos' => 83743,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2653,
            'endLine' => 2653,
            'startColumn' => 60,
            'endColumn' => 72,
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
 * Add an "or where" clause to the query for multiple columns with "and" conditions between them.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2653,
        'endLine' => 2656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereAny' => 
      array (
        'name' => 'whereAny',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2667,
            'endLine' => 2667,
            'startColumn' => 30,
            'endColumn' => 37,
            'parameterIndex' => 0,
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
                'startLine' => 2667,
                'endLine' => 2667,
                'startTokenPos' => 11268,
                'startFilePos' => 84206,
                'endTokenPos' => 11268,
                'endFilePos' => 84209,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2667,
            'endLine' => 2667,
            'startColumn' => 40,
            'endColumn' => 55,
            'parameterIndex' => 1,
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
                'startLine' => 2667,
                'endLine' => 2667,
                'startTokenPos' => 11275,
                'startFilePos' => 84221,
                'endTokenPos' => 11275,
                'endFilePos' => 84224,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2667,
            'endLine' => 2667,
            'startColumn' => 58,
            'endColumn' => 70,
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
                'startLine' => 2667,
                'endLine' => 2667,
                'startTokenPos' => 11282,
                'startFilePos' => 84238,
                'endTokenPos' => 11282,
                'endFilePos' => 84242,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2667,
            'endLine' => 2667,
            'startColumn' => 73,
            'endColumn' => 88,
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
 * Add a "where" clause to the query for multiple columns with "or" conditions between them.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2667,
        'endLine' => 2680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereAny' => 
      array (
        'name' => 'orWhereAny',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2690,
            'endLine' => 2690,
            'startColumn' => 32,
            'endColumn' => 39,
            'parameterIndex' => 0,
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
                'startLine' => 2690,
                'endLine' => 2690,
                'startTokenPos' => 11402,
                'startFilePos' => 84988,
                'endTokenPos' => 11402,
                'endFilePos' => 84991,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2690,
            'endLine' => 2690,
            'startColumn' => 42,
            'endColumn' => 57,
            'parameterIndex' => 1,
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
                'startLine' => 2690,
                'endLine' => 2690,
                'startTokenPos' => 11409,
                'startFilePos' => 85003,
                'endTokenPos' => 11409,
                'endFilePos' => 85006,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2690,
            'endLine' => 2690,
            'startColumn' => 60,
            'endColumn' => 72,
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
 * Add an "or where" clause to the query for multiple columns with "or" conditions between them.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2690,
        'endLine' => 2693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'whereNone' => 
      array (
        'name' => 'whereNone',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2704,
            'endLine' => 2704,
            'startColumn' => 31,
            'endColumn' => 38,
            'parameterIndex' => 0,
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
                'startLine' => 2704,
                'endLine' => 2704,
                'startTokenPos' => 11450,
                'startFilePos' => 85484,
                'endTokenPos' => 11450,
                'endFilePos' => 85487,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2704,
            'endLine' => 2704,
            'startColumn' => 41,
            'endColumn' => 56,
            'parameterIndex' => 1,
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
                'startLine' => 2704,
                'endLine' => 2704,
                'startTokenPos' => 11457,
                'startFilePos' => 85499,
                'endTokenPos' => 11457,
                'endFilePos' => 85502,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2704,
            'endLine' => 2704,
            'startColumn' => 59,
            'endColumn' => 71,
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
                'startLine' => 2704,
                'endLine' => 2704,
                'startTokenPos' => 11464,
                'startFilePos' => 85516,
                'endTokenPos' => 11464,
                'endFilePos' => 85520,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2704,
            'endLine' => 2704,
            'startColumn' => 74,
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
 * Add a "where not" clause to the query for multiple columns where none of the conditions should be true.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2704,
        'endLine' => 2707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orWhereNone' => 
      array (
        'name' => 'orWhereNone',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2717,
            'endLine' => 2717,
            'startColumn' => 33,
            'endColumn' => 40,
            'parameterIndex' => 0,
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
                'startLine' => 2717,
                'endLine' => 2717,
                'startTokenPos' => 11507,
                'startFilePos' => 85983,
                'endTokenPos' => 11507,
                'endFilePos' => 85986,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2717,
            'endLine' => 2717,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
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
                'startLine' => 2717,
                'endLine' => 2717,
                'startTokenPos' => 11514,
                'startFilePos' => 85998,
                'endTokenPos' => 11514,
                'endFilePos' => 86001,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2717,
            'endLine' => 2717,
            'startColumn' => 61,
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
 * Add an "or where not" clause to the query for multiple columns where none of the conditions should be true.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression[]|\\Closure[]|string[]  $columns
 * @param  mixed  $operator
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 2717,
        'endLine' => 2720,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'groupBy' => 
      array (
        'name' => 'groupBy',
        'parameters' => 
        array (
          'groups' => 
          array (
            'name' => 'groups',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2728,
            'endLine' => 2728,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add a "group by" clause to the query.
 *
 * @param  array|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  ...$groups
 * @return $this
 */',
        'startLine' => 2728,
        'endLine' => 2738,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'groupByRaw' => 
      array (
        'name' => 'groupByRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2746,
            'endLine' => 2746,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 2746,
                'endLine' => 2746,
                'startTokenPos' => 11618,
                'startFilePos' => 86698,
                'endTokenPos' => 11619,
                'endFilePos' => 86699,
              ),
            ),
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
            'startLine' => 2746,
            'endLine' => 2746,
            'startColumn' => 38,
            'endColumn' => 57,
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
 * Add a raw "groupBy" clause to the query.
 *
 * @param  literal-string  $sql
 * @return $this
 */',
        'startLine' => 2746,
        'endLine' => 2753,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'having' => 
      array (
        'name' => 'having',
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
            'startLine' => 2764,
            'endLine' => 2764,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
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
                'startLine' => 2764,
                'endLine' => 2764,
                'startTokenPos' => 11673,
                'startFilePos' => 87270,
                'endTokenPos' => 11673,
                'endFilePos' => 87273,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2764,
            'endLine' => 2764,
            'startColumn' => 37,
            'endColumn' => 52,
            'parameterIndex' => 1,
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
                'startLine' => 2764,
                'endLine' => 2764,
                'startTokenPos' => 11680,
                'startFilePos' => 87285,
                'endTokenPos' => 11680,
                'endFilePos' => 87288,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2764,
            'endLine' => 2764,
            'startColumn' => 55,
            'endColumn' => 67,
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
                'startLine' => 2764,
                'endLine' => 2764,
                'startTokenPos' => 11687,
                'startFilePos' => 87302,
                'endTokenPos' => 11687,
                'endFilePos' => 87306,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2764,
            'endLine' => 2764,
            'startColumn' => 70,
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
 * Add a "having" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|\\Closure|string  $column
 * @param  \\DateTimeInterface|string|int|float|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|\\DateTimeInterface|string|int|float|null  $value
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2764,
        'endLine' => 2805,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHaving' => 
      array (
        'name' => 'orHaving',
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
            'startLine' => 2815,
            'endLine' => 2815,
            'startColumn' => 30,
            'endColumn' => 36,
            'parameterIndex' => 0,
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
                'startLine' => 2815,
                'endLine' => 2815,
                'startTokenPos' => 11986,
                'startFilePos' => 89232,
                'endTokenPos' => 11986,
                'endFilePos' => 89235,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2815,
            'endLine' => 2815,
            'startColumn' => 39,
            'endColumn' => 54,
            'parameterIndex' => 1,
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
                'startLine' => 2815,
                'endLine' => 2815,
                'startTokenPos' => 11993,
                'startFilePos' => 89247,
                'endTokenPos' => 11993,
                'endFilePos' => 89250,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2815,
            'endLine' => 2815,
            'startColumn' => 57,
            'endColumn' => 69,
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
 * Add an "or having" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|\\Closure|string  $column
 * @param  \\DateTimeInterface|string|int|float|null  $operator
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|\\DateTimeInterface|string|int|float|null  $value
 * @return $this
 */',
        'startLine' => 2815,
        'endLine' => 2822,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingNested' => 
      array (
        'name' => 'havingNested',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2830,
            'endLine' => 2830,
            'startColumn' => 34,
            'endColumn' => 50,
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
                'startLine' => 2830,
                'endLine' => 2830,
                'startTokenPos' => 12067,
                'startFilePos' => 89649,
                'endTokenPos' => 12067,
                'endFilePos' => 89653,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2830,
            'endLine' => 2830,
            'startColumn' => 53,
            'endColumn' => 68,
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
 * Add a nested "having" statement to the query.
 *
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2830,
        'endLine' => 2835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addNestedHavingQuery' => 
      array (
        'name' => 'addNestedHavingQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2844,
            'endLine' => 2844,
            'startColumn' => 42,
            'endColumn' => 47,
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
                'startLine' => 2844,
                'endLine' => 2844,
                'startTokenPos' => 12116,
                'startFilePos' => 90053,
                'endTokenPos' => 12116,
                'endFilePos' => 90057,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2844,
            'endLine' => 2844,
            'startColumn' => 50,
            'endColumn' => 65,
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
 * Add another query builder as a nested having to the query builder.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder  $query
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2844,
        'endLine' => 2855,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingNull' => 
      array (
        'name' => 'havingNull',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2865,
            'endLine' => 2865,
            'startColumn' => 32,
            'endColumn' => 39,
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
                'startLine' => 2865,
                'endLine' => 2865,
                'startTokenPos' => 12214,
                'startFilePos' => 90585,
                'endTokenPos' => 12214,
                'endFilePos' => 90589,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2865,
            'endLine' => 2865,
            'startColumn' => 42,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2865,
                'endLine' => 2865,
                'startTokenPos' => 12221,
                'startFilePos' => 90599,
                'endTokenPos' => 12221,
                'endFilePos' => 90603,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2865,
            'endLine' => 2865,
            'startColumn' => 60,
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
 * Add a "having null" clause to the query.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2865,
        'endLine' => 2874,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHavingNull' => 
      array (
        'name' => 'orHavingNull',
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
            'startLine' => 2882,
            'endLine' => 2882,
            'startColumn' => 34,
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
 * Add an "or having null" clause to the query.
 *
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 2882,
        'endLine' => 2885,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingNotNull' => 
      array (
        'name' => 'havingNotNull',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2894,
            'endLine' => 2894,
            'startColumn' => 35,
            'endColumn' => 42,
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
                'startLine' => 2894,
                'endLine' => 2894,
                'startTokenPos' => 12341,
                'startFilePos' => 91293,
                'endTokenPos' => 12341,
                'endFilePos' => 91297,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2894,
            'endLine' => 2894,
            'startColumn' => 45,
            'endColumn' => 60,
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
 * Add a "having not null" clause to the query.
 *
 * @param  array|string  $columns
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2894,
        'endLine' => 2897,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHavingNotNull' => 
      array (
        'name' => 'orHavingNotNull',
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
            'startLine' => 2905,
            'endLine' => 2905,
            'startColumn' => 37,
            'endColumn' => 43,
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
 * Add an "or having not null" clause to the query.
 *
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 2905,
        'endLine' => 2908,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingBetween' => 
      array (
        'name' => 'havingBetween',
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
            'startLine' => 2918,
            'endLine' => 2918,
            'startColumn' => 35,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2918,
            'endLine' => 2918,
            'startColumn' => 44,
            'endColumn' => 59,
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
                'startLine' => 2918,
                'endLine' => 2918,
                'startTokenPos' => 12412,
                'startFilePos' => 91870,
                'endTokenPos' => 12412,
                'endFilePos' => 91874,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2918,
            'endLine' => 2918,
            'startColumn' => 62,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'not' => 
          array (
            'name' => 'not',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 2918,
                'endLine' => 2918,
                'startTokenPos' => 12419,
                'startFilePos' => 91884,
                'endTokenPos' => 12419,
                'endFilePos' => 91888,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2918,
            'endLine' => 2918,
            'startColumn' => 80,
            'endColumn' => 91,
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
 * Add a "having between" clause to the query.
 *
 * @param  string  $column
 * @param  string  $boolean
 * @param  bool  $not
 * @return $this
 */',
        'startLine' => 2918,
        'endLine' => 2931,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingNotBetween' => 
      array (
        'name' => 'havingNotBetween',
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
            'startLine' => 2941,
            'endLine' => 2941,
            'startColumn' => 38,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2941,
            'endLine' => 2941,
            'startColumn' => 47,
            'endColumn' => 62,
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
                'startLine' => 2941,
                'endLine' => 2941,
                'startTokenPos' => 12559,
                'startFilePos' => 92572,
                'endTokenPos' => 12559,
                'endFilePos' => 92576,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2941,
            'endLine' => 2941,
            'startColumn' => 65,
            'endColumn' => 80,
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
 * Add a "having not between" clause to the query.
 *
 * @param  string  $column
 * @param  iterable  $values
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 2941,
        'endLine' => 2944,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHavingBetween' => 
      array (
        'name' => 'orHavingBetween',
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
            'startLine' => 2953,
            'endLine' => 2953,
            'startColumn' => 37,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2953,
            'endLine' => 2953,
            'startColumn' => 46,
            'endColumn' => 61,
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
 * Add an "or having between" clause to the query.
 *
 * @param  string  $column
 * @param  iterable  $values
 * @return $this
 */',
        'startLine' => 2953,
        'endLine' => 2956,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHavingNotBetween' => 
      array (
        'name' => 'orHavingNotBetween',
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
            'startLine' => 2965,
            'endLine' => 2965,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2965,
            'endLine' => 2965,
            'startColumn' => 49,
            'endColumn' => 64,
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
 * Add an "or having not between" clause to the query.
 *
 * @param  string  $column
 * @param  iterable  $values
 * @return $this
 */',
        'startLine' => 2965,
        'endLine' => 2968,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'resolveDatePeriodBounds' => 
      array (
        'name' => 'resolveDatePeriodBounds',
        'parameters' => 
        array (
          'period' => 
          array (
            'name' => 'period',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DatePeriod',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2976,
            'endLine' => 2976,
            'startColumn' => 48,
            'endColumn' => 65,
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
 * Resolve the start and end dates from a DatePeriod.
 *
 * @param  \\DatePeriod  $period
 * @return array{\\DateTimeInterface, \\DateTimeInterface}
 */',
        'startLine' => 2976,
        'endLine' => 2991,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'havingRaw' => 
      array (
        'name' => 'havingRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3000,
            'endLine' => 3000,
            'startColumn' => 31,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3000,
                'endLine' => 3000,
                'startTokenPos' => 12804,
                'startFilePos' => 94111,
                'endTokenPos' => 12805,
                'endFilePos' => 94112,
              ),
            ),
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
            'startLine' => 3000,
            'endLine' => 3000,
            'startColumn' => 37,
            'endColumn' => 56,
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
                'startLine' => 3000,
                'endLine' => 3000,
                'startTokenPos' => 12812,
                'startFilePos' => 94126,
                'endTokenPos' => 12812,
                'endFilePos' => 94130,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3000,
            'endLine' => 3000,
            'startColumn' => 59,
            'endColumn' => 74,
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
 * Add a raw "having" clause to the query.
 *
 * @param  literal-string  $sql
 * @param  string  $boolean
 * @return $this
 */',
        'startLine' => 3000,
        'endLine' => 3009,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orHavingRaw' => 
      array (
        'name' => 'orHavingRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3017,
            'endLine' => 3017,
            'startColumn' => 33,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3017,
                'endLine' => 3017,
                'startTokenPos' => 12890,
                'startFilePos' => 94512,
                'endTokenPos' => 12891,
                'endFilePos' => 94513,
              ),
            ),
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
            'startLine' => 3017,
            'endLine' => 3017,
            'startColumn' => 39,
            'endColumn' => 58,
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
 * Add a raw "or having" clause to the query.
 *
 * @param  literal-string  $sql
 * @return $this
 */',
        'startLine' => 3017,
        'endLine' => 3020,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orderBy' => 
      array (
        'name' => 'orderBy',
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
            'startLine' => 3031,
            'endLine' => 3031,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'direction' => 
          array (
            'name' => 'direction',
            'default' => 
            array (
              'code' => '\\SortDirection::Ascending',
              'attributes' => 
              array (
                'startLine' => 3031,
                'endLine' => 3031,
                'startTokenPos' => 12929,
                'startFilePos' => 94991,
                'endTokenPos' => 12931,
                'endFilePos' => 95014,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3031,
            'endLine' => 3031,
            'startColumn' => 38,
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
 * Add an "order by" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  SortDirection|\'asc\'|\'desc\'  $direction
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 3031,
        'endLine' => 3057,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orderByDesc' => 
      array (
        'name' => 'orderByDesc',
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
            'startLine' => 3065,
            'endLine' => 3065,
            'startColumn' => 33,
            'endColumn' => 39,
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
 * Add a descending "order by" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 3065,
        'endLine' => 3068,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'latest' => 
      array (
        'name' => 'latest',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'created_at\'',
              'attributes' => 
              array (
                'startLine' => 3076,
                'endLine' => 3076,
                'startTokenPos' => 13187,
                'startFilePos' => 96575,
                'endTokenPos' => 13187,
                'endFilePos' => 96586,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3076,
            'endLine' => 3076,
            'startColumn' => 28,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add an "order by" clause for a timestamp to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 3076,
        'endLine' => 3079,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'oldest' => 
      array (
        'name' => 'oldest',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'created_at\'',
              'attributes' => 
              array (
                'startLine' => 3087,
                'endLine' => 3087,
                'startTokenPos' => 13221,
                'startFilePos' => 96935,
                'endTokenPos' => 13221,
                'endFilePos' => 96946,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3087,
            'endLine' => 3087,
            'startColumn' => 28,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add an "order by" clause for a timestamp to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return $this
 */',
        'startLine' => 3087,
        'endLine' => 3090,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orderByVectorDistance' => 
      array (
        'name' => 'orderByVectorDistance',
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
            'startLine' => 3101,
            'endLine' => 3101,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'vector' => 
          array (
            'name' => 'vector',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3101,
            'endLine' => 3101,
            'startColumn' => 52,
            'endColumn' => 58,
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
 * Add a vector-distance "order by" clause to the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Support\\Collection<int, float>|\\Illuminate\\Contracts\\Support\\Arrayable|array<int, float>  $vector
 * @return $this
 *
 * @throws \\JsonException
 */',
        'startLine' => 3101,
        'endLine' => 3125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'inRandomOrder' => 
      array (
        'name' => 'inRandomOrder',
        'parameters' => 
        array (
          'seed' => 
          array (
            'name' => 'seed',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 3133,
                'endLine' => 3133,
                'startTokenPos' => 13423,
                'startFilePos' => 98311,
                'endTokenPos' => 13423,
                'endFilePos' => 98312,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3133,
            'endLine' => 3133,
            'startColumn' => 35,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Put the query\'s results in random order.
 *
 * @param  string|int  $seed
 * @return $this
 */',
        'startLine' => 3133,
        'endLine' => 3136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'inOrderOf' => 
      array (
        'name' => 'inOrderOf',
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
            'startLine' => 3145,
            'endLine' => 3145,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3145,
            'endLine' => 3145,
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
 * Add an "order by" clause to order results by a given sequence of values.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  \\Illuminate\\Contracts\\Support\\Arrayable|array<\\UnitEnum|string|int|float|bool>  $values
 * @return $this
 */',
        'startLine' => 3145,
        'endLine' => 3166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'orderByRaw' => 
      array (
        'name' => 'orderByRaw',
        'parameters' => 
        array (
          'sql' => 
          array (
            'name' => 'sql',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3175,
            'endLine' => 3175,
            'startColumn' => 32,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3175,
                'endLine' => 3175,
                'startTokenPos' => 13619,
                'startFilePos' => 99509,
                'endTokenPos' => 13620,
                'endFilePos' => 99510,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3175,
            'endLine' => 3175,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * Add a raw "order by" clause to the query.
 *
 * @param  literal-string  $sql
 * @param  array  $bindings
 * @return $this
 */',
        'startLine' => 3175,
        'endLine' => 3184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'skip' => 
      array (
        'name' => 'skip',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3192,
            'endLine' => 3192,
            'startColumn' => 26,
            'endColumn' => 31,
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
 * Alias to set the "offset" value of the query.
 *
 * @param  int  $value
 * @return $this
 */',
        'startLine' => 3192,
        'endLine' => 3195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'offset' => 
      array (
        'name' => 'offset',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3203,
            'endLine' => 3203,
            'startColumn' => 28,
            'endColumn' => 33,
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
 * Set the "offset" value of the query.
 *
 * @param  int  $value
 * @return $this
 */',
        'startLine' => 3203,
        'endLine' => 3210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'take' => 
      array (
        'name' => 'take',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3218,
            'endLine' => 3218,
            'startColumn' => 26,
            'endColumn' => 31,
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
 * Alias to set the "limit" value of the query.
 *
 * @param  int  $value
 * @return $this
 */',
        'startLine' => 3218,
        'endLine' => 3221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'limit' => 
      array (
        'name' => 'limit',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3229,
            'endLine' => 3229,
            'startColumn' => 27,
            'endColumn' => 32,
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
 * Set the "limit" value of the query.
 *
 * @param  int  $value
 * @return $this
 */',
        'startLine' => 3229,
        'endLine' => 3238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'groupLimit' => 
      array (
        'name' => 'groupLimit',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3247,
            'endLine' => 3247,
            'startColumn' => 32,
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
            'startLine' => 3247,
            'endLine' => 3247,
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
 * Add a "group limit" clause to the query.
 *
 * @param  int  $value
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 3247,
        'endLine' => 3254,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forPage' => 
      array (
        'name' => 'forPage',
        'parameters' => 
        array (
          'page' => 
          array (
            'name' => 'page',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3263,
            'endLine' => 3263,
            'startColumn' => 29,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3263,
                'endLine' => 3263,
                'startTokenPos' => 13949,
                'startFilePos' => 101354,
                'endTokenPos' => 13949,
                'endFilePos' => 101355,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3263,
            'endLine' => 3263,
            'startColumn' => 36,
            'endColumn' => 48,
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
 * Set the limit and offset for a given page.
 *
 * @param  int  $page
 * @param  int  $perPage
 * @return $this
 */',
        'startLine' => 3263,
        'endLine' => 3266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forPageBeforeId' => 
      array (
        'name' => 'forPageBeforeId',
        'parameters' => 
        array (
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3276,
                'endLine' => 3276,
                'startTokenPos' => 13993,
                'startFilePos' => 101706,
                'endTokenPos' => 13993,
                'endFilePos' => 101707,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3276,
            'endLine' => 3276,
            'startColumn' => 37,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'lastId' => 
          array (
            'name' => 'lastId',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 3276,
                'endLine' => 3276,
                'startTokenPos' => 14000,
                'startFilePos' => 101720,
                'endTokenPos' => 14000,
                'endFilePos' => 101720,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3276,
            'endLine' => 3276,
            'startColumn' => 52,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 3276,
                'endLine' => 3276,
                'startTokenPos' => 14007,
                'startFilePos' => 101733,
                'endTokenPos' => 14007,
                'endFilePos' => 101736,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3276,
            'endLine' => 3276,
            'startColumn' => 65,
            'endColumn' => 78,
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
 * Constrain the query to the previous "page" of results before a given ID.
 *
 * @param  int  $perPage
 * @param  int|null  $lastId
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 3276,
        'endLine' => 3288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forPageAfterId' => 
      array (
        'name' => 'forPageAfterId',
        'parameters' => 
        array (
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3298,
                'endLine' => 3298,
                'startTokenPos' => 14102,
                'startFilePos' => 102324,
                'endTokenPos' => 14102,
                'endFilePos' => 102325,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3298,
            'endLine' => 3298,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'lastId' => 
          array (
            'name' => 'lastId',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 3298,
                'endLine' => 3298,
                'startTokenPos' => 14109,
                'startFilePos' => 102338,
                'endTokenPos' => 14109,
                'endFilePos' => 102338,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3298,
            'endLine' => 3298,
            'startColumn' => 51,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => '\'id\'',
              'attributes' => 
              array (
                'startLine' => 3298,
                'endLine' => 3298,
                'startTokenPos' => 14116,
                'startFilePos' => 102351,
                'endTokenPos' => 14116,
                'endFilePos' => 102354,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3298,
            'endLine' => 3298,
            'startColumn' => 64,
            'endColumn' => 77,
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
 * Constrain the query to the next "page" of results after a given ID.
 *
 * @param  int  $perPage
 * @param  int|null  $lastId
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 3298,
        'endLine' => 3310,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'reorder' => 
      array (
        'name' => 'reorder',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3319,
                'endLine' => 3319,
                'startTokenPos' => 14211,
                'startFilePos' => 103013,
                'endTokenPos' => 14211,
                'endFilePos' => 103016,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3319,
            'endLine' => 3319,
            'startColumn' => 29,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'direction' => 
          array (
            'name' => 'direction',
            'default' => 
            array (
              'code' => '\\SortDirection::Ascending',
              'attributes' => 
              array (
                'startLine' => 3319,
                'endLine' => 3319,
                'startTokenPos' => 14218,
                'startFilePos' => 103032,
                'endTokenPos' => 14220,
                'endFilePos' => 103055,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3319,
            'endLine' => 3319,
            'startColumn' => 45,
            'endColumn' => 81,
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
 * Remove all existing orders and optionally add a new order.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $column
 * @param  SortDirection|\'asc\'|\'desc\'  $direction
 * @return $this
 */',
        'startLine' => 3319,
        'endLine' => 3331,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'reorderDesc' => 
      array (
        'name' => 'reorderDesc',
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
            'startLine' => 3339,
            'endLine' => 3339,
            'startColumn' => 33,
            'endColumn' => 39,
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
 * Add descending "reorder" clause to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Contracts\\Database\\Query\\Expression|string|null  $column
 * @return $this
 */',
        'startLine' => 3339,
        'endLine' => 3342,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'removeExistingOrdersFor' => 
      array (
        'name' => 'removeExistingOrdersFor',
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
            'startLine' => 3350,
            'endLine' => 3350,
            'startColumn' => 48,
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
 * Get an array with all orders with a given column removed.
 *
 * @param  string  $column
 * @return array
 */',
        'startLine' => 3350,
        'endLine' => 3356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'union' => 
      array (
        'name' => 'union',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3365,
            'endLine' => 3365,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'all' => 
          array (
            'name' => 'all',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 3365,
                'endLine' => 3365,
                'startTokenPos' => 14414,
                'startFilePos' => 104335,
                'endTokenPos' => 14414,
                'endFilePos' => 104339,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3365,
            'endLine' => 3365,
            'startColumn' => 35,
            'endColumn' => 46,
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
 * Add a "union" statement to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $query
 * @param  bool  $all
 * @return $this
 */',
        'startLine' => 3365,
        'endLine' => 3376,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'unionAll' => 
      array (
        'name' => 'unionAll',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3384,
            'endLine' => 3384,
            'startColumn' => 30,
            'endColumn' => 35,
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
 * Add a "union all" statement to the query.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>  $query
 * @return $this
 */',
        'startLine' => 3384,
        'endLine' => 3387,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'lock' => 
      array (
        'name' => 'lock',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 3395,
                'endLine' => 3395,
                'startTokenPos' => 14533,
                'startFilePos' => 105051,
                'endTokenPos' => 14533,
                'endFilePos' => 105054,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3395,
            'endLine' => 3395,
            'startColumn' => 26,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Lock the selected rows in the table.
 *
 * @param  string|bool  $value
 * @return $this
 */',
        'startLine' => 3395,
        'endLine' => 3404,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'lockForUpdate' => 
      array (
        'name' => 'lockForUpdate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Lock the selected rows in the table for updating.
 *
 * @return $this
 */',
        'startLine' => 3411,
        'endLine' => 3414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'sharedLock' => 
      array (
        'name' => 'sharedLock',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Share lock the selected rows in the table.
 *
 * @return $this
 */',
        'startLine' => 3421,
        'endLine' => 3424,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'timeout' => 
      array (
        'name' => 'timeout',
        'parameters' => 
        array (
          'seconds' => 
          array (
            'name' => 'seconds',
            'default' => NULL,
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
                      'name' => 'int',
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
            'startLine' => 3434,
            'endLine' => 3434,
            'startColumn' => 29,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set a query execution timeout in seconds.
 *
 * @param  int|null  $seconds
 * @return $this
 *
 * @throws InvalidArgumentException
 */',
        'startLine' => 3434,
        'endLine' => 3443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'beforeQuery' => 
      array (
        'name' => 'beforeQuery',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3450,
            'endLine' => 3450,
            'startColumn' => 33,
            'endColumn' => 50,
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
 * Register a closure to be invoked before the query is executed.
 *
 * @return $this
 */',
        'startLine' => 3450,
        'endLine' => 3455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'applyBeforeQueryCallbacks' => 
      array (
        'name' => 'applyBeforeQueryCallbacks',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Invoke the "before query" modification callbacks.
 *
 * @return void
 */',
        'startLine' => 3462,
        'endLine' => 3469,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'afterQuery' => 
      array (
        'name' => 'afterQuery',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3476,
            'endLine' => 3476,
            'startColumn' => 32,
            'endColumn' => 48,
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
 * Register a closure to be invoked after the query is executed.
 *
 * @return $this
 */',
        'startLine' => 3476,
        'endLine' => 3481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'applyAfterQueryCallbacks' => 
      array (
        'name' => 'applyAfterQueryCallbacks',
        'parameters' => 
        array (
          'result' => 
          array (
            'name' => 'result',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3489,
            'endLine' => 3489,
            'startColumn' => 46,
            'endColumn' => 52,
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
 * Invoke the "after query" modification callbacks.
 *
 * @param  mixed  $result
 * @return mixed
 */',
        'startLine' => 3489,
        'endLine' => 3496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'toSql' => 
      array (
        'name' => 'toSql',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the SQL representation of the query.
 *
 * @return string
 */',
        'startLine' => 3503,
        'endLine' => 3508,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'toRawSql' => 
      array (
        'name' => 'toRawSql',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the raw SQL representation of the query with embedded bindings.
 *
 * @return string
 */',
        'startLine' => 3515,
        'endLine' => 3520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'find' => 
      array (
        'name' => 'find',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3529,
            'endLine' => 3529,
            'startColumn' => 26,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3529,
                'endLine' => 3529,
                'startTokenPos' => 14950,
                'startFilePos' => 108050,
                'endTokenPos' => 14952,
                'endFilePos' => 108054,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3529,
            'endLine' => 3529,
            'startColumn' => 31,
            'endColumn' => 46,
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
 * Execute a query for a single record by ID.
 *
 * @param  int|string  $id
 * @param  string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @return \\stdClass|null
 */',
        'startLine' => 3529,
        'endLine' => 3532,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'findOr' => 
      array (
        'name' => 'findOr',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3544,
            'endLine' => 3544,
            'startColumn' => 28,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3544,
                'endLine' => 3544,
                'startTokenPos' => 14995,
                'startFilePos' => 108571,
                'endTokenPos' => 14997,
                'endFilePos' => 108575,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3544,
            'endLine' => 3544,
            'startColumn' => 33,
            'endColumn' => 48,
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
                'startLine' => 3544,
                'endLine' => 3544,
                'startTokenPos' => 15007,
                'startFilePos' => 108599,
                'endTokenPos' => 15007,
                'endFilePos' => 108602,
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
            'startLine' => 3544,
            'endLine' => 3544,
            'startColumn' => 51,
            'endColumn' => 75,
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
 * Execute a query for a single record by ID or call a callback.
 *
 * @template TValue
 *
 * @param  mixed  $id
 * @param  (\\Closure(): TValue)|string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @param  (\\Closure(): TValue)|null  $callback
 * @return \\stdClass|TValue
 */',
        'startLine' => 3544,
        'endLine' => 3557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'value' => 
      array (
        'name' => 'value',
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
            'startLine' => 3565,
            'endLine' => 3565,
            'startColumn' => 27,
            'endColumn' => 33,
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
 * Get a single column\'s value from the first result of a query.
 *
 * @param  string  $column
 * @return mixed
 */',
        'startLine' => 3565,
        'endLine' => 3570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'rawValue' => 
      array (
        'name' => 'rawValue',
        'parameters' => 
        array (
          'expression' => 
          array (
            'name' => 'expression',
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
            'startLine' => 3578,
            'endLine' => 3578,
            'startColumn' => 30,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'bindings' => 
          array (
            'name' => 'bindings',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 3578,
                'endLine' => 3578,
                'startTokenPos' => 15154,
                'startFilePos' => 109393,
                'endTokenPos' => 15155,
                'endFilePos' => 109394,
              ),
            ),
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
            'startLine' => 3578,
            'endLine' => 3578,
            'startColumn' => 50,
            'endColumn' => 69,
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
 * Get a single expression value from the first result of a query.
 *
 * @param  literal-string  $expression
 * @return mixed
 */',
        'startLine' => 3578,
        'endLine' => 3583,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'soleValue' => 
      array (
        'name' => 'soleValue',
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
            'startLine' => 3594,
            'endLine' => 3594,
            'startColumn' => 31,
            'endColumn' => 37,
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
 * Get a single column\'s value from the first result of a query if it\'s the sole matching record.
 *
 * @param  string  $column
 * @return mixed
 *
 * @throws \\Illuminate\\Database\\RecordsNotFoundException
 * @throws \\Illuminate\\Database\\MultipleRecordsFoundException
 */',
        'startLine' => 3594,
        'endLine' => 3599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3607,
                'endLine' => 3607,
                'startTokenPos' => 15255,
                'startFilePos' => 110312,
                'endTokenPos' => 15257,
                'endFilePos' => 110316,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3607,
            'endLine' => 3607,
            'startColumn' => 25,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Execute the query as a "select" statement.
 *
 * @param  string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @return \\Illuminate\\Support\\Collection<int, \\stdClass>
 */',
        'startLine' => 3607,
        'endLine' => 3620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'runSelect' => 
      array (
        'name' => 'runSelect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the query as a "select" statement against the connection.
 *
 * @return array
 */',
        'startLine' => 3627,
        'endLine' => 3632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'withoutGroupLimitKeys' => 
      array (
        'name' => 'withoutGroupLimitKeys',
        'parameters' => 
        array (
          'items' => 
          array (
            'name' => 'items',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3640,
            'endLine' => 3640,
            'startColumn' => 46,
            'endColumn' => 51,
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
 * Remove the group limit keys from the results in the collection.
 *
 * @param  \\Illuminate\\Support\\Collection  $items
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 3640,
        'endLine' => 3658,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'paginate' => 
      array (
        'name' => 'paginate',
        'parameters' => 
        array (
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3670,
                'endLine' => 3670,
                'startTokenPos' => 15565,
                'startFilePos' => 112265,
                'endTokenPos' => 15565,
                'endFilePos' => 112266,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3670,
            'endLine' => 3670,
            'startColumn' => 30,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3670,
                'endLine' => 3670,
                'startTokenPos' => 15572,
                'startFilePos' => 112280,
                'endTokenPos' => 15574,
                'endFilePos' => 112284,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3670,
            'endLine' => 3670,
            'startColumn' => 45,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'pageName' => 
          array (
            'name' => 'pageName',
            'default' => 
            array (
              'code' => '\'page\'',
              'attributes' => 
              array (
                'startLine' => 3670,
                'endLine' => 3670,
                'startTokenPos' => 15581,
                'startFilePos' => 112299,
                'endTokenPos' => 15581,
                'endFilePos' => 112304,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3670,
            'endLine' => 3670,
            'startColumn' => 63,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'page' => 
          array (
            'name' => 'page',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3670,
                'endLine' => 3670,
                'startTokenPos' => 15588,
                'startFilePos' => 112315,
                'endTokenPos' => 15588,
                'endFilePos' => 112318,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3670,
            'endLine' => 3670,
            'startColumn' => 83,
            'endColumn' => 94,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'total' => 
          array (
            'name' => 'total',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3670,
                'endLine' => 3670,
                'startTokenPos' => 15595,
                'startFilePos' => 112330,
                'endTokenPos' => 15595,
                'endFilePos' => 112333,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3670,
            'endLine' => 3670,
            'startColumn' => 97,
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
 * Paginate the given query into a simple paginator.
 *
 * @param  int|\\Closure  $perPage
 * @param  string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @param  string  $pageName
 * @param  int|null  $page
 * @param  \\Closure|int|null  $total
 * @return \\Illuminate\\Pagination\\LengthAwarePaginator
 */',
        'startLine' => 3670,
        'endLine' => 3684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'simplePaginate' => 
      array (
        'name' => 'simplePaginate',
        'parameters' => 
        array (
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3697,
                'endLine' => 3697,
                'startTokenPos' => 15734,
                'startFilePos' => 113292,
                'endTokenPos' => 15734,
                'endFilePos' => 113293,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3697,
            'endLine' => 3697,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3697,
                'endLine' => 3697,
                'startTokenPos' => 15741,
                'startFilePos' => 113307,
                'endTokenPos' => 15743,
                'endFilePos' => 113311,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3697,
            'endLine' => 3697,
            'startColumn' => 51,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'pageName' => 
          array (
            'name' => 'pageName',
            'default' => 
            array (
              'code' => '\'page\'',
              'attributes' => 
              array (
                'startLine' => 3697,
                'endLine' => 3697,
                'startTokenPos' => 15750,
                'startFilePos' => 113326,
                'endTokenPos' => 15750,
                'endFilePos' => 113331,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3697,
            'endLine' => 3697,
            'startColumn' => 69,
            'endColumn' => 86,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'page' => 
          array (
            'name' => 'page',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3697,
                'endLine' => 3697,
                'startTokenPos' => 15757,
                'startFilePos' => 113342,
                'endTokenPos' => 15757,
                'endFilePos' => 113345,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3697,
            'endLine' => 3697,
            'startColumn' => 89,
            'endColumn' => 100,
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
 * Get a paginator only supporting simple next and previous links.
 *
 * This is more efficient on larger data-sets, etc.
 *
 * @param  int  $perPage
 * @param  string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @param  string  $pageName
 * @param  int|null  $page
 * @return \\Illuminate\\Contracts\\Pagination\\Paginator
 */',
        'startLine' => 3697,
        'endLine' => 3707,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cursorPaginate' => 
      array (
        'name' => 'cursorPaginate',
        'parameters' => 
        array (
          'perPage' => 
          array (
            'name' => 'perPage',
            'default' => 
            array (
              'code' => '15',
              'attributes' => 
              array (
                'startLine' => 3720,
                'endLine' => 3720,
                'startTokenPos' => 15864,
                'startFilePos' => 114225,
                'endTokenPos' => 15864,
                'endFilePos' => 114226,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3720,
            'endLine' => 3720,
            'startColumn' => 36,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3720,
                'endLine' => 3720,
                'startTokenPos' => 15871,
                'startFilePos' => 114240,
                'endTokenPos' => 15873,
                'endFilePos' => 114244,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3720,
            'endLine' => 3720,
            'startColumn' => 51,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'cursorName' => 
          array (
            'name' => 'cursorName',
            'default' => 
            array (
              'code' => '\'cursor\'',
              'attributes' => 
              array (
                'startLine' => 3720,
                'endLine' => 3720,
                'startTokenPos' => 15880,
                'startFilePos' => 114261,
                'endTokenPos' => 15880,
                'endFilePos' => 114268,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3720,
            'endLine' => 3720,
            'startColumn' => 69,
            'endColumn' => 90,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'cursor' => 
          array (
            'name' => 'cursor',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3720,
                'endLine' => 3720,
                'startTokenPos' => 15887,
                'startFilePos' => 114281,
                'endTokenPos' => 15887,
                'endFilePos' => 114284,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3720,
            'endLine' => 3720,
            'startColumn' => 93,
            'endColumn' => 106,
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
 * Get a paginator only supporting simple next and previous links.
 *
 * This is more efficient on larger data-sets, etc.
 *
 * @param  int|null  $perPage
 * @param  string|\\Illuminate\\Contracts\\Database\\Query\\Expression|array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @param  string  $cursorName
 * @param  \\Illuminate\\Pagination\\Cursor|string|null  $cursor
 * @return \\Illuminate\\Contracts\\Pagination\\CursorPaginator
 */',
        'startLine' => 3720,
        'endLine' => 3723,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'ensureOrderForCursorPagination' => 
      array (
        'name' => 'ensureOrderForCursorPagination',
        'parameters' => 
        array (
          'shouldReverse' => 
          array (
            'name' => 'shouldReverse',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 3731,
                'endLine' => 3731,
                'startTokenPos' => 15925,
                'startFilePos' => 114627,
                'endTokenPos' => 15925,
                'endFilePos' => 114631,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3731,
            'endLine' => 3731,
            'startColumn' => 55,
            'endColumn' => 76,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the proper order by required for cursor pagination.
 *
 * @param  bool  $shouldReverse
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 3731,
        'endLine' => 3757,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getCountForPagination' => 
      array (
        'name' => 'getCountForPagination',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3765,
                'endLine' => 3765,
                'startTokenPos' => 16172,
                'startFilePos' => 115735,
                'endTokenPos' => 16174,
                'endFilePos' => 115739,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3765,
            'endLine' => 3765,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the count of the total records for the paginator.
 *
 * @param  array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @return int<0, max>
 */',
        'startLine' => 3765,
        'endLine' => 3779,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'runPaginationCountQuery' => 
      array (
        'name' => 'runPaginationCountQuery',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 3787,
                'endLine' => 3787,
                'startTokenPos' => 16280,
                'startFilePos' => 116558,
                'endTokenPos' => 16282,
                'endFilePos' => 116562,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3787,
            'endLine' => 3787,
            'startColumn' => 48,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run a pagination count query.
 *
 * @param  array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @return array<mixed>
 */',
        'startLine' => 3787,
        'endLine' => 3809,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cloneForPaginationCount' => 
      array (
        'name' => 'cloneForPaginationCount',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clone the existing query instance for usage in a pagination subquery.
 *
 * @return self
 */',
        'startLine' => 3816,
        'endLine' => 3820,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'withoutSelectAliases' => 
      array (
        'name' => 'withoutSelectAliases',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 3828,
            'endLine' => 3828,
            'startColumn' => 45,
            'endColumn' => 58,
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
 * Remove the column aliases since they will break count queries.
 *
 * @param  array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>  $columns
 * @return array<string|\\Illuminate\\Contracts\\Database\\Query\\Expression>
 */',
        'startLine' => 3828,
        'endLine' => 3835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cursor' => 
      array (
        'name' => 'cursor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a lazy collection for the given query.
 *
 * @return \\Illuminate\\Support\\LazyCollection<int, \\stdClass>
 */',
        'startLine' => 3842,
        'endLine' => 3855,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => true,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'enforceOrderBy' => 
      array (
        'name' => 'enforceOrderBy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Throw an exception if the query doesn\'t have an orderBy clause.
 *
 * @return void
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 3864,
        'endLine' => 3869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'pluck' => 
      array (
        'name' => 'pluck',
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
            'startLine' => 3878,
            'endLine' => 3878,
            'startColumn' => 27,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 3878,
                'endLine' => 3878,
                'startTokenPos' => 16844,
                'startFilePos' => 119700,
                'endTokenPos' => 16844,
                'endFilePos' => 119703,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3878,
            'endLine' => 3878,
            'startColumn' => 36,
            'endColumn' => 46,
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
 * Get a collection instance containing the values of a given column.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @param  string|null  $key
 * @return \\Illuminate\\Support\\Collection<array-key, mixed>
 */',
        'startLine' => 3878,
        'endLine' => 3909,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'stripTableForPluck' => 
      array (
        'name' => 'stripTableForPluck',
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
            'startLine' => 3917,
            'endLine' => 3917,
            'startColumn' => 43,
            'endColumn' => 49,
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
 * Strip off the table name or alias from a column identifier.
 *
 * @param  string  $column
 * @return string|null
 */',
        'startLine' => 3917,
        'endLine' => 3930,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'pluckFromObjectColumn' => 
      array (
        'name' => 'pluckFromObjectColumn',
        'parameters' => 
        array (
          'queryResult' => 
          array (
            'name' => 'queryResult',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3940,
            'endLine' => 3940,
            'startColumn' => 46,
            'endColumn' => 57,
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
            'startLine' => 3940,
            'endLine' => 3940,
            'startColumn' => 60,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3940,
            'endLine' => 3940,
            'startColumn' => 69,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve column values from rows represented as objects.
 *
 * @param  array  $queryResult
 * @param  string  $column
 * @param  string  $key
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 3940,
        'endLine' => 3955,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'pluckFromArrayColumn' => 
      array (
        'name' => 'pluckFromArrayColumn',
        'parameters' => 
        array (
          'queryResult' => 
          array (
            'name' => 'queryResult',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3965,
            'endLine' => 3965,
            'startColumn' => 45,
            'endColumn' => 56,
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
            'startLine' => 3965,
            'endLine' => 3965,
            'startColumn' => 59,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3965,
            'endLine' => 3965,
            'startColumn' => 68,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve column values from rows represented as arrays.
 *
 * @param  array  $queryResult
 * @param  string  $column
 * @param  string  $key
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 3965,
        'endLine' => 3980,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'implode' => 
      array (
        'name' => 'implode',
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
            'startLine' => 3989,
            'endLine' => 3989,
            'startColumn' => 29,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'glue' => 
          array (
            'name' => 'glue',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 3989,
                'endLine' => 3989,
                'startTokenPos' => 17370,
                'startFilePos' => 123003,
                'endTokenPos' => 17370,
                'endFilePos' => 123004,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 3989,
            'endLine' => 3989,
            'startColumn' => 38,
            'endColumn' => 47,
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
 * Concatenate values of a given column as a string.
 *
 * @param  string  $column
 * @param  string  $glue
 * @return string
 */',
        'startLine' => 3989,
        'endLine' => 3992,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'exists' => 
      array (
        'name' => 'exists',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if any rows exist for the current query.
 *
 * @return bool
 */',
        'startLine' => 3999,
        'endLine' => 4017,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'doesntExist' => 
      array (
        'name' => 'doesntExist',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if no rows exist for the current query.
 *
 * @return bool
 */',
        'startLine' => 4024,
        'endLine' => 4027,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'existsOr' => 
      array (
        'name' => 'existsOr',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4034,
            'endLine' => 4034,
            'startColumn' => 30,
            'endColumn' => 46,
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
 * Execute the given callback if no rows exist for the current query.
 *
 * @return mixed
 */',
        'startLine' => 4034,
        'endLine' => 4037,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'doesntExistOr' => 
      array (
        'name' => 'doesntExistOr',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4044,
            'endLine' => 4044,
            'startColumn' => 35,
            'endColumn' => 51,
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
 * Execute the given callback if rows exist for the current query.
 *
 * @return mixed
 */',
        'startLine' => 4044,
        'endLine' => 4047,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'count' => 
      array (
        'name' => 'count',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '\'*\'',
              'attributes' => 
              array (
                'startLine' => 4055,
                'endLine' => 4055,
                'startTokenPos' => 17608,
                'startFilePos' => 124704,
                'endTokenPos' => 17608,
                'endFilePos' => 124706,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4055,
            'endLine' => 4055,
            'startColumn' => 27,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve the "count" result of the query.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $columns
 * @return int<0, max>
 */',
        'startLine' => 4055,
        'endLine' => 4058,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'min' => 
      array (
        'name' => 'min',
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
            'startLine' => 4066,
            'endLine' => 4066,
            'startColumn' => 25,
            'endColumn' => 31,
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
 * Retrieve the minimum value of a given column.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return mixed
 */',
        'startLine' => 4066,
        'endLine' => 4069,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'max' => 
      array (
        'name' => 'max',
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
            'startLine' => 4077,
            'endLine' => 4077,
            'startColumn' => 25,
            'endColumn' => 31,
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
 * Retrieve the maximum value of a given column.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return mixed
 */',
        'startLine' => 4077,
        'endLine' => 4080,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'sum' => 
      array (
        'name' => 'sum',
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
            'startLine' => 4088,
            'endLine' => 4088,
            'startColumn' => 25,
            'endColumn' => 31,
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
 * Retrieve the sum of the values of a given column.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return mixed
 */',
        'startLine' => 4088,
        'endLine' => 4093,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'avg' => 
      array (
        'name' => 'avg',
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
            'startLine' => 4101,
            'endLine' => 4101,
            'startColumn' => 25,
            'endColumn' => 31,
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
 * Retrieve the average of the values of a given column.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return mixed
 */',
        'startLine' => 4101,
        'endLine' => 4104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'average' => 
      array (
        'name' => 'average',
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
            'startLine' => 4112,
            'endLine' => 4112,
            'startColumn' => 29,
            'endColumn' => 35,
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
 * Alias for the "avg" method.
 *
 * @param  \\Illuminate\\Contracts\\Database\\Query\\Expression|string  $column
 * @return mixed
 */',
        'startLine' => 4112,
        'endLine' => 4115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'aggregate' => 
      array (
        'name' => 'aggregate',
        'parameters' => 
        array (
          'function' => 
          array (
            'name' => 'function',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4124,
            'endLine' => 4124,
            'startColumn' => 31,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 4124,
                'endLine' => 4124,
                'startTokenPos' => 17807,
                'startFilePos' => 126419,
                'endTokenPos' => 17809,
                'endFilePos' => 126423,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4124,
            'endLine' => 4124,
            'startColumn' => 42,
            'endColumn' => 57,
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
 * Execute an aggregate function on the database.
 *
 * @param  string  $function
 * @param  array  $columns
 * @return mixed
 */',
        'startLine' => 4124,
        'endLine' => 4134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'numericAggregate' => 
      array (
        'name' => 'numericAggregate',
        'parameters' => 
        array (
          'function' => 
          array (
            'name' => 'function',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4143,
            'endLine' => 4143,
            'startColumn' => 38,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 4143,
                'endLine' => 4143,
                'startTokenPos' => 17934,
                'startFilePos' => 127049,
                'endTokenPos' => 17936,
                'endFilePos' => 127053,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4143,
            'endLine' => 4143,
            'startColumn' => 49,
            'endColumn' => 64,
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
 * Execute a numeric aggregate function on the database.
 *
 * @param  string  $function
 * @param  array  $columns
 * @return float|int
 */',
        'startLine' => 4143,
        'endLine' => 4164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'setAggregate' => 
      array (
        'name' => 'setAggregate',
        'parameters' => 
        array (
          'function' => 
          array (
            'name' => 'function',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4173,
            'endLine' => 4173,
            'startColumn' => 37,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4173,
            'endLine' => 4173,
            'startColumn' => 48,
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
 * Set the aggregate property without running the query.
 *
 * @param  string  $function
 * @param  array<\\Illuminate\\Contracts\\Database\\Query\\Expression|string>  $columns
 * @return $this
 */',
        'startLine' => 4173,
        'endLine' => 4184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insert' => 
      array (
        'name' => 'insert',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4191,
            'endLine' => 4191,
            'startColumn' => 28,
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
 * Insert new records into the database.
 *
 * @return bool
 */',
        'startLine' => 4191,
        'endLine' => 4224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insertOrIgnore' => 
      array (
        'name' => 'insertOrIgnore',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4231,
            'endLine' => 4231,
            'startColumn' => 36,
            'endColumn' => 48,
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
 * Insert new records into the database while ignoring errors.
 *
 * @return int<0, max>
 */',
        'startLine' => 4231,
        'endLine' => 4253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insertOrIgnoreReturning' => 
      array (
        'name' => 'insertOrIgnoreReturning',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4262,
            'endLine' => 4262,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'returning' => 
          array (
            'name' => 'returning',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 4262,
                'endLine' => 4262,
                'startTokenPos' => 18456,
                'startFilePos' => 131000,
                'endTokenPos' => 18458,
                'endFilePos' => 131004,
              ),
            ),
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
            'startLine' => 4262,
            'endLine' => 4262,
            'startColumn' => 60,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'uniqueBy' => 
          array (
            'name' => 'uniqueBy',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 4262,
                'endLine' => 4262,
                'startTokenPos' => 18471,
                'startFilePos' => 131037,
                'endTokenPos' => 18471,
                'endFilePos' => 131040,
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
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
            'startLine' => 4262,
            'endLine' => 4262,
            'startColumn' => 86,
            'endColumn' => 119,
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
 * Insert new records into the database and returning specified columns with optional ignoring specific conflicts.
 *
 * @param  non-empty-array<non-empty-string>  $returning
 * @param  non-empty-string|non-empty-array<non-empty-string>|null  $uniqueBy
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 4262,
        'endLine' => 4297,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insertGetId' => 
      array (
        'name' => 'insertGetId',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4305,
            'endLine' => 4305,
            'startColumn' => 33,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sequence' => 
          array (
            'name' => 'sequence',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 4305,
                'endLine' => 4305,
                'startTokenPos' => 18743,
                'startFilePos' => 132313,
                'endTokenPos' => 18743,
                'endFilePos' => 132316,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4305,
            'endLine' => 4305,
            'startColumn' => 48,
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
 * Insert a new record and get the value of the primary key.
 *
 * @param  string|null  $sequence
 * @return int
 */',
        'startLine' => 4305,
        'endLine' => 4314,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insertUsing' => 
      array (
        'name' => 'insertUsing',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 4322,
            'endLine' => 4322,
            'startColumn' => 33,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4322,
            'endLine' => 4322,
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
 * Insert new records into the table using a subquery.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @return int
 */',
        'startLine' => 4322,
        'endLine' => 4332,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'insertOrIgnoreUsing' => 
      array (
        'name' => 'insertOrIgnoreUsing',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 4340,
            'endLine' => 4340,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4340,
            'endLine' => 4340,
            'startColumn' => 57,
            'endColumn' => 62,
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
 * Insert new records into the table using a subquery while ignoring errors.
 *
 * @param  \\Closure|\\Illuminate\\Database\\Query\\Builder|\\Illuminate\\Database\\Eloquent\\Builder<*>|string  $query
 * @return int
 */',
        'startLine' => 4340,
        'endLine' => 4350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4357,
            'endLine' => 4357,
            'startColumn' => 28,
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
 * Update records in the database.
 *
 * @return int<0, max>
 */',
        'startLine' => 4357,
        'endLine' => 4380,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'updateFrom' => 
      array (
        'name' => 'updateFrom',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4389,
            'endLine' => 4389,
            'startColumn' => 32,
            'endColumn' => 44,
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
 * Update records in a PostgreSQL database using the update from syntax.
 *
 * @return int
 *
 * @throws \\LogicException
 */',
        'startLine' => 4389,
        'endLine' => 4402,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'updateOrInsert' => 
      array (
        'name' => 'updateOrInsert',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 4409,
            'endLine' => 4409,
            'startColumn' => 36,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'values' => 
          array (
            'name' => 'values',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 4409,
                'endLine' => 4409,
                'startTokenPos' => 19387,
                'startFilePos' => 135777,
                'endTokenPos' => 19388,
                'endFilePos' => 135778,
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'callable',
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
            'startLine' => 4409,
            'endLine' => 4409,
            'startColumn' => 55,
            'endColumn' => 81,
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
 * Insert or update a record matching the attributes, and fill it with values.
 *
 * @return bool
 */',
        'startLine' => 4409,
        'endLine' => 4426,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'upsert' => 
      array (
        'name' => 'upsert',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 4434,
            'endLine' => 4434,
            'startColumn' => 28,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'uniqueBy' => 
          array (
            'name' => 'uniqueBy',
            'default' => NULL,
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
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
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
            'startLine' => 4434,
            'endLine' => 4434,
            'startColumn' => 43,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'update' => 
          array (
            'name' => 'update',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 4434,
                'endLine' => 4434,
                'startTokenPos' => 19525,
                'startFilePos' => 136432,
                'endTokenPos' => 19525,
                'endFilePos' => 136435,
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
                      'name' => 'array',
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
            'startLine' => 4434,
            'endLine' => 4434,
            'startColumn' => 67,
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
 * Insert new records or update the existing ones.
 *
 * @param  non-empty-string|non-empty-array<int, non-empty-string>  $uniqueBy
 * @return int
 */',
        'startLine' => 4434,
        'endLine' => 4473,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'increment' => 
      array (
        'name' => 'increment',
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
            'startLine' => 4484,
            'endLine' => 4484,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 4484,
                'endLine' => 4484,
                'startTokenPos' => 19820,
                'startFilePos' => 137806,
                'endTokenPos' => 19820,
                'endFilePos' => 137806,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4484,
            'endLine' => 4484,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 4484,
                'endLine' => 4484,
                'startTokenPos' => 19829,
                'startFilePos' => 137824,
                'endTokenPos' => 19830,
                'endFilePos' => 137825,
              ),
            ),
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
            'startLine' => 4484,
            'endLine' => 4484,
            'startColumn' => 53,
            'endColumn' => 69,
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
 * Increment a column\'s value by a given amount.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @return int<0, max>
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4484,
        'endLine' => 4491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'incrementEach' => 
      array (
        'name' => 'incrementEach',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 4502,
            'endLine' => 4502,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 4502,
                'endLine' => 4502,
                'startTokenPos' => 19900,
                'startFilePos' => 138390,
                'endTokenPos' => 19901,
                'endFilePos' => 138391,
              ),
            ),
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
            'startLine' => 4502,
            'endLine' => 4502,
            'startColumn' => 51,
            'endColumn' => 67,
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
 * Increment the given column\'s values by the given amounts.
 *
 * @param  array<string, float|int|numeric-string>  $columns
 * @param  array<string, mixed>  $extra
 * @return int<0, max>
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4502,
        'endLine' => 4515,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'decrement' => 
      array (
        'name' => 'decrement',
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
            'startLine' => 4526,
            'endLine' => 4526,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 4526,
                'endLine' => 4526,
                'startTokenPos' => 20039,
                'startFilePos' => 139218,
                'endTokenPos' => 20039,
                'endFilePos' => 139218,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4526,
            'endLine' => 4526,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 4526,
                'endLine' => 4526,
                'startTokenPos' => 20048,
                'startFilePos' => 139236,
                'endTokenPos' => 20049,
                'endFilePos' => 139237,
              ),
            ),
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
            'startLine' => 4526,
            'endLine' => 4526,
            'startColumn' => 53,
            'endColumn' => 69,
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
 * Decrement a column\'s value by a given amount.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @return int<0, max>
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4526,
        'endLine' => 4533,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'decrementEach' => 
      array (
        'name' => 'decrementEach',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
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
            'startLine' => 4544,
            'endLine' => 4544,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 4544,
                'endLine' => 4544,
                'startTokenPos' => 20119,
                'startFilePos' => 139802,
                'endTokenPos' => 20120,
                'endFilePos' => 139803,
              ),
            ),
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
            'startLine' => 4544,
            'endLine' => 4544,
            'startColumn' => 51,
            'endColumn' => 67,
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
 * Decrement the given column\'s values by the given amounts.
 *
 * @param  array<string, float|int|numeric-string>  $columns
 * @param  array<string, mixed>  $extra
 * @return int<0, max>
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4544,
        'endLine' => 4557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 4565,
                'endLine' => 4565,
                'startTokenPos' => 20255,
                'startFilePos' => 140507,
                'endTokenPos' => 20255,
                'endFilePos' => 140510,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4565,
            'endLine' => 4565,
            'startColumn' => 28,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete records from the database.
 *
 * @param  mixed  $id
 * @return int
 */',
        'startLine' => 4565,
        'endLine' => 4581,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'truncate' => 
      array (
        'name' => 'truncate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run a "truncate" statement on the table.
 *
 * @return void
 */',
        'startLine' => 4588,
        'endLine' => 4595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'newQuery' => 
      array (
        'name' => 'newQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new instance of the query builder.
 *
 * @return \\Illuminate\\Database\\Query\\Builder
 */',
        'startLine' => 4602,
        'endLine' => 4605,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'forSubQuery' => 
      array (
        'name' => 'forSubQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new query instance for a sub-query.
 *
 * @return \\Illuminate\\Database\\Query\\Builder
 */',
        'startLine' => 4612,
        'endLine' => 4615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getColumns' => 
      array (
        'name' => 'getColumns',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get all of the query builder\'s columns in a text-only array with all expressions evaluated.
 *
 * @return list<string>
 */',
        'startLine' => 4622,
        'endLine' => 4627,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'raw' => 
      array (
        'name' => 'raw',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4635,
            'endLine' => 4635,
            'startColumn' => 25,
            'endColumn' => 30,
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
 * Create a raw database expression.
 *
 * @param  literal-string|int|float  $value
 * @return \\Illuminate\\Contracts\\Database\\Query\\Expression
 */',
        'startLine' => 4635,
        'endLine' => 4638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getUnionBuilders' => 
      array (
        'name' => 'getUnionBuilders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the query builder instances that are used in the union of the query.
 *
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 4645,
        'endLine' => 4650,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getLimit' => 
      array (
        'name' => 'getLimit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "limit" value for the query or null if it\'s not set.
 *
 * @return mixed
 */',
        'startLine' => 4657,
        'endLine' => 4662,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getOffset' => 
      array (
        'name' => 'getOffset',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the "offset" value for the query or null if it\'s not set.
 *
 * @return mixed
 */',
        'startLine' => 4669,
        'endLine' => 4674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getBindings' => 
      array (
        'name' => 'getBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current query value bindings in a flattened array.
 *
 * @return list<mixed>
 */',
        'startLine' => 4681,
        'endLine' => 4684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getRawBindings' => 
      array (
        'name' => 'getRawBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the raw array of bindings.
 *
 * @return array{
 *      select: list<mixed>,
 *      from: list<mixed>,
 *      join: list<mixed>,
 *      where: list<mixed>,
 *      groupBy: list<mixed>,
 *      having: list<mixed>,
 *      order: list<mixed>,
 *      union: list<mixed>,
 *      unionOrder: list<mixed>,
 * }
 */',
        'startLine' => 4701,
        'endLine' => 4704,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'setBindings' => 
      array (
        'name' => 'setBindings',
        'parameters' => 
        array (
          'bindings' => 
          array (
            'name' => 'bindings',
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
            'startLine' => 4715,
            'endLine' => 4715,
            'startColumn' => 33,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'where\'',
              'attributes' => 
              array (
                'startLine' => 4715,
                'endLine' => 4715,
                'startTokenPos' => 20773,
                'startFilePos' => 144437,
                'endTokenPos' => 20773,
                'endFilePos' => 144443,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4715,
            'endLine' => 4715,
            'startColumn' => 50,
            'endColumn' => 64,
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
 * Set the bindings on the query builder.
 *
 * @param  list<mixed>  $bindings
 * @param  "select"|"from"|"join"|"where"|"groupBy"|"having"|"order"|"union"|"unionOrder"  $type
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4715,
        'endLine' => 4724,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'addBinding' => 
      array (
        'name' => 'addBinding',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4735,
            'endLine' => 4735,
            'startColumn' => 32,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'where\'',
              'attributes' => 
              array (
                'startLine' => 4735,
                'endLine' => 4735,
                'startTokenPos' => 20848,
                'startFilePos' => 144981,
                'endTokenPos' => 20848,
                'endFilePos' => 144987,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4735,
            'endLine' => 4735,
            'startColumn' => 40,
            'endColumn' => 54,
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
 * Add a binding to the query.
 *
 * @param  mixed  $value
 * @param  "select"|"from"|"join"|"where"|"groupBy"|"having"|"order"|"union"|"unionOrder"  $type
 * @return $this
 *
 * @throws \\InvalidArgumentException
 */',
        'startLine' => 4735,
        'endLine' => 4751,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'castBinding' => 
      array (
        'name' => 'castBinding',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4759,
            'endLine' => 4759,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Cast the given binding value.
 *
 * @param  mixed  $value
 * @return mixed
 */',
        'startLine' => 4759,
        'endLine' => 4762,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'mergeBindings' => 
      array (
        'name' => 'mergeBindings',
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
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4770,
            'endLine' => 4770,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * Merge an array of bindings into our bindings.
 *
 * @param  self  $query
 * @return $this
 */',
        'startLine' => 4770,
        'endLine' => 4775,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cleanBindings' => 
      array (
        'name' => 'cleanBindings',
        'parameters' => 
        array (
          'bindings' => 
          array (
            'name' => 'bindings',
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
            'startLine' => 4783,
            'endLine' => 4783,
            'startColumn' => 35,
            'endColumn' => 49,
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
 * Remove all of the expressions from a list of bindings.
 *
 * @param  array<mixed>  $bindings
 * @return list<mixed>
 */',
        'startLine' => 4783,
        'endLine' => 4792,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'flattenValue' => 
      array (
        'name' => 'flattenValue',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4800,
            'endLine' => 4800,
            'startColumn' => 37,
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
 * Get a scalar type value from an unknown type of input.
 *
 * @param  mixed  $value
 * @return mixed
 */',
        'startLine' => 4800,
        'endLine' => 4803,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'defaultKeyName' => 
      array (
        'name' => 'defaultKeyName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default key name of the table.
 *
 * @return string
 */',
        'startLine' => 4810,
        'endLine' => 4813,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getConnection' => 
      array (
        'name' => 'getConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the database connection instance.
 *
 * @return \\Illuminate\\Database\\ConnectionInterface
 */',
        'startLine' => 4820,
        'endLine' => 4823,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'ensureConnectionSupportsVectors' => 
      array (
        'name' => 'ensureConnectionSupportsVectors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the database connection supports vector queries.
 *
 * @return void
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 4832,
        'endLine' => 4837,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getProcessor' => 
      array (
        'name' => 'getProcessor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the database query processor instance.
 *
 * @return \\Illuminate\\Database\\Query\\Processors\\Processor
 */',
        'startLine' => 4844,
        'endLine' => 4847,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'getGrammar' => 
      array (
        'name' => 'getGrammar',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the query grammar instance.
 *
 * @return \\Illuminate\\Database\\Query\\Grammars\\Grammar
 */',
        'startLine' => 4854,
        'endLine' => 4857,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'useWritePdo' => 
      array (
        'name' => 'useWritePdo',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Use the "write" PDO connection when executing the query.
 *
 * @return $this
 */',
        'startLine' => 4864,
        'endLine' => 4869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'fetchUsing' => 
      array (
        'name' => 'fetchUsing',
        'parameters' => 
        array (
          'fetchUsing' => 
          array (
            'name' => 'fetchUsing',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4877,
            'endLine' => 4877,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Specify arguments for the PDOStatement::fetchAll / fetch functions.
 *
 * @param  mixed  ...$fetchUsing
 * @return $this
 */',
        'startLine' => 4877,
        'endLine' => 4882,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'isQueryable' => 
      array (
        'name' => 'isQueryable',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4890,
            'endLine' => 4890,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Determine if the value is a query builder instance or a Closure.
 *
 * @param  mixed  $value
 * @return bool
 */',
        'startLine' => 4890,
        'endLine' => 4896,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'clone' => 
      array (
        'name' => 'clone',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clone the query.
 *
 * @return static
 */',
        'startLine' => 4903,
        'endLine' => 4906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cloneWithout' => 
      array (
        'name' => 'cloneWithout',
        'parameters' => 
        array (
          'properties' => 
          array (
            'name' => 'properties',
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
            'startLine' => 4913,
            'endLine' => 4913,
            'startColumn' => 34,
            'endColumn' => 50,
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
 * Clone the query without the given properties.
 *
 * @return static
 */',
        'startLine' => 4913,
        'endLine' => 4920,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'cloneWithoutBindings' => 
      array (
        'name' => 'cloneWithoutBindings',
        'parameters' => 
        array (
          'except' => 
          array (
            'name' => 'except',
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
            'startLine' => 4927,
            'endLine' => 4927,
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
 * Clone the query without the given bindings.
 *
 * @return static
 */',
        'startLine' => 4927,
        'endLine' => 4934,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'dump' => 
      array (
        'name' => 'dump',
        'parameters' => 
        array (
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4942,
            'endLine' => 4942,
            'startColumn' => 26,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the current SQL and bindings.
 *
 * @param  mixed  ...$args
 * @return $this
 */',
        'startLine' => 4942,
        'endLine' => 4951,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'dumpRawSql' => 
      array (
        'name' => 'dumpRawSql',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the raw current SQL with embedded bindings.
 *
 * @return $this
 */',
        'startLine' => 4958,
        'endLine' => 4963,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'dd' => 
      array (
        'name' => 'dd',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Die and dump the current SQL and bindings.
 *
 * @return never
 */',
        'startLine' => 4970,
        'endLine' => 4973,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      'ddRawSql' => 
      array (
        'name' => 'ddRawSql',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Die and dump the current SQL with embedded bindings.
 *
 * @return never
 */',
        'startLine' => 4980,
        'endLine' => 4983,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4994,
            'endLine' => 4994,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 4994,
            'endLine' => 4994,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Handle dynamic method calls into the method.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 *
 * @throws \\BadMethodCallException
 */',
        'startLine' => 4994,
        'endLine' => 5005,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Query',
        'declaringClassName' => 'Illuminate\\Database\\Query\\Builder',
        'implementingClassName' => 'Illuminate\\Database\\Query\\Builder',
        'currentClassName' => 'Illuminate\\Database\\Query\\Builder',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\database\\concerns\\buildswheredateclauses::__call',
          ),
        ),
        'Illuminate\\Database\\Concerns\\BuildsQueries' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\database\\concerns\\buildsqueries::__call',
          ),
        ),
        'Illuminate\\Database\\Concerns\\ExplainsQueries' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\database\\concerns\\explainsqueries::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\ForwardsCalls' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\forwardscalls::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Macroable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\macroable::__call',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'illuminate\\database\\concerns\\buildswheredateclauses::__call' => 'Illuminate\\Database\\Concerns\\BuildsWhereDateClauses::__call',
        'illuminate\\database\\concerns\\buildsqueries::__call' => 'Illuminate\\Database\\Concerns\\BuildsQueries::__call',
        'illuminate\\database\\concerns\\explainsqueries::__call' => 'Illuminate\\Database\\Concerns\\ExplainsQueries::__call',
        'illuminate\\support\\traits\\forwardscalls::__call' => 'Illuminate\\Support\\Traits\\ForwardsCalls::__call',
        'illuminate\\support\\traits\\macroable::__call' => 'Illuminate\\Support\\Traits\\Macroable::__call',
      ),
    ),
  ),
));