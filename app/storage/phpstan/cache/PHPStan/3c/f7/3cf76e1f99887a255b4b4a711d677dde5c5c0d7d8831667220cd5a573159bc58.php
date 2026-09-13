<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/FinancialAccount.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\FinancialAccount
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-2be59864a4a2e4f5e4f184eca2bd077db3bc83777b579192ef35a5cc2f5bd89b',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\FinancialAccount',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/FinancialAccount.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\FinancialAccount',
    'shortName' => 'FinancialAccount',
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
            'code' => '[\'organisation_id\', \'name\', \'account_type\', \'bank_name\', \'account_number_last4\', \'upi_id\', \'qr_payload\', \'qr_image_path\', \'status\']',
            'attributes' => 
            array (
              'startLine' => 16,
              'endLine' => 19,
              'startTokenPos' => 58,
              'startFilePos' => 438,
              'endTokenPos' => 87,
              'endFilePos' => 579,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 16,
    'endLine' => 48,
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
        'startLine' => 25,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\FinancialAccount',
        'implementingClassName' => 'App\\Models\\FinancialAccount',
        'currentClassName' => 'App\\Models\\FinancialAccount',
        'aliasName' => NULL,
      ),
      'feePayments' => 
      array (
        'name' => 'feePayments',
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
 * @return HasMany<FeePayment, $this>
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\FinancialAccount',
        'implementingClassName' => 'App\\Models\\FinancialAccount',
        'currentClassName' => 'App\\Models\\FinancialAccount',
        'aliasName' => NULL,
      ),
      'expenses' => 
      array (
        'name' => 'expenses',
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
 * @return HasMany<Expense, $this>
 */',
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\FinancialAccount',
        'implementingClassName' => 'App\\Models\\FinancialAccount',
        'currentClassName' => 'App\\Models\\FinancialAccount',
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