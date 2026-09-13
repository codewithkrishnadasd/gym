<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/WhatsappActionNotification.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\WhatsappActionNotification
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-6797eebaf9040fa621f2e6cdf45aeae71badba85aac7ee46f73ba6f1c2d1551f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\WhatsappActionNotification',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/WhatsappActionNotification.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\WhatsappActionNotification',
    'shortName' => 'WhatsappActionNotification',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Not a delivery log: `opened` means the admin launched the WhatsApp deep
 * link, never that WhatsApp delivered the message. The unique index on
 * (entity_id, action_type, operation_id) is the idempotency guarantee for
 * retried operations — see MEP.md 5.14.
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
            'code' => '[\'organisation_id\', \'recipient_type\', \'recipient_id\', \'recipient_name\', \'recipient_phone\', \'entity_type\', \'entity_id\', \'action_type\', \'message_template_version\', \'message_snapshot\', \'status\', \'created_by\', \'operation_id\']',
            'attributes' => 
            array (
              'startLine' => 24,
              'endLine' => 28,
              'startTokenPos' => 70,
              'startFilePos' => 800,
              'endTokenPos' => 111,
              'endFilePos' => 1035,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 24,
    'endLine' => 62,
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
      'UPDATED_AT' => 
      array (
        'declaringClassName' => 'App\\Models\\WhatsappActionNotification',
        'implementingClassName' => 'App\\Models\\WhatsappActionNotification',
        'name' => 'UPDATED_AT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 141,
            'startFilePos' => 1217,
            'endTokenPos' => 141,
            'endFilePos' => 1220,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 28,
      ),
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
        'startLine' => 36,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\WhatsappActionNotification',
        'implementingClassName' => 'App\\Models\\WhatsappActionNotification',
        'currentClassName' => 'App\\Models\\WhatsappActionNotification',
        'aliasName' => NULL,
      ),
      'createdBy' => 
      array (
        'name' => 'createdBy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<OrganisationUser, $this>
 */',
        'startLine' => 50,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\WhatsappActionNotification',
        'implementingClassName' => 'App\\Models\\WhatsappActionNotification',
        'currentClassName' => 'App\\Models\\WhatsappActionNotification',
        'aliasName' => NULL,
      ),
      'openedBy' => 
      array (
        'name' => 'openedBy',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return BelongsTo<OrganisationUser, $this>
 */',
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\WhatsappActionNotification',
        'implementingClassName' => 'App\\Models\\WhatsappActionNotification',
        'currentClassName' => 'App\\Models\\WhatsappActionNotification',
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