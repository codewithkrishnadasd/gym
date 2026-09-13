<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/Scopes/OrganisationScope.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\Scopes\OrganisationScope
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-d2b3acce339d013a3a27a40cd37e0c4fe1bb8fa3c343598dcbb4a6d34f7b743d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\Scopes\\OrganisationScope',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Scopes/OrganisationScope.php',
      ),
    ),
    'namespace' => 'App\\Models\\Scopes',
    'name' => 'App\\Models\\Scopes\\OrganisationScope',
    'shortName' => 'OrganisationScope',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Constrains every query on a tenant-scoped model to the currently resolved
 * organisation, so a query without an explicit tenant filter still cannot
 * cross a tenant boundary. Bound by `ResolveTenant` middleware; there is no
 * bound tenant outside an HTTP request (e.g. console commands), so those
 * call sites must use `withoutGlobalScope` and an explicit filter instead —
 * see technology.md Section 2.2.
 *
 * @implements Scope<Model>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 32,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Scope',
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
      'apply' => 
      array (
        'name' => 'apply',
        'parameters' => 
        array (
          'builder' => 
          array (
            'name' => 'builder',
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
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 27,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 26,
            'endLine' => 26,
            'startColumn' => 45,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  Builder<covariant Model>  $builder
 */',
        'startLine' => 26,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models\\Scopes',
        'declaringClassName' => 'App\\Models\\Scopes\\OrganisationScope',
        'implementingClassName' => 'App\\Models\\Scopes\\OrganisationScope',
        'currentClassName' => 'App\\Models\\Scopes\\OrganisationScope',
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