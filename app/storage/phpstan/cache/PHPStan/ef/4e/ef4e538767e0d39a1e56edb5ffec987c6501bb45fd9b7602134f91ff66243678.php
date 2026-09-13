<?php declare(strict_types = 1);

// ftm-/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v6-2.3.5',
   'data' => 
  array (
    0 => 
    array (
      '40c8682acf37fde13c8f408cd84a6caa' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'expensestatus' => 'App\\Enums\\ExpenseStatus',
          'expensetargettype' => 'App\\Enums\\ExpenseTargetType',
          'belongstoorganisation' => 'App\\Models\\Concerns\\BelongsToOrganisation',
          'expensefactory' => 'Database\\Factories\\ExpenseFactory',
          'fillable' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '65ef96031349a5706bd5da8017780f89' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'organisation' => 'App\\Models\\Organisation',
          'organisationscope' => 'App\\Models\\Scopes\\OrganisationScope',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\BelongsToOrganisation',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'App\\Models\\Concerns\\BelongsToOrganisation',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      '5cbdf60be93dc54e1f262f4e63d6066a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'organisation' => 'App\\Models\\Organisation',
          'organisationscope' => 'App\\Models\\Scopes\\OrganisationScope',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'bootBelongsToOrganisation',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'organisation' => 'App\\Models\\Organisation',
            'organisationscope' => 'App\\Models\\Scopes\\OrganisationScope',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          ),
           'className' => 'App\\Models\\Expense',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\BelongsToOrganisation',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\BelongsToOrganisation',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'App\\Models\\Concerns\\BelongsToOrganisation',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      '26ba90e5bc7ac6a229431171594f8a25' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'organisation' => 'App\\Models\\Organisation',
          'organisationscope' => 'App\\Models\\Scopes\\OrganisationScope',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'organisation',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'organisation' => 'App\\Models\\Organisation',
            'organisationscope' => 'App\\Models\\Scopes\\OrganisationScope',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          ),
           'className' => 'App\\Models\\Expense',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\BelongsToOrganisation',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\BelongsToOrganisation',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'App\\Models\\Concerns\\BelongsToOrganisation',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      '6d65fd3a56a5698f994d76fd86f16c87' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
          'TFactory' => 
          array (
            0 => '@template',
            1 => 
            \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
               'name' => 'TFactory',
               'bound' => 
              \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                 'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
               'default' => NULL,
               'lowerBound' => NULL,
               'description' => '',
               'attributes' => 
              array (
                'startLine' => 2,
                'endLine' => 2,
              ),
            )),
          ),
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      'e3bf953c8727f5883b82fc32cf5276c5' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'factory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Models\\Expense',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      'b6d894330a47016fd8dcd44a30e3c026' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'newFactory',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Models\\Expense',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      'bc3438ba05b2d35ea476d932ede6b390' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
         'uses' => 
        array (
          'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'getUseFactoryAttribute',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'Illuminate\\Database\\Eloquent\\Factories',
           'uses' => 
          array (
            'usefactory' => 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory',
          ),
           'className' => 'App\\Models\\Expense',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
            'TFactory' => 
            array (
              0 => '@template',
              1 => 
              \PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::__set_state(array(
                 'name' => 'TFactory',
                 'bound' => 
                \PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode::__set_state(array(
                   'name' => '\\Illuminate\\Database\\Eloquent\\Factories\\Factory',
                   'attributes' => 
                  array (
                    'startLine' => 2,
                    'endLine' => 2,
                  ),
                )),
                 'default' => NULL,
                 'lowerBound' => NULL,
                 'description' => '',
                 'attributes' => 
                array (
                  'startLine' => 2,
                  'endLine' => 2,
                ),
              )),
            ),
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
         'traitData' => 
        array (
          0 => '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php',
          1 => 'App\\Models\\Expense',
          2 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          3 => NULL,
          4 => '/** @use HasFactory<ExpenseFactory> */',
        ),
      )),
      'adbcdf0e59b0a1c0021bacb76b1ef9a0' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'expensestatus' => 'App\\Enums\\ExpenseStatus',
          'expensetargettype' => 'App\\Enums\\ExpenseTargetType',
          'belongstoorganisation' => 'App\\Models\\Concerns\\BelongsToOrganisation',
          'expensefactory' => 'Database\\Factories\\ExpenseFactory',
          'fillable' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'casts',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'f2169f5d501456daa06cc6aa385815d8' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'expensestatus' => 'App\\Enums\\ExpenseStatus',
          'expensetargettype' => 'App\\Enums\\ExpenseTargetType',
          'belongstoorganisation' => 'App\\Models\\Concerns\\BelongsToOrganisation',
          'expensefactory' => 'Database\\Factories\\ExpenseFactory',
          'fillable' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'club',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '6ca72dce9ac1aab27a2c434188144119' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'expensestatus' => 'App\\Enums\\ExpenseStatus',
          'expensetargettype' => 'App\\Enums\\ExpenseTargetType',
          'belongstoorganisation' => 'App\\Models\\Concerns\\BelongsToOrganisation',
          'expensefactory' => 'Database\\Factories\\ExpenseFactory',
          'fillable' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'paidFromFinancialAccount',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '66cb524f09a660d671027bb8877d134f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'expensestatus' => 'App\\Enums\\ExpenseStatus',
          'expensetargettype' => 'App\\Enums\\ExpenseTargetType',
          'belongstoorganisation' => 'App\\Models\\Concerns\\BelongsToOrganisation',
          'expensefactory' => 'Database\\Factories\\ExpenseFactory',
          'fillable' => 'Illuminate\\Database\\Eloquent\\Attributes\\Fillable',
          'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
        ),
         'className' => 'App\\Models\\Expense',
         'functionName' => 'createdBy',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Expense.php' => 'b9a705f236401c74796a698d9b052b0f2762adcb74d9ff07a251129613a8cb45',
      '/Users/krishnadasd/Documents/projects/gym/app/app/Models/Concerns/BelongsToOrganisation.php' => '959f01b2bd4fe105c0c506be6f2d9f371441d37a0dec88a91bdd3e33d5d0032d',
      '/Users/krishnadasd/Documents/projects/gym/app/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Factories/HasFactory.php' => 'b6cb2b164e90168e80963a5549541f5f3188a3ec8cfd368bf3611bd94fbd46a7',
    ),
  ),
));