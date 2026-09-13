<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/Plan.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\Plan
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-028b4b43bd9b61965d16a5360b39c38d8855c12d548a8ee9355928312ed6efd4',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\Plan',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Plan.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\Plan',
    'shortName' => 'Plan',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
      0 => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '[\'organisation_id\', \'name\', \'description\', \'price_minor\', \'currency_code\', \'duration_days\', \'session_limit\', \'status\']',
            'attributes' => 
            array (
              'startLine' => 15,
              'endLine' => 15,
              'startTokenPos' => 53,
              'startFilePos' => 378,
              'endTokenPos' => 76,
              'endFilePos' => 495,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 15,
    'endLine' => 38,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Models\\Concerns\\BelongsToOrganisation',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'casts' => 
      array (
        'name' => 'casts',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\Plan',
        'implementingClassName' => 'App\\Models\\Plan',
        'currentClassName' => 'App\\Models\\Plan',
        'aliasName' => NULL,
      ),
      'subscriptions' => 
      array (
        'name' => 'subscriptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<MemberSubscription, $this>
 */',
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\Plan',
        'implementingClassName' => 'App\\Models\\Plan',
        'currentClassName' => 'App\\Models\\Plan',
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