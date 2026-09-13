<?php declare(strict_types = 1);

// odsl-/Users/krishnadasd/Documents/projects/gym/app/app/Models/Attendance.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\Attendance
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.4.4-c6f9a38a4bb1326fcfa9659d50bff07847f1a5ab17d0dead6156bf08b21c0012',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\Attendance',
        'filename' => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Attendance.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\Attendance',
    'shortName' => 'Attendance',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * `subject` is polymorphic across `members` and `organisation_users`, using
 * the \'member\'/\'user\' morph map aliases registered in AppServiceProvider so
 * `subject_type` stores the short alias from MEP.md rather than a class
 * name. The unique index on
 * (organisation_id, club_id, subject_type, subject_id, attendance_date) is
 * the actual "one record per day" guarantee — see MEP.md 5.9.
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
            'code' => '[\'organisation_id\', \'club_id\', \'subject_type\', \'subject_id\', \'attendance_date\', \'check_in_at\', \'action\', \'marked_by\', \'source\', \'notes\']',
            'attributes' => 
            array (
              'startLine' => 25,
              'endLine' => 25,
              'startTokenPos' => 65,
              'startFilePos' => 881,
              'endTokenPos' => 94,
              'endFilePos' => 1016,
            ),
          ),
        ),
      ),
    ),
    'startLine' => 25,
    'endLine' => 64,
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
        'startLine' => 31,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\Attendance',
        'implementingClassName' => 'App\\Models\\Attendance',
        'currentClassName' => 'App\\Models\\Attendance',
        'aliasName' => NULL,
      ),
      'club' => 
      array (
        'name' => 'club',
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
 * @return BelongsTo<Club, $this>
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
        'declaringClassName' => 'App\\Models\\Attendance',
        'implementingClassName' => 'App\\Models\\Attendance',
        'currentClassName' => 'App\\Models\\Attendance',
        'aliasName' => NULL,
      ),
      'subject' => 
      array (
        'name' => 'subject',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return MorphTo<Model, $this>
 */',
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\Attendance',
        'implementingClassName' => 'App\\Models\\Attendance',
        'currentClassName' => 'App\\Models\\Attendance',
        'aliasName' => NULL,
      ),
      'markedBy' => 
      array (
        'name' => 'markedBy',
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
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\Attendance',
        'implementingClassName' => 'App\\Models\\Attendance',
        'currentClassName' => 'App\\Models\\Attendance',
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