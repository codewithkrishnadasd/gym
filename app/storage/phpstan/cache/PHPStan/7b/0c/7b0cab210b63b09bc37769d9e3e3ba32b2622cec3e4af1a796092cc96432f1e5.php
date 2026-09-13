<?php declare(strict_types = 1);

// osfsl-/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Application.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Foundation\Application
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-842ce90400eeba748b1821b3e33b5a23322e2ad9cc9381e0a980052fa0e352ba-8.4.4-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Foundation\\Application',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Foundation/Application.php',
      ),
    ),
    'namespace' => 'Illuminate\\Foundation',
    'name' => 'Illuminate\\Foundation\\Application',
    'shortName' => 'Application',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 1743,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Container\\Container',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Foundation\\Application',
      1 => 'Illuminate\\Contracts\\Foundation\\CachesConfiguration',
      2 => 'Illuminate\\Contracts\\Foundation\\CachesRoutes',
      3 => 'Symfony\\Component\\HttpKernel\\HttpKernelInterface',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
      'VERSION' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'VERSION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'13.31.0\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 229,
            'startFilePos' => 1796,
            'endTokenPos' => 229,
            'endFilePos' => 1804,
          ),
        ),
        'docComment' => '/**
 * The Laravel framework version.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 30,
      ),
    ),
    'immediateProperties' => 
    array (
      'basePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'basePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The base path for the Laravel installation.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'registeredCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'registeredCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 62,
            'endLine' => 62,
            'startTokenPos' => 247,
            'startFilePos' => 2052,
            'endTokenPos' => 248,
            'endFilePos' => 2053,
          ),
        ),
        'docComment' => '/**
 * The array of registered callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 62,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hasBeenBootstrapped' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'hasBeenBootstrapped',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 259,
            'startFilePos' => 2200,
            'endTokenPos' => 259,
            'endFilePos' => 2204,
          ),
        ),
        'docComment' => '/**
 * Indicates if the application has been bootstrapped before.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'booted' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'booted',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 76,
            'endLine' => 76,
            'startTokenPos' => 270,
            'startFilePos' => 2322,
            'endTokenPos' => 270,
            'endFilePos' => 2326,
          ),
        ),
        'docComment' => '/**
 * Indicates if the application has "booted".
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 76,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootingCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootingCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 281,
            'startFilePos' => 2449,
            'endTokenPos' => 282,
            'endFilePos' => 2450,
          ),
        ),
        'docComment' => '/**
 * The array of booting callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootedCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootedCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 90,
            'endLine' => 90,
            'startTokenPos' => 293,
            'startFilePos' => 2571,
            'endTokenPos' => 294,
            'endFilePos' => 2572,
          ),
        ),
        'docComment' => '/**
 * The array of booted callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 90,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'terminatingCallbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'terminatingCallbacks',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 305,
            'startFilePos' => 2703,
            'endTokenPos' => 306,
            'endFilePos' => 2704,
          ),
        ),
        'docComment' => '/**
 * The array of terminating callbacks.
 *
 * @var callable[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'serviceProviders' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'serviceProviders',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 317,
            'startFilePos' => 2876,
            'endTokenPos' => 318,
            'endFilePos' => 2877,
          ),
        ),
        'docComment' => '/**
 * All of the registered service providers.
 *
 * @var array<string, \\Illuminate\\Support\\ServiceProvider>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'loadedProviders' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'loadedProviders',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 111,
            'endLine' => 111,
            'startTokenPos' => 329,
            'startFilePos' => 3005,
            'endTokenPos' => 330,
            'endFilePos' => 3006,
          ),
        ),
        'docComment' => '/**
 * The names of the loaded service providers.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 111,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'deferredServices' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'deferredServices',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 118,
            'startTokenPos' => 341,
            'startFilePos' => 3135,
            'endTokenPos' => 342,
            'endFilePos' => 3136,
          ),
        ),
        'docComment' => '/**
 * The deferred services and their providers.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bootstrapPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'bootstrapPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom bootstrap path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'appPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'appPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom application path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'configPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'configPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom configuration path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 139,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'databasePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'databasePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom database path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 146,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'langPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'langPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom language file path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 153,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'publicPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'publicPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom public / web path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 160,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'storagePath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'storagePath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom storage path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 167,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'environmentPath' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'environmentPath',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The custom environment path defined by the developer.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 174,
        'endLine' => 174,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'environmentFile' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'environmentFile',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'.env\'',
          'attributes' => 
          array (
            'startLine' => 181,
            'endLine' => 181,
            'startTokenPos' => 409,
            'startFilePos' => 4323,
            'endTokenPos' => 409,
            'endFilePos' => 4328,
          ),
        ),
        'docComment' => '/**
 * The environment file to load during bootstrapping.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 181,
        'endLine' => 181,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isRunningInConsole' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'isRunningInConsole',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates if the application is running in the console.
 *
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 188,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'namespace' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'namespace',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The application namespace.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 195,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mergeFrameworkConfiguration' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'mergeFrameworkConfiguration',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 202,
            'endLine' => 202,
            'startTokenPos' => 434,
            'startFilePos' => 4737,
            'endTokenPos' => 434,
            'endFilePos' => 4740,
          ),
        ),
        'docComment' => '/**
 * Indicates if the framework\'s base configuration should be merged.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 202,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'absoluteCachePathPrefixes' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'absoluteCachePathPrefixes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'/\', \'\\\\\']',
          'attributes' => 
          array (
            'startLine' => 209,
            'endLine' => 209,
            'startTokenPos' => 445,
            'startFilePos' => 4905,
            'endTokenPos' => 450,
            'endFilePos' => 4915,
          ),
        ),
        'docComment' => '/**
 * The prefixes of absolute cache paths for use during normalization.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 209,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 55,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'applicationBuilder' => 
      array (
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'name' => 'applicationBuilder',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Illuminate\\Foundation\\Configuration\\ApplicationBuilder::class',
          'attributes' => 
          array (
            'startLine' => 216,
            'endLine' => 216,
            'startTokenPos' => 465,
            'startFilePos' => 5089,
            'endTokenPos' => 467,
            'endFilePos' => 5127,
          ),
        ),
        'docComment' => '/**
 * The application builder class.
 *
 * @var class-string<Configuration\\ApplicationBuilder>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 216,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 90,
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
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 223,
                'endLine' => 223,
                'startTokenPos' => 482,
                'startFilePos' => 5289,
                'endTokenPos' => 482,
                'endFilePos' => 5292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 223,
            'endLine' => 223,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Create a new Illuminate application instance.
 *
 * @param  string|null  $basePath
 */',
        'startLine' => 223,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configure' => 
      array (
        'name' => 'configure',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 241,
                'endLine' => 241,
                'startTokenPos' => 552,
                'startFilePos' => 5817,
                'endTokenPos' => 552,
                'endFilePos' => 5820,
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
                      'name' => 'string',
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
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 38,
            'endColumn' => 61,
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
 * Begin configuring a new Laravel application instance.
 *
 * @param  string|null  $basePath
 * @return \\Illuminate\\Foundation\\Configuration\\ApplicationBuilder
 */',
        'startLine' => 241,
        'endLine' => 253,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'inferBasePath' => 
      array (
        'name' => 'inferBasePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Infer the application\'s base directory from the environment.
 *
 * @return string
 */',
        'startLine' => 260,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'version' => 
      array (
        'name' => 'version',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the version number of the application.
 *
 * @return string
 */',
        'startLine' => 277,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerBaseBindings' => 
      array (
        'name' => 'registerBaseBindings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the basic bindings into the container.
 *
 * @return void
 */',
        'startLine' => 287,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerBaseServiceProviders' => 
      array (
        'name' => 'registerBaseServiceProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register all of the base service providers.
 *
 * @return void
 */',
        'startLine' => 306,
        'endLine' => 312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerLaravelCloudServices' => 
      array (
        'name' => 'registerLaravelCloudServices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register any services needed for Laravel Cloud.
 *
 * @return void
 */',
        'startLine' => 319,
        'endLine' => 334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootstrapWith' => 
      array (
        'name' => 'bootstrapWith',
        'parameters' => 
        array (
          'bootstrappers' => 
          array (
            'name' => 'bootstrappers',
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
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 35,
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
 * Run the given array of bootstrap classes.
 *
 * @param  string[]  $bootstrappers
 * @return void
 */',
        'startLine' => 342,
        'endLine' => 353,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'afterLoadingEnvironment' => 
      array (
        'name' => 'afterLoadingEnvironment',
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
            'startLine' => 361,
            'endLine' => 361,
            'startColumn' => 45,
            'endColumn' => 61,
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
 * Register a callback to run after loading the environment.
 *
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 361,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'beforeBootstrapping' => 
      array (
        'name' => 'beforeBootstrapping',
        'parameters' => 
        array (
          'bootstrapper' => 
          array (
            'name' => 'bootstrapper',
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
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 375,
            'endLine' => 375,
            'startColumn' => 56,
            'endColumn' => 72,
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
 * Register a callback to run before a bootstrapper.
 *
 * @param  string  $bootstrapper
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 375,
        'endLine' => 378,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'afterBootstrapping' => 
      array (
        'name' => 'afterBootstrapping',
        'parameters' => 
        array (
          'bootstrapper' => 
          array (
            'name' => 'bootstrapper',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 40,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 387,
            'endLine' => 387,
            'startColumn' => 55,
            'endColumn' => 71,
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
 * Register a callback to run after a bootstrapper.
 *
 * @param  string  $bootstrapper
 * @param  \\Closure  $callback
 * @return void
 */',
        'startLine' => 387,
        'endLine' => 390,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'hasBeenBootstrapped' => 
      array (
        'name' => 'hasBeenBootstrapped',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application has been bootstrapped before.
 *
 * @return bool
 */',
        'startLine' => 397,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setBasePath' => 
      array (
        'name' => 'setBasePath',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 408,
            'endLine' => 408,
            'startColumn' => 33,
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
 * Set the base path for the application.
 *
 * @param  string  $basePath
 * @return $this
 */',
        'startLine' => 408,
        'endLine' => 415,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bindPathsInContainer' => 
      array (
        'name' => 'bindPathsInContainer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Bind all of the application paths in the container.
 *
 * @return void
 */',
        'startLine' => 422,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'path' => 
      array (
        'name' => 'path',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 451,
                'endLine' => 451,
                'startTokenPos' => 1523,
                'startFilePos' => 11578,
                'endTokenPos' => 1523,
                'endFilePos' => 11579,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 451,
            'endLine' => 451,
            'startColumn' => 26,
            'endColumn' => 35,
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
 * Get the path to the application "app" directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 451,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useAppPath' => 
      array (
        'name' => 'useAppPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 462,
            'endLine' => 462,
            'startColumn' => 32,
            'endColumn' => 36,
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
 * Set the application directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 462,
        'endLine' => 469,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'basePath' => 
      array (
        'name' => 'basePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 477,
                'endLine' => 477,
                'startTokenPos' => 1607,
                'startFilePos' => 12100,
                'endTokenPos' => 1607,
                'endFilePos' => 12101,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 477,
            'endLine' => 477,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the base path of the Laravel installation.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 477,
        'endLine' => 480,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootstrapPath' => 
      array (
        'name' => 'bootstrapPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 488,
                'endLine' => 488,
                'startTokenPos' => 1641,
                'startFilePos' => 12338,
                'endTokenPos' => 1641,
                'endFilePos' => 12339,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 488,
            'endLine' => 488,
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
 * Get the path to the bootstrap directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 488,
        'endLine' => 491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getBootstrapProvidersPath' => 
      array (
        'name' => 'getBootstrapProvidersPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the service provider list in the bootstrap directory.
 *
 * @return string
 */',
        'startLine' => 498,
        'endLine' => 501,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useBootstrapPath' => 
      array (
        'name' => 'useBootstrapPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 509,
            'endLine' => 509,
            'startColumn' => 38,
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
 * Set the bootstrap file directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 509,
        'endLine' => 516,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configPath' => 
      array (
        'name' => 'configPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 524,
                'endLine' => 524,
                'startTokenPos' => 1739,
                'startFilePos' => 13110,
                'endTokenPos' => 1739,
                'endFilePos' => 13111,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 524,
            'endLine' => 524,
            'startColumn' => 32,
            'endColumn' => 41,
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
 * Get the path to the application configuration files.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 524,
        'endLine' => 527,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useConfigPath' => 
      array (
        'name' => 'useConfigPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 535,
            'endLine' => 535,
            'startColumn' => 35,
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
 * Set the configuration directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 535,
        'endLine' => 542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'databasePath' => 
      array (
        'name' => 'databasePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 550,
                'endLine' => 550,
                'startTokenPos' => 1823,
                'startFilePos' => 13650,
                'endTokenPos' => 1823,
                'endFilePos' => 13651,
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
 * Get the path to the database directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 550,
        'endLine' => 553,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useDatabasePath' => 
      array (
        'name' => 'useDatabasePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 561,
            'endLine' => 561,
            'startColumn' => 37,
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
 * Set the database directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 561,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'langPath' => 
      array (
        'name' => 'langPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 576,
                'endLine' => 576,
                'startTokenPos' => 1907,
                'startFilePos' => 14187,
                'endTokenPos' => 1907,
                'endFilePos' => 14188,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 576,
            'endLine' => 576,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the path to the language files.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 576,
        'endLine' => 579,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useLangPath' => 
      array (
        'name' => 'useLangPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 587,
            'endLine' => 587,
            'startColumn' => 33,
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
 * Set the language file directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 587,
        'endLine' => 594,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'publicPath' => 
      array (
        'name' => 'publicPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 602,
                'endLine' => 602,
                'startTokenPos' => 1981,
                'startFilePos' => 14692,
                'endTokenPos' => 1981,
                'endFilePos' => 14693,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 602,
            'endLine' => 602,
            'startColumn' => 32,
            'endColumn' => 41,
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
 * Get the path to the public / web directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 602,
        'endLine' => 605,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'usePublicPath' => 
      array (
        'name' => 'usePublicPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
            'startColumn' => 35,
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
 * Set the public / web directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 613,
        'endLine' => 620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'storagePath' => 
      array (
        'name' => 'storagePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 628,
                'endLine' => 628,
                'startTokenPos' => 2065,
                'startFilePos' => 15229,
                'endTokenPos' => 2065,
                'endFilePos' => 15230,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 628,
            'endLine' => 628,
            'startColumn' => 33,
            'endColumn' => 42,
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
 * Get the path to the storage directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 628,
        'endLine' => 639,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useStoragePath' => 
      array (
        'name' => 'useStoragePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 647,
            'endLine' => 647,
            'startColumn' => 36,
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
 * Set the storage directory.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 647,
        'endLine' => 654,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resourcePath' => 
      array (
        'name' => 'resourcePath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 662,
                'endLine' => 662,
                'startTokenPos' => 2227,
                'startFilePos' => 16095,
                'endTokenPos' => 2227,
                'endFilePos' => 16096,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 662,
            'endLine' => 662,
            'startColumn' => 34,
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
 * Get the path to the resources directory.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 662,
        'endLine' => 665,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'viewPath' => 
      array (
        'name' => 'viewPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 675,
                'endLine' => 675,
                'startTokenPos' => 2264,
                'startFilePos' => 16425,
                'endTokenPos' => 2264,
                'endFilePos' => 16426,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 675,
            'endLine' => 675,
            'startColumn' => 30,
            'endColumn' => 39,
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
 * Get the path to the views directory.
 *
 * This method returns the first configured path in the array of view paths.
 *
 * @param  string  $path
 * @return string
 */',
        'startLine' => 675,
        'endLine' => 680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'joinPaths' => 
      array (
        'name' => 'joinPaths',
        'parameters' => 
        array (
          'basePath' => 
          array (
            'name' => 'basePath',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 31,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 689,
                'endLine' => 689,
                'startTokenPos' => 2323,
                'startFilePos' => 16775,
                'endTokenPos' => 2323,
                'endFilePos' => 16776,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 42,
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
 * Join the given paths together.
 *
 * @param  string  $basePath
 * @param  string  $path
 * @return string
 */',
        'startLine' => 689,
        'endLine' => 692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentPath' => 
      array (
        'name' => 'environmentPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the environment file directory.
 *
 * @return string
 */',
        'startLine' => 699,
        'endLine' => 702,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'useEnvironmentPath' => 
      array (
        'name' => 'useEnvironmentPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 710,
            'endLine' => 710,
            'startColumn' => 40,
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
 * Set the directory for the environment file.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 710,
        'endLine' => 715,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadEnvironmentFrom' => 
      array (
        'name' => 'loadEnvironmentFrom',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 723,
            'endLine' => 723,
            'startColumn' => 41,
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
 * Set the environment file to be loaded during bootstrapping.
 *
 * @param  string  $file
 * @return $this
 */',
        'startLine' => 723,
        'endLine' => 728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentFile' => 
      array (
        'name' => 'environmentFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the environment file the application is using.
 *
 * @return string
 */',
        'startLine' => 735,
        'endLine' => 738,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environmentFilePath' => 
      array (
        'name' => 'environmentFilePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the fully-qualified path to the environment file.
 *
 * @return string
 */',
        'startLine' => 745,
        'endLine' => 748,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'environment' => 
      array (
        'name' => 'environment',
        'parameters' => 
        array (
          'environments' => 
          array (
            'name' => 'environments',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 756,
            'endLine' => 756,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Get or check the current application environment.
 *
 * @param  string|array  ...$environments
 * @return string|bool
 */',
        'startLine' => 756,
        'endLine' => 765,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isLocal' => 
      array (
        'name' => 'isLocal',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is in the local environment.
 *
 * @return bool
 */',
        'startLine' => 772,
        'endLine' => 775,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isProduction' => 
      array (
        'name' => 'isProduction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is in the production environment.
 *
 * @return bool
 */',
        'startLine' => 782,
        'endLine' => 785,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'detectEnvironment' => 
      array (
        'name' => 'detectEnvironment',
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
            'startLine' => 793,
            'endLine' => 793,
            'startColumn' => 39,
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
 * Detect the application\'s current environment.
 *
 * @param  \\Closure  $callback
 * @return string
 */',
        'startLine' => 793,
        'endLine' => 800,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningInConsole' => 
      array (
        'name' => 'runningInConsole',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running in the console.
 *
 * @return bool
 */',
        'startLine' => 807,
        'endLine' => 814,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningConsoleCommand' => 
      array (
        'name' => 'runningConsoleCommand',
        'parameters' => 
        array (
          'commands' => 
          array (
            'name' => 'commands',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 822,
            'endLine' => 822,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Determine if the application is running any of the given console commands.
 *
 * @param  string|array  ...$commands
 * @return bool
 */',
        'startLine' => 822,
        'endLine' => 832,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'runningUnitTests' => 
      array (
        'name' => 'runningUnitTests',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running unit tests.
 *
 * @return bool
 */',
        'startLine' => 839,
        'endLine' => 842,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'hasDebugModeEnabled' => 
      array (
        'name' => 'hasDebugModeEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is running with debug mode enabled.
 *
 * @return bool
 */',
        'startLine' => 849,
        'endLine' => 852,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registered' => 
      array (
        'name' => 'registered',
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
            'startLine' => 860,
            'endLine' => 860,
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
 * Register a new registered listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 860,
        'endLine' => 863,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerConfiguredProviders' => 
      array (
        'name' => 'registerConfiguredProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register all of the configured providers.
 *
 * @return void
 */',
        'startLine' => 870,
        'endLine' => 881,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'register' => 
      array (
        'name' => 'register',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 890,
            'endLine' => 890,
            'startColumn' => 30,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'force' => 
          array (
            'name' => 'force',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 890,
                'endLine' => 890,
                'startTokenPos' => 3070,
                'startFilePos' => 21655,
                'endTokenPos' => 3070,
                'endFilePos' => 21659,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 890,
            'endLine' => 890,
            'startColumn' => 41,
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
 * Register a service provider with the application.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @param  bool  $force
 * @return \\Illuminate\\Support\\ServiceProvider
 */',
        'startLine' => 890,
        'endLine' => 932,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getProvider' => 
      array (
        'name' => 'getProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 940,
            'endLine' => 940,
            'startColumn' => 33,
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
 * Get the registered service provider instance if it exists.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @return \\Illuminate\\Support\\ServiceProvider|null
 */',
        'startLine' => 940,
        'endLine' => 945,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getProviders' => 
      array (
        'name' => 'getProviders',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 953,
            'endLine' => 953,
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
 * Get the registered service provider instances if any exist.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider|string  $provider
 * @return array
 */',
        'startLine' => 953,
        'endLine' => 958,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resolveProvider' => 
      array (
        'name' => 'resolveProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 966,
            'endLine' => 966,
            'startColumn' => 37,
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
 * Resolve a service provider instance from the class name.
 *
 * @param  string  $provider
 * @return \\Illuminate\\Support\\ServiceProvider
 */',
        'startLine' => 966,
        'endLine' => 969,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'markAsRegistered' => 
      array (
        'name' => 'markAsRegistered',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
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
            'startColumn' => 41,
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
 * Mark the given provider as registered.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider  $provider
 * @return void
 */',
        'startLine' => 977,
        'endLine' => 984,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProviders' => 
      array (
        'name' => 'loadDeferredProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Load and boot all of the remaining deferred providers.
 *
 * @return void
 */',
        'startLine' => 991,
        'endLine' => 1001,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProvider' => 
      array (
        'name' => 'loadDeferredProvider',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1009,
            'endLine' => 1009,
            'startColumn' => 42,
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
 * Load the provider for a deferred service.
 *
 * @param  string  $service
 * @return void
 */',
        'startLine' => 1009,
        'endLine' => 1023,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerDeferredProvider' => 
      array (
        'name' => 'registerDeferredProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1032,
            'endLine' => 1032,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'service' => 
          array (
            'name' => 'service',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1032,
                'endLine' => 1032,
                'startTokenPos' => 3650,
                'startFilePos' => 26192,
                'endTokenPos' => 3650,
                'endFilePos' => 26195,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1032,
            'endLine' => 1032,
            'startColumn' => 57,
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
 * Register a deferred provider and service.
 *
 * @param  string  $provider
 * @param  string|null  $service
 * @return void
 */',
        'startLine' => 1032,
        'endLine' => 1048,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'make' => 
      array (
        'name' => 'make',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1061,
            'endLine' => 1061,
            'startColumn' => 26,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1061,
                'endLine' => 1061,
                'startTokenPos' => 3763,
                'startFilePos' => 27186,
                'endTokenPos' => 3764,
                'endFilePos' => 27187,
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
            'startLine' => 1061,
            'endLine' => 1061,
            'startColumn' => 37,
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
 * Resolve the given type from the container.
 *
 * @template TClass of object
 *
 * @param  string|class-string<TClass>  $abstract
 * @param  array  $parameters
 * @return ($abstract is class-string<TClass> ? TClass : mixed)
 *
 * @throws \\Illuminate\\Contracts\\Container\\BindingResolutionException
 */',
        'startLine' => 1061,
        'endLine' => 1066,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'resolve' => 
      array (
        'name' => 'resolve',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1081,
            'endLine' => 1081,
            'startColumn' => 32,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1081,
                'endLine' => 1081,
                'startTokenPos' => 3816,
                'startFilePos' => 27867,
                'endTokenPos' => 3817,
                'endFilePos' => 27868,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1081,
            'endLine' => 1081,
            'startColumn' => 43,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'raiseEvents' => 
          array (
            'name' => 'raiseEvents',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1081,
                'endLine' => 1081,
                'startTokenPos' => 3824,
                'startFilePos' => 27886,
                'endTokenPos' => 3824,
                'endFilePos' => 27889,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1081,
            'endLine' => 1081,
            'startColumn' => 61,
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
 * Resolve the given type from the container.
 *
 * @template TClass of object
 *
 * @param  string|class-string<TClass>|callable  $abstract
 * @param  array  $parameters
 * @param  bool  $raiseEvents
 * @return ($abstract is class-string<TClass> ? TClass : mixed)
 *
 * @throws \\Illuminate\\Contracts\\Container\\BindingResolutionException
 * @throws \\Illuminate\\Contracts\\Container\\CircularDependencyException
 */',
        'startLine' => 1081,
        'endLine' => 1086,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'loadDeferredProviderIfNeeded' => 
      array (
        'name' => 'loadDeferredProviderIfNeeded',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
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
            'startColumn' => 53,
            'endColumn' => 61,
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
 * Load the deferred provider if the given type is a deferred service and the instance has not been loaded.
 *
 * @param  string  $abstract
 * @return void
 */',
        'startLine' => 1094,
        'endLine' => 1099,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bound' => 
      array (
        'name' => 'bound',
        'parameters' => 
        array (
          'abstract' => 
          array (
            'name' => 'abstract',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1107,
            'endLine' => 1107,
            'startColumn' => 27,
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
 * Determine if the given abstract type has been bound.
 *
 * @param  string  $abstract
 * @return bool
 */',
        'startLine' => 1107,
        'endLine' => 1110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isBooted' => 
      array (
        'name' => 'isBooted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application has booted.
 *
 * @return bool
 */',
        'startLine' => 1117,
        'endLine' => 1120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Boot the application\'s service providers.
 *
 * @return void
 */',
        'startLine' => 1127,
        'endLine' => 1145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'bootProvider' => 
      array (
        'name' => 'bootProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\ServiceProvider',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1153,
            'endLine' => 1153,
            'startColumn' => 37,
            'endColumn' => 61,
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
 * Boot the given service provider.
 *
 * @param  \\Illuminate\\Support\\ServiceProvider  $provider
 * @return void
 */',
        'startLine' => 1153,
        'endLine' => 1162,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'booting' => 
      array (
        'name' => 'booting',
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
            'startLine' => 1170,
            'endLine' => 1170,
            'startColumn' => 29,
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
 * Register a new boot listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 1170,
        'endLine' => 1173,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
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
            'startLine' => 1181,
            'endLine' => 1181,
            'startColumn' => 28,
            'endColumn' => 36,
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
 * Register a new "booted" listener.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 1181,
        'endLine' => 1188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'fireAppCallbacks' => 
      array (
        'name' => 'fireAppCallbacks',
        'parameters' => 
        array (
          'callbacks' => 
          array (
            'name' => 'callbacks',
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
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1196,
            'endLine' => 1196,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * Call the booting callbacks for the application.
 *
 * @param  callable[]  $callbacks
 * @return void
 */',
        'startLine' => 1196,
        'endLine' => 1205,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\HttpFoundation\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1212,
            'endLine' => 1212,
            'startColumn' => 28,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'self::MAIN_REQUEST',
              'attributes' => 
              array (
                'startLine' => 1212,
                'endLine' => 1212,
                'startTokenPos' => 4270,
                'startFilePos' => 31037,
                'endTokenPos' => 4272,
                'endFilePos' => 31054,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1212,
            'endLine' => 1212,
            'startColumn' => 53,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'catch' => 
          array (
            'name' => 'catch',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1212,
                'endLine' => 1212,
                'startTokenPos' => 4281,
                'startFilePos' => 31071,
                'endTokenPos' => 4281,
                'endFilePos' => 31074,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1212,
            'endLine' => 1212,
            'startColumn' => 85,
            'endColumn' => 102,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\HttpFoundation\\Response',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * {@inheritdoc}
 *
 * @return \\Symfony\\Component\\HttpFoundation\\Response
 */',
        'startLine' => 1212,
        'endLine' => 1215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'handleRequest' => 
      array (
        'name' => 'handleRequest',
        'parameters' => 
        array (
          'request' => 
          array (
            'name' => 'request',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Http\\Request',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1223,
            'endLine' => 1223,
            'startColumn' => 35,
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
 * Handle the incoming HTTP request and send the response to the browser.
 *
 * @param  \\Illuminate\\Http\\Request  $request
 * @return void
 */',
        'startLine' => 1223,
        'endLine' => 1230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'handleCommand' => 
      array (
        'name' => 'handleCommand',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Console\\Input\\InputInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1238,
            'endLine' => 1238,
            'startColumn' => 35,
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
 * Handle the incoming Artisan command.
 *
 * @param  \\Symfony\\Component\\Console\\Input\\InputInterface  $input
 * @return int
 */',
        'startLine' => 1238,
        'endLine' => 1250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'shouldMergeFrameworkConfiguration' => 
      array (
        'name' => 'shouldMergeFrameworkConfiguration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the framework\'s base configuration should be merged.
 *
 * @return bool
 */',
        'startLine' => 1257,
        'endLine' => 1260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'dontMergeFrameworkConfiguration' => 
      array (
        'name' => 'dontMergeFrameworkConfiguration',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Indicate that the framework\'s base configuration should not be merged.
 *
 * @return $this
 */',
        'startLine' => 1267,
        'endLine' => 1272,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'shouldSkipMiddleware' => 
      array (
        'name' => 'shouldSkipMiddleware',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if middleware has been disabled for the application.
 *
 * @return bool
 */',
        'startLine' => 1279,
        'endLine' => 1283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedServicesPath' => 
      array (
        'name' => 'getCachedServicesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the cached services.php file.
 *
 * @return string
 */',
        'startLine' => 1290,
        'endLine' => 1293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedPackagesPath' => 
      array (
        'name' => 'getCachedPackagesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the cached packages.php file.
 *
 * @return string
 */',
        'startLine' => 1300,
        'endLine' => 1303,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'configurationIsCached' => 
      array (
        'name' => 'configurationIsCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application configuration is cached.
 *
 * @return bool
 */',
        'startLine' => 1310,
        'endLine' => 1317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedConfigPath' => 
      array (
        'name' => 'getCachedConfigPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the configuration cache file.
 *
 * @return string
 */',
        'startLine' => 1324,
        'endLine' => 1327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'routesAreCached' => 
      array (
        'name' => 'routesAreCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application routes are cached.
 *
 * @return bool
 */',
        'startLine' => 1334,
        'endLine' => 1341,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedRoutesPath' => 
      array (
        'name' => 'getCachedRoutesPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the routes cache file.
 *
 * @return string
 */',
        'startLine' => 1348,
        'endLine' => 1351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'eventsAreCached' => 
      array (
        'name' => 'eventsAreCached',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application events are cached.
 *
 * @return bool
 */',
        'startLine' => 1358,
        'endLine' => 1367,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getCachedEventsPath' => 
      array (
        'name' => 'getCachedEventsPath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the path to the events cache file.
 *
 * @return string
 */',
        'startLine' => 1374,
        'endLine' => 1377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'normalizeCachePath' => 
      array (
        'name' => 'normalizeCachePath',
        'parameters' => 
        array (
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
            'startLine' => 1386,
            'endLine' => 1386,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1386,
            'endLine' => 1386,
            'startColumn' => 49,
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
 * Normalize a relative or absolute path to a cache file.
 *
 * @param  string  $key
 * @param  string  $default
 * @return string
 */',
        'startLine' => 1386,
        'endLine' => 1395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'addAbsoluteCachePathPrefix' => 
      array (
        'name' => 'addAbsoluteCachePathPrefix',
        'parameters' => 
        array (
          'prefix' => 
          array (
            'name' => 'prefix',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1403,
            'endLine' => 1403,
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
 * Add new prefix to list of absolute path prefixes.
 *
 * @param  string  $prefix
 * @return $this
 */',
        'startLine' => 1403,
        'endLine' => 1408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'maintenanceMode' => 
      array (
        'name' => 'maintenanceMode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an instance of the maintenance mode manager implementation.
 *
 * @return \\Illuminate\\Contracts\\Foundation\\MaintenanceMode
 */',
        'startLine' => 1415,
        'endLine' => 1418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isDownForMaintenance' => 
      array (
        'name' => 'isDownForMaintenance',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the application is currently down for maintenance.
 *
 * @return bool
 */',
        'startLine' => 1425,
        'endLine' => 1428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'abort' => 
      array (
        'name' => 'abort',
        'parameters' => 
        array (
          'code' => 
          array (
            'name' => 'code',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1441,
            'endLine' => 1441,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'message' => 
          array (
            'name' => 'message',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 1441,
                'endLine' => 1441,
                'startTokenPos' => 5028,
                'startFilePos' => 36778,
                'endTokenPos' => 5028,
                'endFilePos' => 36779,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1441,
            'endLine' => 1441,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'headers' => 
          array (
            'name' => 'headers',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1441,
                'endLine' => 1441,
                'startTokenPos' => 5037,
                'startFilePos' => 36799,
                'endTokenPos' => 5038,
                'endFilePos' => 36800,
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
            'startLine' => 1441,
            'endLine' => 1441,
            'startColumn' => 49,
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
 * Throw an HttpException with the given data.
 *
 * @param  int  $code
 * @param  string  $message
 * @param  array  $headers
 * @return never
 *
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\HttpException
 * @throws \\Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException
 */',
        'startLine' => 1441,
        'endLine' => 1448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'terminating' => 
      array (
        'name' => 'terminating',
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
            'startLine' => 1456,
            'endLine' => 1456,
            'startColumn' => 33,
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
 * Register a terminating callback with the application.
 *
 * @param  callable|string  $callback
 * @return $this
 */',
        'startLine' => 1456,
        'endLine' => 1461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'terminate' => 
      array (
        'name' => 'terminate',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Terminate the application.
 *
 * @return void
 */',
        'startLine' => 1468,
        'endLine' => 1477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getLoadedProviders' => 
      array (
        'name' => 'getLoadedProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the service providers that have been loaded.
 *
 * @return array<string, bool>
 */',
        'startLine' => 1484,
        'endLine' => 1487,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'providerIsLoaded' => 
      array (
        'name' => 'providerIsLoaded',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
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
            'startLine' => 1495,
            'endLine' => 1495,
            'startColumn' => 38,
            'endColumn' => 53,
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
 * Determine if the given service provider is loaded.
 *
 * @param  string  $provider
 * @return bool
 */',
        'startLine' => 1495,
        'endLine' => 1498,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getDeferredServices' => 
      array (
        'name' => 'getDeferredServices',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application\'s deferred services.
 *
 * @return array
 */',
        'startLine' => 1505,
        'endLine' => 1508,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setDeferredServices' => 
      array (
        'name' => 'setDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1516,
            'endLine' => 1516,
            'startColumn' => 41,
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
 * Set the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1516,
        'endLine' => 1519,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isDeferredService' => 
      array (
        'name' => 'isDeferredService',
        'parameters' => 
        array (
          'service' => 
          array (
            'name' => 'service',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1527,
            'endLine' => 1527,
            'startColumn' => 39,
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
 * Determine if the given service is a deferred service.
 *
 * @param  string  $service
 * @return bool
 */',
        'startLine' => 1527,
        'endLine' => 1530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'addDeferredServices' => 
      array (
        'name' => 'addDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1538,
            'endLine' => 1538,
            'startColumn' => 41,
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
 * Add an array of services to the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1538,
        'endLine' => 1541,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'removeDeferredServices' => 
      array (
        'name' => 'removeDeferredServices',
        'parameters' => 
        array (
          'services' => 
          array (
            'name' => 'services',
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
            'startLine' => 1549,
            'endLine' => 1549,
            'startColumn' => 44,
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
 * Remove an array of services from the application\'s deferred services.
 *
 * @param  array  $services
 * @return void
 */',
        'startLine' => 1549,
        'endLine' => 1554,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'provideFacades' => 
      array (
        'name' => 'provideFacades',
        'parameters' => 
        array (
          'namespace' => 
          array (
            'name' => 'namespace',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1562,
            'endLine' => 1562,
            'startColumn' => 36,
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
 * Configure the real-time facade namespace.
 *
 * @param  string  $namespace
 * @return void
 */',
        'startLine' => 1562,
        'endLine' => 1565,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getLocale' => 
      array (
        'name' => 'getLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application locale.
 *
 * @return string
 */',
        'startLine' => 1572,
        'endLine' => 1575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'currentLocale' => 
      array (
        'name' => 'currentLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application locale.
 *
 * @return string
 */',
        'startLine' => 1582,
        'endLine' => 1585,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getFallbackLocale' => 
      array (
        'name' => 'getFallbackLocale',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current application fallback locale.
 *
 * @return string
 */',
        'startLine' => 1592,
        'endLine' => 1595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setLocale' => 
      array (
        'name' => 'setLocale',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1603,
            'endLine' => 1603,
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
 * Set the current application locale.
 *
 * @param  string  $locale
 * @return void
 */',
        'startLine' => 1603,
        'endLine' => 1612,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'setFallbackLocale' => 
      array (
        'name' => 'setFallbackLocale',
        'parameters' => 
        array (
          'fallbackLocale' => 
          array (
            'name' => 'fallbackLocale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1620,
            'endLine' => 1620,
            'startColumn' => 39,
            'endColumn' => 53,
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
 * Set the current application fallback locale.
 *
 * @param  string  $fallbackLocale
 * @return void
 */',
        'startLine' => 1620,
        'endLine' => 1625,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'isLocale' => 
      array (
        'name' => 'isLocale',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1633,
            'endLine' => 1633,
            'startColumn' => 30,
            'endColumn' => 36,
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
 * Determine if the application locale is the given locale.
 *
 * @param  string  $locale
 * @return bool
 */',
        'startLine' => 1633,
        'endLine' => 1636,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'registerCoreContainerAliases' => 
      array (
        'name' => 'registerCoreContainerAliases',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Register the core class aliases in the container.
 *
 * @return void
 */',
        'startLine' => 1643,
        'endLine' => 1691,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'flush' => 
      array (
        'name' => 'flush',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Flush the container of all bindings and resolved instances.
 *
 * @return void
 */',
        'startLine' => 1698,
        'endLine' => 1716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
        'aliasName' => NULL,
      ),
      'getNamespace' => 
      array (
        'name' => 'getNamespace',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the application namespace.
 *
 * @return string
 *
 * @throws \\RuntimeException
 */',
        'startLine' => 1725,
        'endLine' => 1742,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Foundation',
        'declaringClassName' => 'Illuminate\\Foundation\\Application',
        'implementingClassName' => 'Illuminate\\Foundation\\Application',
        'currentClassName' => 'Illuminate\\Foundation\\Application',
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