<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/PlatformAdmin.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\PlatformAdmin
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-16706d88ec2b3644d0cc05ca875ba3a966fc5ccc2918ac3197f8d2d0a38157c1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\PlatformAdmin',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/PlatformAdmin.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\PlatformAdmin',
    'shortName' => 'PlatformAdmin',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A "root user" — a platform-level operator who can create organisations
 * and assign their first admin. Entirely separate from the tenant `users`
 * table and never reachable through a tenant domain. See MEP.md 3.3.
 */',
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
            'code' => '[\'name\', \'email\', \'password\']',
            'attributes' => 
            array (
              'startLine' => 20,
              'endLine' => 20,
              'startTokenPos' => 59,
              'startFilePos' => 652,
              'endTokenPos' => 67,
              'endFilePos' => 680,
            ),
          ),
        ),
      ),
      1 => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Attributes\\Hidden',
        'isRepeated' => false,
        'arguments' => 
        array (
          0 => 
          array (
            'code' => '[\'password\', \'remember_token\']',
            'attributes' => 
            array (
              'startLine' => 21,
              'endLine' => 21,
              'startTokenPos' => 74,
              'startFilePos' => 693,
              'endTokenPos' => 79,
              'endFilePos' => 722,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 20,
    'endLine' => 42,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Foundation\\Auth\\User',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Illuminate\\Notifications\\Notifiable',
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
        'startLine' => 27,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PlatformAdmin',
        'implementingClassName' => 'App\\Models\\PlatformAdmin',
        'currentClassName' => 'App\\Models\\PlatformAdmin',
        'aliasName' => NULL,
      ),
      'organisationsCreated' => 
      array (
        'name' => 'organisationsCreated',
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
 * @return HasMany<Organisation, $this>
 */',
        'startLine' => 38,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PlatformAdmin',
        'implementingClassName' => 'App\\Models\\PlatformAdmin',
        'currentClassName' => 'App\\Models\\PlatformAdmin',
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