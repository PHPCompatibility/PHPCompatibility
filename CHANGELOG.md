# Change Log for the PHPCompatibility standard for PHP Codesniffer

All notable changes to this project will be documented in this file.

This projects adheres to [Keep a CHANGELOG](http://keepachangelog.com/).

The `major.minor` version numbers are based on the PHP version for which compatibility check support was added.
The `patch` version numbers are specific to this library.


## [Unreleased]

_Nothing yet._


## [7.1.5] - 2017-07-17

See all related issues and PRs in the [7.1.5 milestone].

### Added
- :star: The `NewKeywords` sniff will now also sniff for `yield from` which was introduced in PHP 7.0. [#477](https://github.com/wimg/PHPCompatibility/pull/477). Fixes [#476](https://github.com/wimg/PHPCompatibility/issues/476)
- :books: The LGPL-3.0 license. [#447](https://github.com/wimg/PHPCompatibility/pull/447)

### Changed
- :rewind: The `NewExecutionDirectives` sniff will now also report on execution directives when used in combination with PHPCS 2.0.0-2.3.3. [#451](https://github.com/wimg/PHPCompatibility/pull/451)
- :rewind: The `getMethodParameters()` utility method will no longer break when used with PHPCS 1.5.x < 1.5.6. This affected a number of sniffs. [#452](https://github.com/wimg/PHPCompatibility/pull/452)
- :rewind: The `inUseScope()` utility method will no longer break when used with PHPCS 2.0.0 - 2.2.0. This affected a number of sniffs. [#454](https://github.com/wimg/PHPCompatibility/pull/454)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#443](https://github.com/wimg/PHPCompatibility/pull/443), [#474](https://github.com/wimg/PHPCompatibility/pull/474)
- :pencil2: Renamed a test file for consistency. [#453](https://github.com/wimg/PHPCompatibility/pull/453)
- :wrench: Code style clean up. [#429](https://github.com/wimg/PHPCompatibility/pull/429)
- :wrench: Prevent Composer installing PHPCS 3.x. **_PHPCS 3.x is not (yet) supported by the PHPCompatibility standard, but will be in the near future._** [#444](https://github.com/wimg/PHPCompatibility/pull/444)
- :green_heart: The code base will now be checked for consistent code style during build testing. [#429](https://github.com/wimg/PHPCompatibility/pull/429)
- :green_heart: The sniffs are now also tested against HHVM for consistent results. _Note: the sniffs do not contain any HHVM specific checks nor is there any intention to add them at this time._ [#450](https://github.com/wimg/PHPCompatibility/pull/450)
- :books: Made it explicit that - at this moment - PHPCS 3.x is not (yet) supported. [#444](https://github.com/wimg/PHPCompatibility/pull/444)
- :books: Minor improvements to the Readme. [#448](https://github.com/wimg/PHPCompatibility/pull/448), [#449](https://github.com/wimg/PHPCompatibility/pull/449), [#468](https://github.com/wimg/PHPCompatibility/pull/468)
- :books: Minor improvements to the Contributing guidelines. [#467](https://github.com/wimg/PHPCompatibility/pull/467)

### Removed
- :no_entry_sign: The `DefaultTimeZoneRequired` sniff. This sniff was checking server settings rather than code. [#458](https://github.com/wimg/PHPCompatibility/pull/458). Fixes [#457](https://github.com/wimg/PHPCompatibility/issues/457)
- :no_entry_sign: The `NewMagicClassConstant` sniff as introduced in v 7.1.4 contained two additional checks for not strictly compatibility related issues. One of these was plainly wrong, the other opinionated. Both have been removed. [#442](https://github.com/wimg/PHPCompatibility/pull/442). Fixes [#436](https://github.com/wimg/PHPCompatibility/issues/436)

### Fixed
- :bug: `NewClass` sniff: was reporting an incorrect introduction version number for a few of the Exception classes. [#441](https://github.com/wimg/PHPCompatibility/pull/441). Fixes [#440](https://github.com/wimg/PHPCompatibility/issues/440).
- :bug: `ForbiddenBreakContinueVariableArguments` sniff: was incorrectly reporting an error if the `break` or `continue` was followed by a PHP closing tag (breaking out of PHP). [#462](https://github.com/wimg/PHPCompatibility/pull/462). Fixes [#460](https://github.com/wimg/PHPCompatibility/issues/460)
- :bug: `ForbiddenGlobalVariableVariable` sniff: was incorrectly reporting an error if the `global` statement was followed by a PHP closing tag (breaking out of PHP). [#463](https://github.com/wimg/PHPCompatibility/pull/463).
- :bug: `DeprecatedFunctions` sniff: was reporting false positives for classes using the same name as a deprecated function. [#465](https://github.com/wimg/PHPCompatibility/pull/465). Fixes [#464](https://github.com/wimg/PHPCompatibility/issues/464)

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:


## [7.1.4] - 2017-05-06

See all related issues and PRs in the [7.1.4 milestone].

### Added
- :star2: New `CaseSensitiveKeywords` sniff to detect use of non-lowercase `self`, `static` and `parent` keywords which could cause compatibility issues pre-PHP 5.5. [#382](https://github.com/wimg/PHPCompatibility/pull/382)
- :star2: New `ConstantArraysUsingConst` sniff to detect constants defined using the `const` keyword being assigned an array value which was not supported prior to PHP 5.6. [#397](https://github.com/wimg/PHPCompatibility/pull/397)
- :star2: New `ForbiddenClosureUseVariableNames` sniff to detect PHP 7.1 forbidden variable names in closure use statements. [#386](https://github.com/wimg/PHPCompatibility/pull/386). Fixes [#374](https://github.com/wimg/PHPCompatibility/issues/374)
- :star2: New `NewArrayStringDereferencing` sniff to detect array and string literal dereferencing as introduced in PHP 5.5. [#388](https://github.com/wimg/PHPCompatibility/pull/388)
- :star2: New `NewHeredocInitialize` sniff to detect initialization of static variables and class properties/constants using the heredoc syntax which is supported since PHP 5.3. [#391](https://github.com/wimg/PHPCompatibility/pull/391). Fixes [#51](https://github.com/wimg/PHPCompatibility/issues/51)
- :star2: New `NewMagicClassConstant` sniff to detect use of the magic `::class` constant as introduced in PHP 5.5. [#403](https://github.com/wimg/PHPCompatibility/pull/403). Fixes [#364](https://github.com/wimg/PHPCompatibility/issues/364).
- :star2: New `NewUseConstFunction` sniff to detect use statements importing constants and functions as introduced in PHP 5.6. [#401](https://github.com/wimg/PHPCompatibility/pull/401)
- :star: `DeprecatedFunctions` sniff: recognize PHP 7.2 deprecated GD functions. [#392](https://github.com/wimg/PHPCompatibility/pull/392)
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.2 deprecated `mbstring.func_overload` directive. [#377](https://github.com/wimg/PHPCompatibility/pull/377)
- :star: `NewClasses` sniff: check for the PHP 5.1 `libXMLError` class. [#412](https://github.com/wimg/PHPCompatibility/pull/412)
- :star: `NewClasses` sniff: recognize all native PHP Exception classes. [#418](https://github.com/wimg/PHPCompatibility/pull/418)
- :star: `NewClosures` sniff: check for closures being declared as static and closures using `$this`. Both of which was not supported pre-PHP 5.4. [#389](https://github.com/wimg/PHPCompatibility/pull/389). Fixes [#24](https://github.com/wimg/PHPCompatibility/issues/24).
- :star: `NewFunctionParameters` sniff: recognize new `exclude_disabled` parameter for the `get_defined_functions()` function as introduced in PHP 7.0.15. [#375](https://github.com/wimg/PHPCompatibility/pull/375)
- :star: `NewFunctions` sniff: recognize new PHP 7.2 socket related functions. [#376](https://github.com/wimg/PHPCompatibility/pull/376)
- :star: `NewInterfaces` sniff: check for some more PHP native interfaces. [#411](https://github.com/wimg/PHPCompatibility/pull/411)
- :star: New `isClassProperty()`, `isClassConstant()` and `validDirectScope()` utility methods to the `PHPCompatibility_Sniff` class. [#393](https://github.com/wimg/PHPCompatibility/pull/393), [#391](https://github.com/wimg/PHPCompatibility/pull/391).
- :star: New `getTypeHintsFromFunctionDeclaration()` utility method to the `PHPCompatibility_Sniff` class. [#414](https://github.com/wimg/PHPCompatibility/pull/414).
- :umbrella: Unit tests against false positives for the `NewMagicMethods` sniff. [#381](https://github.com/wimg/PHPCompatibility/pull/381)
- :umbrella: More unit tests for the `getTestVersion()` utility method. [#405](https://github.com/wimg/PHPCompatibility/pull/405), [#430](https://github.com/wimg/PHPCompatibility/pull/430)
- :green_heart: The XML of the ruleset will now be validated and checked for consistent code style during the build testing by Travis. [#433](https://github.com/wimg/PHPCompatibility/pull/433)
- :books: Readme: information about setting `installed_paths` via a custom ruleset. [#407](https://github.com/wimg/PHPCompatibility/pull/407)
- :books: `Changelog.md` file containing a record of notable changes since the first tagged release. [#421](https://github.com/wimg/PHPCompatibility/pull/421)

### Changed
- :pushpin: The `ForbiddenNamesAsDeclared` sniff will now emit `warning`s for soft reserved keywords. [#406](https://github.com/wimg/PHPCompatibility/pull/406), [#370](https://github.com/wimg/PHPCompatibility/pull/370).
- :pushpin: The `ForbiddenNames` sniff will now allow for the more liberal rules for usage of reserved keywords as of PHP 7.0. [#417](https://github.com/wimg/PHPCompatibility/pull/417)
- :pushpin: The `InternalInterfaces`, `NewClasses`, `NewConstVisibility`, `NewInterfaces`, `NewMagicMethods`, `NonStaticMagicMethods` and `RemovedGlobalVariables` sniffs will now also sniff for and correctly report violations in combination with anonymous classes. [#378](https://github.com/wimg/PHPCompatibility/pull/378), [#383](https://github.com/wimg/PHPCompatibility/pull/383), [#393](https://github.com/wimg/PHPCompatibility/pull/393), [#394](https://github.com/wimg/PHPCompatibility/pull/394), [#395](https://github.com/wimg/PHPCompatibility/pull/395), [#396](https://github.com/wimg/PHPCompatibility/pull/396). Fixes [#351](https://github.com/wimg/PHPCompatibility/issues/351) and [#333](https://github.com/wimg/PHPCompatibility/issues/333).
- :pushpin: The `NewClasses` and `NewInterfaces` sniffs will now also report on new classes/interfaces when used as type hints. [#414](https://github.com/wimg/PHPCompatibility/pull/414), [#416](https://github.com/wimg/PHPCompatibility/pull/416). Fixes [#352](https://github.com/wimg/PHPCompatibility/issues/352)
- :pushpin: The `NewClasses` sniff will now also report on Exception classes when used in (multi-)`catch` statements. [#418](https://github.com/wimg/PHPCompatibility/pull/418). Fixes [#373](https://github.com/wimg/PHPCompatibility/issues/373).
- :pushpin: The `NewScalarTypeDeclarations` sniff will now report on new type hints even when the type hint is nullable. [#379](https://github.com/wimg/PHPCompatibility/pull/379)
- :twisted_rightwards_arrows: The `NewNowdoc` sniff has been renamed to `NewNowdocQuotedHeredoc` and will now also check for double quoted heredoc identifiers as introduced in PHP 5.3. [#390](https://github.com/wimg/PHPCompatibility/pull/390)
- :rewind: The `NewClasses` sniff will now also report anonymous classes which `extend` a new sniff when used in combination with PHPCS 2.4.0-2.8.0. [#432](https://github.com/wimg/PHPCompatibility/pull/432). Fixes [#334](https://github.com/wimg/PHPCompatibility/issues/334).
- :pencil2: `NewFunctionParameter` sniff: version number precision for two parameters. [#384](https://github.com/wimg/PHPCompatibility/pull/384), [#428](https://github.com/wimg/PHPCompatibility/pull/428)
- :umbrella: Skipping two unit tests for the `ForbiddenClosureUseVariable` sniff when run on PHPCS 2.5.1 as these cause an infinite loop due to an upstream bug. [#408](https://github.com/wimg/PHPCompatibility/pull/408)
- :umbrella: Skipping unit tests involving `trait`s in combination with PHP < 5.4 and PHPCS < 2.4.0 as `trait`s are not recognized in those circumstances. [#431](https://github.com/wimg/PHPCompatibility/pull/431)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#385](https://github.com/wimg/PHPCompatibility/pull/385), [#387](https://github.com/wimg/PHPCompatibility/pull/387), [#415](https://github.com/wimg/PHPCompatibility/pull/415), [#423](https://github.com/wimg/PHPCompatibility/pull/423), [#424](https://github.com/wimg/PHPCompatibility/pull/424)
- :recycle: Minor simplification of the PHPUnit 6 compatibility layer and other test code. [#426](https://github.com/wimg/PHPCompatibility/pull/426), [#425](https://github.com/wimg/PHPCompatibility/pull/425)
- General housekeeping. [#398](https://github.com/wimg/PHPCompatibility/pull/398), [#400](https://github.com/wimg/PHPCompatibility/pull/400)
- :wrench: Minor tweaks to the Travis build script. [#409](https://github.com/wimg/PHPCompatibility/pull/409)
- :green_heart: The sniffs are now also tested against PHP nightly for consistent results. [#380](https://github.com/wimg/PHPCompatibility/pull/380)

### Fixed
- :fire: Using unbounded ranges in `testVersion` resulted in unreported errors when used with sniffs using the `supportsBelow()` method. This affected the results of approximately half the sniffs. [#430](https://github.com/wimg/PHPCompatibility/pull/430)
- :bug: The `ForbiddenNames` sniff would throw false positives for `use` statements with the `final` modifier in traits. [#402](https://github.com/wimg/PHPCompatibility/pull/402).
- :bug: The `ForbiddenNames` sniff would fail to report on functions declared to return by reference using a reserved keyword as the function name. [#413](https://github.com/wimg/PHPCompatibility/pull/413)
- :bug: The `ForbiddenNames` sniff would only examine the first part of a namespace and not report on reserved keywords used in subsequent parts of a nested namespace. [#419](https://github.com/wimg/PHPCompatibility/pull/419)
- :bug: The `ForbiddenNames` sniff would not always correctly report on use statements importing constants or functions using reserved keywords. [#420](https://github.com/wimg/PHPCompatibility/pull/420)
- :bug: The `NewKeywords` sniff would sometimes fail to report on the `const` keyword when used in a class, but not for a class constant. [#424](https://github.com/wimg/PHPCompatibility/pull/424)
- :green_heart: PHPCS has released version 3.0 and updated the `master` branch to reflect this. This was causing the builds to fail. [#422](https://github.com/wimg/PHPCompatibility/pull/422)

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:


## [7.1.3] - 2017-04-02

See all related issues and PRs in the [7.1.3 milestone].

### Added
- :zap: The `testVersion` config parameter now allows for specifying unbounded ranges.
    For example: specifying `-5.6` means: check for compatibility with all PHP versions up to and including PHP 5.6;
    Specifying `7.0-` means: check for compatibility with all PHP versions from PHP 7.0 upwards.
    For more information about setting the `testVersion`, see [Using the compatibility sniffs](https://github.com/wimg/PHPCompatibility#using-the-compatibility-sniffs) in the readme.
- :umbrella: Unit test for multi-line short arrays for the `ShortArray` sniff. [#347](https://github.com/wimg/PHPCompatibility/pull/347)
- :umbrella: Various additional unit tests against false positives. [#345](https://github.com/wimg/PHPCompatibility/pull/345), [#369](https://github.com/wimg/PHPCompatibility/pull/369)
- :umbrella: Unit tests for the `supportsBelow()`, `supportsAbove()` and `getTestVersion()` utility methods. [#363](https://github.com/wimg/PHPCompatibility/pull/363)
- :books: Readme: information about installation of the standard using git check-out. [#349](https://github.com/wimg/PHPCompatibility/pull/349)
- :books: `Contributing.md` file with information about reporting bugs, requesting features, making pull requests and running the unit tests. [#350](https://github.com/wimg/PHPCompatibility/pull/350)

### Changed
- :pushpin: The `ForbiddenFunctionParametersWithSameName`, `NewScalarTypeDeclarations`, `ParameterShadowSuperGlobals` sniff will now also sniff for and report violations in closures. [#331](https://github.com/wimg/PHPCompatibility/pull/331)
- :twisted_rightwards_arrows: :rewind: The check for the PHP 5.3 `nowdoc` structure has been moved from the `NewKeywords` sniff to a new stand-alone `NewNowdoc` sniff which will now also recognize this structure when the sniffs are run on PHP 5.2. [#335](https://github.com/wimg/PHPCompatibility/pull/335)
- :rewind: The `ForbiddenNames` sniff will now also correctly recognize reserved keywords used in a declared namespace when run on PHP 5.2. [#362](https://github.com/wimg/PHPCompatibility/pull/362)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#360](https://github.com/wimg/PHPCompatibility/pull/360)
- :recycle: The unit tests would previously run each test case file against all PHPCompatibility sniffs. Now, they will only be tested against the sniff which the test case file is intended to test. This allows for more test cases to be tested, more precise testing in combination with `testVersion` settings and makes the unit tests run ~6 x faster.
    Relevant additional unit tests have been added and others adjusted. [#369](https://github.com/wimg/PHPCompatibility/pull/369)
- :recycle: Refactoring/tidying up of some unit test code. [#343](https://github.com/wimg/PHPCompatibility/pull/343), [#345](https://github.com/wimg/PHPCompatibility/pull/345), [#356](https://github.com/wimg/PHPCompatibility/pull/356), [#355](https://github.com/wimg/PHPCompatibility/pull/355), [#359](https://github.com/wimg/PHPCompatibility/pull/359)
- General housekeeping. [#346](https://github.com/wimg/PHPCompatibility/pull/346)
- :books: Readme: Clarify minimum requirements and influence on the results. [#348](https://github.com/wimg/PHPCompatibility/pull/348)

### Removed
- :twisted_rightwards_arrows: Removed the `LongArrays` sniff. The checks it contained have been moved into the `RemovedGlobalVariables` sniff. Both sniffs essentially did the same thing, just for different PHP native superglobals. [#354](https://github.com/wimg/PHPCompatibility/pull/354)

### Fixed
- :bug: The `PregReplaceEModifier` sniff would throw a false positive if a quote character was used as the regex delimiter. [#357](https://github.com/wimg/PHPCompatibility/pull/357)
- :bug: `RemovedGlobalVariables` sniff would report false positives for class properties shadowing the removed `$HTTP_RAW_POST_DATA` variables. [#354](https://github.com/wimg/PHPCompatibility/pull/354).
- :bug: The `getFQClassNameFromNewToken()` utility function could go into an infinite loop causing PHP to run out of memory when examining unfinished code (examination during live coding). [#338](https://github.com/wimg/PHPCompatibility/pull/338), [#342](https://github.com/wimg/PHPCompatibility/pull/342)
- :bug: The `determineNamespace()` utility method would in certain cases not break out a loop. [#358](https://github.com/wimg/PHPCompatibility/pull/358)
- :wrench: Travis script: Minor tweak for PHP 5.2 compatibility. [#341](https://github.com/wimg/PHPCompatibility/pull/341)
- :wrench: The unit test suite is now also compatible with PHPUnit 6. [#365](https://github.com/wimg/PHPCompatibility/pull/365)
- :books: Readme: Typo in the composer instructions. [#344](https://github.com/wimg/PHPCompatibility/pull/344)

### Credits
Thanks go out to [Arthur Edamov], [Juliette Reinders Folmer], [Mark Clements] and [Tadas Juozapaitis] for their contributions to this version. :clap:


## [7.1.2] - 2017-02-17

See all related issues and PRs in the [7.1.2 milestone].

### Added
- :star2: New `VariableVariables` sniff to detect variables variables for which the behaviour has changed in PHP 7.0. [#310](https://github.com/wimg/PHPCompatibility/pull/310) Fixes [#309](https://github.com/wimg/PHPCompatibility/issues/309).
- :star: The `NewReturnTypeDeclarations` sniff will now also sniff for non-scalar return type declarations, i.e. `array`, `callable`, `self` or a class name. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :star: The `NewLanguageConstructs` sniff will now also sniff for the null coalesce equal operator `??=`. This operator is slated to be introduced in PHP 7.2 and PHPCS already accounts for it. [#340](https://github.com/wimg/PHPCompatibility/pull/340)
- :star: New `getReturnTypeHintToken()` utility method to the `PHPCompatibility_Sniff` class to retrieve return type hints from function declarations in a cross-PHPCS-version compatible way. [#323](https://github.com/wimg/PHPCompatibility/pull/323).
- :star: New `stripVariables()` utility method to the `PHPCompatibility_Sniff` class to strip variables from interpolated text strings. [#341](https://github.com/wimg/PHPCompatibility/pull/314).
- :umbrella: Additional unit tests covering previously uncovered code. [#308](https://github.com/wimg/PHPCompatibility/pull/308)

### Changed
- :pushpin: The `MbstringReplaceEModifier`, `PregReplaceEModifier` and `NewExecutionDirectives` sniffs will now also correctly interpret double quoted text strings with interpolated variables. [#341](https://github.com/wimg/PHPCompatibility/pull/314), [#324](https://github.com/wimg/PHPCompatibility/pull/324).
- :pushpin: The `NewNullableTypes` sniff will now also report on nullable (return) type hints when used with closures. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :pushpin: The `NewReturnTypeDeclarations` sniff will now also report on return type hints when used with closures. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :pushpin: Allow for anonymous classes in the `inClassScope()` utility method. [#315](https://github.com/wimg/PHPCompatibility/pull/315)
- :pushpin: The function call parameter related utility functions can now also be used to get the individual items from an array declaration. [#300](https://github.com/wimg/PHPCompatibility/pull/300)
- :twisted_rightwards_arrows: The `NewScalarReturnTypeDeclarations` sniff has been renamed to `NewReturnTypeDeclarations`. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :rewind: The `ForbiddenNames` sniff will now also correctly ignore anonymous classes when used in combination with PHPCS < 2.3.4. [#319](https://github.com/wimg/PHPCompatibility/pull/319)
- :rewind: The `NewAnonymousClasses` sniff will now correctly recognize and report on anonymous classes when used in combination with PHPCS < 2.5.2. [#325](https://github.com/wimg/PHPCompatibility/pull/325)
- :rewind: The `NewGroupUseDeclarations` sniff will now correctly recognize and report on group use statements when used in combination with PHPCS < 2.6.0. [#320](https://github.com/wimg/PHPCompatibility/pull/320)
- :rewind: The `NewNullableTypes` sniff will now correctly recognize and report on nullable return types when used in combination with PHPCS < 2.6.0. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :rewind: The `NewReturnTypeDeclarations` sniff will now correctly recognize and report on new return types when used in combination with PHPCS < 2.6.0. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#317](https://github.com/wimg/PHPCompatibility/pull/317)
- :recycle: Defer to upstream `hasCondition()` utility method where appropriate. [#315](https://github.com/wimg/PHPCompatibility/pull/315)
- :recycle: Minor refactoring of some unit test code. [#304](https://github.com/wimg/PHPCompatibility/pull/304), [#303](https://github.com/wimg/PHPCompatibility/pull/303), [#318](https://github.com/wimg/PHPCompatibility/pull/318)
- :wrench: All unit tests now have appropriate `@group` annotations allowing for quicker/easier testing of a select group of tests/sniffs. [#305](https://github.com/wimg/PHPCompatibility/pull/305)
- :wrench: All unit tests now have appropriate `@covers` annotations to improve code coverage reporting and remove bleed through of accidental coverage. [#307](https://github.com/wimg/PHPCompatibility/pull/307)
- :wrench: Minor tweaks to the travis script. [#322](https://github.com/wimg/PHPCompatibility/pull/322)
- :green_heart: The PHPCompatibility code base itself will now be checked for cross-version compatibility during build testing. [#322](https://github.com/wimg/PHPCompatibility/pull/322)

### Fixed
- :bug: The `ConstantArraysUsingDefine` sniff would throw false positives if the value of the `define()` was retrieved via a function call and an array parameter was passed. [#327](https://github.com/wimg/PHPCompatibility/pull/327)
- :bug: The `ForbiddenCallTimePassByReference` sniff would throw false positives on assign by reference within function calls or conditions. [#302](https://github.com/wimg/PHPCompatibility/pull/302) Fixes the last two cases reported in [#68](https://github.com/wimg/PHPCompatibility/issues/68#issuecomment-231366445)
- :bug: The `ForbiddenGlobalVariableVariableSniff` sniff would only examine the first variable in a `global ...` statement causing unreported issues if subsequent variables were variable variables. [#316](https://github.com/wimg/PHPCompatibility/pull/316)
- :bug: The `NewKeywords` sniff would throw a false positive for the `const` keyword when encountered in an interface. [#312](https://github.com/wimg/PHPCompatibility/pull/312)
- :bug: The `NewNullableTypes` sniff would not report on nullable return types for namespaced classnames used as a type hint. [#323](https://github.com/wimg/PHPCompatibility/pull/323)
- :bug: The `PregReplaceEModifier` sniff would always consider the first parameter passed as a single regex, while it could also be an array of regexes. This led to false positives and potentially unreported use of the `e` modifier when an array of regexes was passed. [#300](https://github.com/wimg/PHPCompatibility/pull/300)
- :bug: The `PregReplaceEModifier` sniff could misidentify the regex delimiter when the regex to be examined was concatenated together from various text strings taken from a compound parameter leading to false positives. [#300](https://github.com/wimg/PHPCompatibility/pull/300)
- :white_check_mark: Compatibility with PHPCS 2.7.x. Deal with changed behaviour of the upstream PHP tokenizer and utility function(s). [#313](https://github.com/wimg/PHPCompatibility/pull/313), [#323](https://github.com/wimg/PHPCompatibility/pull/323), [#326](https://github.com/wimg/PHPCompatibility/pull/326), [#340](https://github.com/wimg/PHPCompatibility/pull/340)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.1.1] - 2016-12-14

See all related issues and PRs in the [7.1.1 milestone].

### Added
- :star: `ForbiddenNamesAsDeclared` sniff: detection of the PHP 7.1 `iterable` and `void` reserved keywords when used to name classes, interfaces or traits. [#298](https://github.com/wimg/PHPCompatibility/pull/298)

### Fixed
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would incorrectly throw an error if the `clone` keyword was used with parenthesis. [#299](https://github.com/wimg/PHPCompatibility/pull/299). Fixes [#284](https://github.com/wimg/PHPCompatibility/issues/284)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.1.0] - 2016-12-14

See all related issues and PRs in the [7.1.0 milestone].

### Added
- :star: New `stringToErrorCode()`, `arrayKeysToLowercase()` and `addMessage()` utility methods to the `PHPCompatibility_Sniff` class. [#291](https://github.com/wimg/PHPCompatibility/pull/291).

### Changed
- :pushpin: All sniff error messages now have modular error codes allowing for selectively disabling individual checks - and even selectively disabling individual sniff for specific files - without disabling the complete sniff. [#291](https://github.com/wimg/PHPCompatibility/pull/291)
- :pencil2: Minor changes to some of the error message texts for consistency across sniffs. [#291](https://github.com/wimg/PHPCompatibility/pull/291)
- :recycle: Refactored the complex version sniffs to reduce code duplication. [#291](https://github.com/wimg/PHPCompatibility/pull/291)
- :recycle: Miscellaneous other refactoring for improved performance and sniff accuracy. [#291](https://github.com/wimg/PHPCompatibility/pull/291)
- :umbrella: The unit tests for the `RemovedExtensions` sniff now verify that the correct alternative extension is being suggested. [#291](https://github.com/wimg/PHPCompatibility/pull/291)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.8] - 2016-10-31 - :ghost: Spooky! :jack_o_lantern:

See all related issues and PRs in the [7.0.8 milestone].

### Added
- :star2: New `ForbiddenNamesAsDeclared` sniff: detection of the [other reserved keywords](http://php.net/manual/en/reserved.other-reserved-words.php) which are reserved as of PHP 7.0 (or higher). [#287](https://github.com/wimg/PHPCompatibility/pull/287). Fixes [#115](https://github.com/wimg/PHPCompatibility/issues/115).
    These were previously sniffed for by the `ForbiddenNames` and `ForbiddenNamesAsInvokedFunctions` sniffs causing false positives as the rules for their reservation are different from the rules for "normal" [reserved keywords](http://php.net/manual/en/reserved.keywords.php).
- :star: New `inUseScope()` utility method to the `PHPCompatibility_Sniff` class which handles PHPCS cross-version compatibility when determining the scope of a `use` statement. [#271](https://github.com/wimg/PHPCompatibility/pull/271).
- :umbrella: More unit tests for the `ForbiddenNames` sniff. [#271](https://github.com/wimg/PHPCompatibility/pull/271).

### Changed
- :pushpin: _Deprecated_ functionality should throw a `warning`. _Removed_ or otherwise unavailable functionality should throw an `error`. This distinction was previously not consistently applied everywhere. [#286](https://github.com/wimg/PHPCompatibility/pull/286)
    This change affects the following sniffs:
    * `DeprecatedPHP4StyleConstructors` will now throw a `warning` instead of an `error` for deprecated PHP4 style class constructors.
    * `ForbiddenCallTimePassByReference` will now throw a `warning` if the `testVersion` is `5.3` and an `error` if the `testVersion` if `5.4` or higher.
    * `MbstringReplaceEModifier` will now throw a `warning` instead of an `error` for usage of the deprecated `e` modifier.
    * `PregReplaceEModifier` will now throw a `warning` if the `testVersion` is `5.5` or `5.6` and an `error` if the `testVersion` if `7.0` or higher. Fixes [#290](https://github.com/wimg/PHPCompatibility/issues/290).
    * `TernaryOperators` will now throw an `error` when the `testVersion` < `5.3` and the middle part has been omitted.
    * `ValidIntegers` will now throw a `warning` when an invalid binary integer is detected.
- :pencil2: `DeprecatedFunctions` and `DeprecatedIniDirectives` sniffs: minor change in the sniff error message text. Use _"removed"_ rather than the ominous _"forbidden"_. [#285](https://github.com/wimg/PHPCompatibility/pull/285)
    Also updated relevant internal variable names and documentation to match.

### Fixed
- :bug: `ForbiddenNames` sniff would throw false positives for `use` statements which changed the visibility of methods in traits. [#271](https://github.com/wimg/PHPCompatibility/pull/271).
- :bug: `ForbiddenNames` sniff would not report reserved keywords when used in combination with `use function` or `use const`. [#271](https://github.com/wimg/PHPCompatibility/pull/271).
- :bug: `ForbiddenNames` sniff would potentially - unintentionally - skip over tokens, thereby - potentially - not reporting all errors. [#271](https://github.com/wimg/PHPCompatibility/pull/271).
- :wrench: Composer config: `prefer-stable` should be a root element of the json file. Fixes [#277](https://github.com/wimg/PHPCompatibility/issues/277).

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.7] - 2016-10-20

See all related issues and PRs in the [7.0.7 milestone].

### Added
- :star2: New `ForbiddenBreakContinueOutsideLoop` sniff: verify that `break`/`continue` is not used outside of a loop structure. This will cause fatal errors since PHP 7.0. [#278](https://github.com/wimg/PHPCompatibility/pull/278). Fixes [#275](https://github.com/wimg/PHPCompatibility/issues/275)
- :star2: New `NewConstVisibility` sniff: detect visibility indicators for `class` and `interface` constants as introduced in PHP 7.1. [#280](https://github.com/wimg/PHPCompatibility/pull/280). Fixes [#249](https://github.com/wimg/PHPCompatibility/issues/249)
- :star2: New `NewHashAlgorithms` sniff to check used hash algorithms against the PHP version in which they were introduced. [#242](https://github.com/wimg/PHPCompatibility/pull/242)
- :star2: New `NewMultiCatch` sniff: detect catch statements catching multiple Exceptions as introduced in PHP 7.1. [#281](https://github.com/wimg/PHPCompatibility/pull/281). Fixes [#251](https://github.com/wimg/PHPCompatibility/issues/251)
- :star2: New `NewNullableTypes` sniff: detect nullable parameter and return type hints (only supported in PHPCS >= 2.3.4) as introduced in PHP 7.1. [#282](https://github.com/wimg/PHPCompatibility/pull/282). Fixes [#247](https://github.com/wimg/PHPCompatibility/issues/247)
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.1 removed `session` ini directives. [#256](https://github.com/wimg/PHPCompatibility/pull/256)
- :star: `NewFunctions` sniff: recognize new `socket_export_stream()` function as introduced in PHP 7.0.7. [#264](https://github.com/wimg/PHPCompatibility/pull/264)
- :star: `NewFunctions` sniff: recognize new `curl_...()`, `is_iterable()`, `pcntl_async_signals()`, `session_create_id()`, `session_gc()` functions as introduced in PHP 7.1. [#273](https://github.com/wimg/PHPCompatibility/pull/273)
- :star: `NewFunctionParameters` sniff: recognize new OpenSSL function parameters as introduced in PHP 7.1. [#258](https://github.com/wimg/PHPCompatibility/pull/258)
- :star: `NewIniDirectives` sniff: recognize new `session` ini directives as introduced in PHP 7.1. [#259](https://github.com/wimg/PHPCompatibility/pull/259)
- :star: `NewScalarReturnTypeDeclarations` sniff: recognize PHP 7.1 `void` return type hint. [#250](https://github.com/wimg/PHPCompatibility/pull/250)
- :star: `NewScalarTypeDeclarations` sniff: recognize PHP 7.1 `iterable` type hint. [#255](https://github.com/wimg/PHPCompatibility/pull/255)
- :star: Recognize the PHP 7.1 deprecated `mcrypt` functionality in the `RemovedExtensions`, `DeprecatedFunctions` and `DeprecatedIniDirectives` sniffs. [#257](https://github.com/wimg/PHPCompatibility/pull/257)

### Changed
- :pushpin: `LongArrays` sniff used to only throw `warning`s. It will now throw `error`s for PHP versions in which the long superglobals have been removed. [#270](https://github.com/wimg/PHPCompatibility/pull/270)
- :pushpin: The `NewIniDirectives` sniff used to always throw an `warning`. Now it will throw an `error` when a new ini directive is used in combination with `ini_set()`. [#246](https://github.com/wimg/PHPCompatibility/pull/246).
- :pushpin: `RemovedHashAlgorithms` sniff: also recognize removed algorithms when used with the PHP 5.5+ `hash_pbkdf2()` function. [#240](https://github.com/wimg/PHPCompatibility/pull/240)
- :pushpin: Properly recognize nullable type hints in the `getMethodParameters()` utility method. [#282](https://github.com/wimg/PHPCompatibility/pull/282)
- :pencil2: `DeprecatedPHP4StyleConstructors` sniff: minor error message text fix. [#236](https://github.com/wimg/PHPCompatibility/pull/236)
- :pencil2: `NewIniDirectives` sniff: improved precision for the introduction version numbers being reported. [#246](https://github.com/wimg/PHPCompatibility/pull/246)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#238](https://github.com/wimg/PHPCompatibility/pull/238), [#244](https://github.com/wimg/PHPCompatibility/pull/244), [#240](https://github.com/wimg/PHPCompatibility/pull/240), [#276](https://github.com/wimg/PHPCompatibility/pull/276)
- :umbrella: Re-activate the unit tests for the `NewScalarReturnTypeDeclarations` sniff. [#250](https://github.com/wimg/PHPCompatibility/pull/250)

### Fixed
- :bug: The `DeprecatedPHP4StyleConstructors` sniff would not report errors when the case of the class name and the PHP4 constructor function name did not match. [#236](https://github.com/wimg/PHPCompatibility/pull/236)
- :bug: `LongArrays` sniff would report false positives for class properties shadowing removed PHP superglobals. [#270](https://github.com/wimg/PHPCompatibility/pull/270). Fixes [#268](https://github.com/wimg/PHPCompatibility/issues/268).
- :bug: The `NewClasses` sniff would not report errors when the case of the class name used and "official" class name did not match. [#237](https://github.com/wimg/PHPCompatibility/pull/237)
- :bug: The `NewIniDirectives` sniff would report violations against the PHP version in which the ini directive was introduced. This should be the version below it. [#246](https://github.com/wimg/PHPCompatibility/pull/246)
- :bug: `PregReplaceEModifier` sniff would report false positives for compound regex parameters with different quote types. [#266](https://github.com/wimg/PHPCompatibility/pull/266). Fixes [#265](https://github.com/wimg/PHPCompatibility/issues/265).
- :bug: `RemovedGlobalVariables` sniff would report false positives for lowercase/mixed cased variables shadowing superglobals. [#245](https://github.com/wimg/PHPCompatibility/pull/245).
- :bug: The `RemovedHashAlgorithms` sniff would not report errors when the case of the hash function name used and "official" class name did not match. [#240](https://github.com/wimg/PHPCompatibility/pull/240)
- :bug: The `ShortArray` sniff would report all violations on the line of the PHP open tag, not on the lines of the short array open/close tags. [#238](https://github.com/wimg/PHPCompatibility/pull/238)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.6] - 2016-09-23

See all related issues and PRs in the [7.0.6 milestone].

### Added
- :star: New `stripQuotes()` utility method in the `PHPCompatibility_Sniff` base class to strip quotes which surround text strings in a consistent manner. [#224](https://github.com/wimg/PHPCompatibility/pull/224)
- :books: Readme: Add _PHP Version Support_ section. [#225](https://github.com/wimg/PHPCompatibility/pull/225)

### Changed
- :pushpin: The `ForbiddenCallTimePassByReference` sniff will now also report the deprecation as of PHP 5.3, not just its removal as of PHP 5.4. [#203](https://github.com/wimg/PHPCompatibility/pull/203)
- :pushpin: The `NewFunctionArrayDereferencing` sniff will now also check _method_ calls for array dereferencing, not just function calls. [#229](https://github.com/wimg/PHPCompatibility/pull/229). Fixes [#227](https://github.com/wimg/PHPCompatibility/issues/227).
- :pencil2: The `NewExecutionDirectives` sniff will now throw `warning`s instead of `error`s for invalid values encountered in execution directives. [#223](https://github.com/wimg/PHPCompatibility/pull/223)
- :pencil2: Minor miscellaneous fixes. [#231](https://github.com/wimg/PHPCompatibility/pull/231)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#219](https://github.com/wimg/PHPCompatibility/pull/219), [#203](https://github.com/wimg/PHPCompatibility/pull/203)
- :recycle: Defer to upstream `findImplementedInterfaceNames()` utility method when it exists. [#222](https://github.com/wimg/PHPCompatibility/pull/222)
- :wrench: Exclude the test files from analysis by Scrutinizer CI. [#230](https://github.com/wimg/PHPCompatibility/pull/230)

### Removed
- :no_entry_sign: Some redundant code. [#232](https://github.com/wimg/PHPCompatibility/pull/232)

### Fixed
- :bug: The `EmptyNonVariable` sniff would throw false positives for variable variables and for array access with a (partially) variable array index. [#212](https://github.com/wimg/PHPCompatibility/pull/212). Fixes [#210](https://github.com/wimg/PHPCompatibility/issues/210).
- :bug: The `NewFunctionArrayDereferencing` sniff would throw false positives for lines of code containing both a function call as well as square brackets, even when they were unrelated. [#228](https://github.com/wimg/PHPCompatibility/pull/228). Fixes [#226](https://github.com/wimg/PHPCompatibility/issues/226).
- :bug: `ParameterShadowSuperGlobals` sniff would report false positives for lowercase/mixed cased variables shadowing superglobals. [#218](https://github.com/wimg/PHPCompatibility/pull/218). Fixes [#214](https://github.com/wimg/PHPCompatibility/issues/214).
- :bug: The `determineNamespace()` utility method now accounts properly for namespaces within scoped `declare()` statements. [#221](https://github.com/wimg/PHPCompatibility/pull/221)
- :books: Readme: Logo alignment in the Credits section. [#233](https://github.com/wimg/PHPCompatibility/pull/233)

### Credits
Thanks go out to [Jason Stallings], [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:


## [7.0.5] - 2016-09-08

See all related issues and PRs in the [7.0.5 milestone].

### Added
- :star2: New `MbstringReplaceEModifier` sniff to detect the use of the PHP 7.1 deprecated `e` modifier in Mbstring regex functions. [#202](https://github.com/wimg/PHPCompatibility/pull/202)
- :star: The `ForbiddenBreakContinueVariableArguments` sniff will now also report on `break 0`/`continue 0` which is not allowed since PHP 5.4. [#209](https://github.com/wimg/PHPCompatibility/pull/209)
- :star: New `getFunctionCallParameters()`, `getFunctionCallParameter()` utility methods in the `PHPCompatibility_Sniff` base class. [#170](https://github.com/wimg/PHPCompatibility/pull/170)
- :star: New `tokenHasScope()` utility method in the `PHPCompatibility_Sniff` base class. [#189](https://github.com/wimg/PHPCompatibility/pull/189)
- :umbrella: Unit test for `goto` and `callable` detection and some other miscellanous extra unit tests for the `NewKeywords` sniff. [#189](https://github.com/wimg/PHPCompatibility/pull/189)
- :books: Readme: Information for sniff developers about running unit tests for _other_ sniff libraries using the PHPCS native test framework without running into conflicts with the PHPCompatibility specific unit test framework. [#217](https://github.com/wimg/PHPCompatibility/pull/217)

### Changed
- :pushpin: The `ForbiddenNames` sniff will now also check interface declarations for usage of reserved keywords. [#200](https://github.com/wimg/PHPCompatibility/pull/200)
- :pushpin: `PregReplaceEModifier` sniff: improved handling of regexes build up of a combination of variables, function calls and/or text strings. [#201](https://github.com/wimg/PHPCompatibility/pull/201)
- :rewind: The `NewKeywords` sniff will now also correctly recognize new keywords when used in combination with older PHPCS versions and/or run on older PHP versions. [#189](https://github.com/wimg/PHPCompatibility/pull/189)
- :pencil2: `PregReplaceEModifier` sniff: minor improvement to the error message text. [#201](https://github.com/wimg/PHPCompatibility/pull/201)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#170](https://github.com/wimg/PHPCompatibility/pull/170), [#188](https://github.com/wimg/PHPCompatibility/pull/188), [#189](https://github.com/wimg/PHPCompatibility/pull/189), [#199](https://github.com/wimg/PHPCompatibility/pull/199), [#200](https://github.com/wimg/PHPCompatibility/pull/200), [#201](https://github.com/wimg/PHPCompatibility/pull/201), [#208](https://github.com/wimg/PHPCompatibility/pull/208)
- :wrench: The unit tests for the utility methods have been moved to their own subdirectory within `Tests`. [#215](https://github.com/wimg/PHPCompatibility/pull/215)
- :green_heart: The sniffs are now also tested against PHP 7.1 for consistent results. [#216](https://github.com/wimg/PHPCompatibility/pull/216)

### Removed
- :no_entry_sign: Some redundant code. [26d0b6](https://github.com/wimg/PHPCompatibility/commit/26d0b6cf0921f75d93a4faaf09c390f386dde9ff) and [841616](https://github.com/wimg/PHPCompatibility/commit/8416162ea81f4067226324f5948f4a50f7958a9b)

### Fixed
- :bug: `ConstantArraysUsingDefine` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#199](https://github.com/wimg/PHPCompatibility/pull/199)
- :bug: The `DeprecatedIniDirectives` and `NewIniDirectives` sniffs could potentially trigger on the ini value instead of the ini directive name. [#170](https://github.com/wimg/PHPCompatibility/pull/170)
- :bug: `ForbiddenNames` sniff: Reserved keywords when used as the name of a constant declared using `define()` would always be reported independently of the `testVersion` set. [#200](https://github.com/wimg/PHPCompatibility/pull/200)
- :bug: `PregReplaceEModifier` sniff would not report errors when the function name used was not in lowercase. [#201](https://github.com/wimg/PHPCompatibility/pull/201)
- :bug: `TernaryOperators` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#188](https://github.com/wimg/PHPCompatibility/pull/188)
- :bug: The `getFQClassNameFromNewToken()` and `getFQClassNameFromDoubleColonToken()` utility methods would get confused when the class name was a variable instead of being hard-coded, resulting in a PHP warning being thown. [#206](https://github.com/wimg/PHPCompatibility/pull/206). Fixes [#205](https://github.com/wimg/PHPCompatibility/issues/205).
- :bug: The `getFunctionCallParameters()` utility method would incorrectly identify an extra parameter if the last parameter passed to a function would have an - unnecessary - comma after it. The `getFunctionCallParameters()` utility method also did not handle parameters passed as short arrays correctly. [#213](https://github.com/wimg/PHPCompatibility/pull/213). Fixes [#211](https://github.com/wimg/PHPCompatibility/issues/211).
- :umbrella: Unit tests for the `NewFunctionArrayDereferencing` sniff were not being run due to a naming error. [#208](https://github.com/wimg/PHPCompatibility/pull/208)
- :books: Readme: Information about setting the `testVersion` from a custom ruleset was incorrect. [#204](https://github.com/wimg/PHPCompatibility/pull/204)
- :wrench: Path to PHPCS in the unit tests breaking for non-Composer installs. [#198](https://github.com/wimg/PHPCompatibility/pull/198)

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Yoshiaki Yoshida] for their contributions to this version. :clap:


## [7.0.4] - 2016-08-20

See all related issues and PRs in the [7.0.4 milestone].

### Added
- :star2: New `EmptyNonVariable` sniff: detection of empty being used on non-variables for PHP < 5.5. [#187](https://github.com/wimg/PHPCompatibility/pull/187)
- :star2: New `NewMagicMethods` sniff: detection of declaration of magic methods before the method became "magic". Includes a check for the changed behaviour for the `__toString()` magic method in PHP 5.2. [#176](https://github.com/wimg/PHPCompatibility/pull/176). Fixes [#64](https://github.com/wimg/PHPCompatibility/issues/64).
- :star2: New `RemovedAlternativePHPTags` sniff: detection of ASP and script open tags for which support was removed in PHP 7.0. [#184](https://github.com/wimg/PHPCompatibility/pull/184), [#193](https://github.com/wimg/PHPCompatibility/pull/193). Fixes [#127](https://github.com/wimg/PHPCompatibility/issues/127).
- :star: `NonStaticMagicMethods` sniff: detection of the `__callStatic()`, `__sleep()`, `__toString()` and `__set_state()` magic methods.
- :green_heart: Lint all non-test case files for syntax errors during the build testing by Travis. [#192](https://github.com/wimg/PHPCompatibility/pull/192)

### Changed
- :pushpin: `NonStaticMagicMethods` sniff: will now also sniff `trait`s for magic methods. [#174](https://github.com/wimg/PHPCompatibility/pull/174)
- :pushpin: `NonStaticMagicMethods` sniff: will now also check for magic methods which should be declared as `static`. [#174](https://github.com/wimg/PHPCompatibility/pull/174)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#178](https://github.com/wimg/PHPCompatibility/pull/178), [#179](https://github.com/wimg/PHPCompatibility/pull/179), [#174](https://github.com/wimg/PHPCompatibility/pull/174), [#171](https://github.com/wimg/PHPCompatibility/pull/171)
- :recycle: The unit test suite now internally caches PHPCS run results in combination with a set `testVersion` to speed up the running of the unit tests. These are now ~3 x faster. [#148](https://github.com/wimg/PHPCompatibility/pull/148)
- :books: Readme: Minor clarification of the minimum requirements.
- :books: Readme: Advise to use the latest stable version of this repository.
- :wrench: The unit tests can now be run with PHPCS installed in an arbitrary location by passing the location through an environment option. [#191](https://github.com/wimg/PHPCompatibility/pull/191).
- :wrench: Improved coveralls configuration and compatibility. [#194](https://github.com/wimg/PHPCompatibility/pull/194)
- :green_heart: The sniffs are now also tested against PHP 5.2 for consistent results. Except for namespace, trait and group use related errors, most sniffs work as intended on PHP 5.2. [#196](https://github.com/wimg/PHPCompatibility/pull/196)

### Fixed
- :bug: The `ForbiddenBreakContinueVariableArguments` sniff would not report on `break`/`continue` with a closure as an argument. [#171](https://github.com/wimg/PHPCompatibility/pull/171)
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would not report on reserved keywords which were not lowercase. [#186](https://github.com/wimg/PHPCompatibility/pull/186)
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would not report on the `goto` and `namespace` keywords when run on PHP 5.2. [#193](https://github.com/wimg/PHPCompatibility/pull/193)
- :bug: `NewAnonymousClasses` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#195](https://github.com/wimg/PHPCompatibility/pull/195).
- :bug: `NewGroupUseDeclarations` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#190](https://github.com/wimg/PHPCompatibility/pull/190).
- :bug: The `NonStaticMagicMethods` sniff would not report on magic methods when the function name as declared was not in the same case as used in the PHP manual. [#174](https://github.com/wimg/PHPCompatibility/pull/174)
- :wrench: The unit tests would exit with `0` if PHPCS could not be found. [#191](https://github.com/wimg/PHPCompatibility/pull/191)
- :green_heart: The PHPCompatibility library itself was not fully compatible with PHP 5.2. [#193](https://github.com/wimg/PHPCompatibility/pull/193)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.3] - 2016-08-18

See all related issues and PRs in the [7.0.3 milestone].

### Added
- :star2: New `InternalInterfaces` sniff: detection of internal PHP interfaces being which should not be implemented by user land classes. [#144](https://github.com/wimg/PHPCompatibility/pull/144)
- :star2: New `LateStaticBinding` sniff: detection of PHP 5.3 late static binding. [#177](https://github.com/wimg/PHPCompatibility/pull/177)
- :star2: New `NewExecutionDirectives` sniff: verify execution directives set with `declare()`. [#169](https://github.com/wimg/PHPCompatibility/pull/169)
- :star2: New `NewInterfaces` sniff: detection of the use of newly introduced PHP native interfaces. This sniff will also detect unsupported methods when a class implements the `Serializable` interface. [#144](https://github.com/wimg/PHPCompatibility/pull/144)
- :star2: New `RequiredOptionalFunctionParameters` sniff: detection of missing function parameters which were required in earlier PHP versions only to become optional in later versions. [#165](https://github.com/wimg/PHPCompatibility/pull/165)
- :star2: New `ValidIntegers` sniff: detection of binary integers for PHP < 5.4, detection of hexademical numeric strings for which recognition as hex integers was removed in PHP 7.0, detection of invalid binary and octal integers. [#160](https://github.com/wimg/PHPCompatibility/pull/160). Fixes [#55](https://github.com/wimg/PHPCompatibility/issues/55).
- :star: `DeprecatedExtensions` sniff: detect removal of the `ereg` extension in PHP 7. [#149](https://github.com/wimg/PHPCompatibility/pull/149)
- :star: `DeprecatedFunctions` sniff: detection of the PHP 5.0.5 deprecated `php_check_syntax()` and PHP 5.4 deprecated `mysqli_get_cache_stats()` functions. [#155](https://github.com/wimg/PHPCompatibility/pull/155).
- :star: `DeprecatedFunctions` sniff: detect deprecation of a number of the `mysqli` functions in PHP 5.3. [#149](https://github.com/wimg/PHPCompatibility/pull/149)
- :star: `DeprecatedFunctions` sniff: detect removal of the `call_user_method()`, `ldap_sort()`, `ereg_*()` and `mysql_*()` functions in PHP 7.0. [#149](https://github.com/wimg/PHPCompatibility/pull/149)
- :star: `DeprecatedIniDirectives` sniff: detection of a _lot_ more deprecated/removed ini directives. [#146](https://github.com/wimg/PHPCompatibility/pull/146)
- :star: `NewFunctionParameters` sniff: detection of a _lot_ more new function parameters. [#164](https://github.com/wimg/PHPCompatibility/pull/164)
- :star: `NewFunctions` sniff: detection of numerous extra new functions. [#161](https://github.com/wimg/PHPCompatibility/pull/161)
- :star: `NewIniDirectives` sniff: detection of a _lot_ more new ini directives. [#146](https://github.com/wimg/PHPCompatibility/pull/146)
- :star: `NewLanguageConstructs` sniff: detection of the PHP 5.6 ellipsis `...` construct. [#175](https://github.com/wimg/PHPCompatibility/pull/175)
- :star: `NewScalarTypeDeclarations` sniff: detection of PHP 5.1 `array` and PHP 5.4 `callable` type hints. [#168](https://github.com/wimg/PHPCompatibility/pull/168)
- :star: `RemovedFunctionParameters` sniff: detection of a few extra removed function parameters. [#163](https://github.com/wimg/PHPCompatibility/pull/163)
- :star: Detection of functions and methods with a double underscore prefix as these are reserved by PHP for future use. The existing upstream `Generic.NamingConventions.CamelCapsFunctionName` sniff is re-used for this with some customization. [#173](https://github.com/wimg/PHPCompatibility/pull/173)
- :star: New `getFQClassNameFromNewToken()`, `getFQExtendedClassName()`, `getFQClassNameFromDoubleColonToken()`, `getFQName()`, `isNamespaced()`, `determineNamespace()` and `getDeclaredNamespaceName()` utility methods in the `PHPCompatibility_Sniff` base class for namespace determination. [#162](https://github.com/wimg/PHPCompatibility/pull/162)
- :recycle: New `inClassScope()` utility method in the `PHPCompatibility_Sniff` base class. [#168](https://github.com/wimg/PHPCompatibility/pull/168)
- :recycle: New `doesFunctionCallHaveParameters()` and `getFunctionCallParameterCount()` utility methods in the `PHPCompatibility_Sniff` base class. [#153](https://github.com/wimg/PHPCompatibility/pull/153)
- :umbrella: Unit test for `__halt_compiler()` detection by the `NewKeywords` sniff.
- :umbrella: Unit tests for the `NewFunctions` sniff. [#161](https://github.com/wimg/PHPCompatibility/pull/161)
- :umbrella: Unit tests for the `ParameterShadowSuperGlobals` sniff. [#180](https://github.com/wimg/PHPCompatibility/pull/180)
- :wrench: Minimal config for Scrutinizer CI. [#145](https://github.com/wimg/PHPCompatibility/pull/145).

### Changed
- :pushpin: The `DeprecatedIniDirectives` and the `NewIniDirectives` sniffs will now indicate an alternative ini directive in case the directive has been renamed. [#146](https://github.com/wimg/PHPCompatibility/pull/146)
- :pushpin: The `NewClasses` sniff will now also report on new classes being extended by child classes. [#140](https://github.com/wimg/PHPCompatibility/pull/140).
- :pushpin: The `NewClasses` sniff will now also report on static use of new classes. [#162](https://github.com/wimg/PHPCompatibility/pull/162).
- :pushpin: The `NewScalarTypeDeclarations` sniff will now throw an error on use of type hints pre-PHP 5.0. [#168](https://github.com/wimg/PHPCompatibility/pull/168)
- :pushpin: The `NewScalarTypeDeclarations` sniff will now verify type hints used against typical mistakes. [#168](https://github.com/wimg/PHPCompatibility/pull/168)
- :pushpin: The `ParameterShadowSuperGlobals` sniff will now do a case-insensitive variable name compare. [#180](https://github.com/wimg/PHPCompatibility/pull/180)
- :pushpin: The `RemovedFunctionParameters` sniff will now also report `warning`s on deprecation of function parameters. [#163](https://github.com/wimg/PHPCompatibility/pull/163)
- :twisted_rightwards_arrows: The check for `JsonSerializable` has been moved from the `NewClasses` sniff to the `NewInterfaces` sniff. [#162](https://github.com/wimg/PHPCompatibility/pull/162)
- :rewind: The `NewLanguageConstructs` sniff will now also recognize new language constructs when used in combination with PHPCS 1.5.x. [#175](https://github.com/wimg/PHPCompatibility/pull/175)
- :pencil2: `NewFunctionParameters` sniff: use correct name for the new parameter for the `dirname()` function. [#164](https://github.com/wimg/PHPCompatibility/pull/164)
- :pencil2: `NewScalarTypeDeclarations` sniff: minor change in the sniff error message text. [#168](https://github.com/wimg/PHPCompatibility/pull/168)
- :pencil2: `RemovedFunctionParameters` sniff: minor change in the sniff error message text. [#163](https://github.com/wimg/PHPCompatibility/pull/163)
- :pencil2: The `ParameterShadowSuperGlobals` sniff now extends the `PHPCompatibility_Sniff` class. [#180](https://github.com/wimg/PHPCompatibility/pull/180)
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#181](https://github.com/wimg/PHPCompatibility/pull/181), [#182](https://github.com/wimg/PHPCompatibility/pull/182), [#166](https://github.com/wimg/PHPCompatibility/pull/166), [#167](https://github.com/wimg/PHPCompatibility/pull/167), [#172](https://github.com/wimg/PHPCompatibility/pull/172), [#180](https://github.com/wimg/PHPCompatibility/pull/180), [#146](https://github.com/wimg/PHPCompatibility/pull/146), [#138](https://github.com/wimg/PHPCompatibility/pull/138)
- :recycle: Various refactoring to remove code duplication in the unit tests and add proper test skip notifications where relevant. [#139](https://github.com/wimg/PHPCompatibility/pull/139), [#149](https://github.com/wimg/PHPCompatibility/pull/149)

### Fixed
- :bug: The `DeprecatedFunctions` sniff was reporting an incorrect deprecation/removal version number for a few functions. [#149](https://github.com/wimg/PHPCompatibility/pull/149)
- :bug: The `DeprecatedIniDirectives` sniff was in select cases reporting deprecation of an ini directive prior to removal, while the ini directive was never deprecated prior to its removal. [#146](https://github.com/wimg/PHPCompatibility/pull/146)
- :bug: The `DeprecatedPHP4StyleConstructors` sniff would cause false positives for methods with the same name as the class in namespaced classes. [#167](https://github.com/wimg/PHPCompatibility/pull/167)
- :bug: The `ForbiddenEmptyListAssignment` sniff did not report errors when there were only comments or parentheses between the list parentheses. [#166](https://github.com/wimg/PHPCompatibility/pull/166)
- :bug: The `ForbiddenEmptyListAssignment` sniff will no longer cause false positives during live coding. [#166](https://github.com/wimg/PHPCompatibility/pull/166)
- :bug: The `NewClasses` sniff would potentially misidentify namespaced classes as PHP native classes. [#161](https://github.com/wimg/PHPCompatibility/pull/162)
- :bug: The `NewFunctions` sniff would fail to identify called functions when the function call was not lowercase. [#161](https://github.com/wimg/PHPCompatibility/pull/161)
- :bug: The `NewFunctions` sniff would potentially misidentify namespaced userland functions as new functions. [#161](https://github.com/wimg/PHPCompatibility/pull/161)
- :bug: The `NewIniDirectives` sniff was reporting an incorrect introduction version number for a few ini directives. [#146](https://github.com/wimg/PHPCompatibility/pull/146)
- :bug: `NewKeywords` sniff: the use of the `const` keyword should only be reported when used outside of a class for PHP < 5.3. [#147](https://github.com/wimg/PHPCompatibility/pull/147). Fixes [#129](https://github.com/wimg/PHPCompatibility/issues/129).
- :bug: The `RemovedExtensions` sniff was incorrectly reporting a number of extensions as being removed in PHP 5.3 while they were actually removed in PHP 5.1. [#156](https://github.com/wimg/PHPCompatibility/pull/156)
- :bug: :recycle: The `NewFunctionParameters` and `RemovedFunctionParameters` now use the new `doesFunctionCallHaveParameters()` and `getFunctionCallParameterCount()` utility methods for improved accuracy in identifying function parameters. This fixes several false positives. [#153](https://github.com/wimg/PHPCompatibility/pull/153) Fixes [#120](https://github.com/wimg/PHPCompatibility/issues/120), [#151](https://github.com/wimg/PHPCompatibility/issues/151), [#152](https://github.com/wimg/PHPCompatibility/issues/152).
- :bug: A number of sniffs would return `false` if the examined construct was not found. This could potentially cause race conditions/infinite sniff loops. [#138](https://github.com/wimg/PHPCompatibility/pull/138)
- :wrench: The unit tests would fail to run when used in combination with a PEAR install of PHPCS. [#157](https://github.com/wimg/PHPCompatibility/pull/157).
- :green_heart: Unit tests failing against PHPCS 2.6.1. [#158](https://github.com/wimg/PHPCompatibility/pull/158)
    The unit tests *will* still fail against PHPCS 2.6.2 due to a bug in PHPCS itself. This bug does not affect the running of the sniffs outside of a unit test context.

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.2] - 2016-08-03

See all related issues and PRs in the [7.0.2 milestone].

### Added
- :star: `RemovedExtensions` sniff: ability to whitelist userland functions for which the function prefix overlaps with a prefix of a deprecated/removed extension. [#130](https://github.com/wimg/PHPCompatibility/pull/130). Fixes [#123](https://github.com/wimg/PHPCompatibility/issues/123).
    To use this feature, add the `functionWhitelist` property in your custom ruleset. For more information, see the [README](https://github.com/wimg/PHPCompatibility#phpcompatibility-specific-options).

### Changed
- :pencil2: A number of sniffs contained `public` class properties. Within PHPCS, `public` properties can be overruled via a custom ruleset. This was not the intention, so the visibility of these properties has been changed to `protected`. [#135](https://github.com/wimg/PHPCompatibility/pull/135)
- :wrench: Composer config: Stable packages are preferred over unstable/dev.
- :pencil2: Ruleset name. [#134](https://github.com/wimg/PHPCompatibility/pull/134)

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:


## [7.0.1] - 2016-08-02

See all related issues and PRs in the [7.0.1 milestone].

### Changed
- :pushpin: The `DeprecatedIniDirectives` sniff used to throw an `error` when a deprecated ini directive was used in combination with `ini_get()`. It will now throw a `warning` instead. [#122](https://github.com/wimg/PHPCompatibility/pull/122) Fixes [#119](https://github.com/wimg/PHPCompatibility/issues/119).
    Usage of deprecated ini directives in combination with `ini_set()` will still throw an `error`.
- :pushpin: The `PregReplaceEModifier` sniff now also detects the `e` modifier when used with the `preg_filter()` function. While this is undocumented, the `e` modifier was supported by the `preg_filter()` function as well. [#128](https://github.com/wimg/PHPCompatibility/pull/128)
- :pencil2: The `RemovedExtensions` sniff contained superfluous deprecation information in the error message. [#131](https://github.com/wimg/PHPCompatibility/pull/131)

### Removed
- :wrench: Duplicate builds from the Travis CI build matrix. [#132](https://github.com/wimg/PHPCompatibility/pull/132)

### Fixed
- :bug: The `ForbiddenNames` sniff did not allow for the PHP 5.6 `use function ...` and `use const ...` syntax. [#126](https://github.com/wimg/PHPCompatibility/pull/126) Fixes [#124](https://github.com/wimg/PHPCompatibility/issues/124).
- :bug: The `NewClasses` sniff failed to detect new classes when the class was instantiated without parenthesis, i.e. `new NewClass;`. [#121](https://github.com/wimg/PHPCompatibility/pull/121)
- :bug: The `PregReplaceEModifier` sniff failed to detect the `e` modifier when using bracket delimiters for the regex other than the `{}` brackets. [#128](https://github.com/wimg/PHPCompatibility/pull/128)
- :green_heart: Unit tests failing against PHPCS 2.6.1.

### Credits
Thanks go out to [Jason Stallings], [Juliette Reinders Folmer] and [Ryan Neufeld] for their contributions to this version. :clap:


## [7.0] - 2016-07-02

See all related issues and PRs in the [7.0 milestone].

### Added
- :zap: Ability to specify a range of PHP versions against which to test your code base for compatibility, i.e. `--runtime-set testVersion 5.0-5.4` will now test your code for compatibility with PHP 5.0 up to PHP 5.4. [#99](https://github.com/wimg/PHPCompatibility/pull/99)
- :star2: New `NewFunctionArrayDereferencing` sniff to detect function array dereferencing as introduced in PHP 5.4. Fixes [#52](https://github.com/wimg/PHPCompatibility/issues/52).
- :star2: New `ShortArray` sniff to detect short array syntax as introduced in PHP 5.4. [#97](https://github.com/wimg/PHPCompatibility/pull/97). Fixes [#47](https://github.com/wimg/PHPCompatibility/issues/47).
- :star2: New `TernaryOperators` sniff to detect ternaries without the middle part (`elvis` operator) as introduced in PHP 5.3. [#101](https://github.com/wimg/PHPCompatibility/pull/101), [#103](https://github.com/wimg/PHPCompatibility/pull/103). Fixes [#49](https://github.com/wimg/PHPCompatibility/issues/49).
- :star2: New `ConstantArraysUsingDefine` sniff to detect constants declared using `define()` being assigned an `array` value which was not allowed prior to PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `DeprecatedPHP4StyleConstructors` sniff to detect PHP 4 style class constructor methods which are deprecated as of PHP 7. [#109](https://github.com/wimg/PHPCompatibility/pull/109).
- :star2: New `ForbiddenEmptyListAssignment` sniff to detect empty list() assignments which have been removed in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `ForbiddenFunctionParametersWithSameName` sniff to detect functions declared with multiple same-named parameters which is no longer accepted since PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `ForbiddenGlobalVariableVariable` sniff to detect variable variables being made `global` which is not allowed since PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `ForbiddenNegativeBitshift` sniff to detect bitwise shifts by negative number which will throw an ArithmeticError in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `ForbiddenSwitchWithMultipleDefaultBlocks` sniff to detect switch statements with multiple default blocks which is not allowed since PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `NewAnonymousClasses` sniff to detect anonymous classes as introduced in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `NewFunctionParameters` sniff to detect use of new parameters in build-in PHP functions. Initially only sniffing for the new PHP 7.0 function parameters and the new PHP 5.3+ `before_needle` parameter for the `strstr()` function. [#110](https://github.com/wimg/PHPCompatibility/pull/110), [#112](https://github.com/wimg/PHPCompatibility/pull/112). Fixes [#27](https://github.com/wimg/PHPCompatibility/issues/27).
- :star2: New `NewGroupUseDeclarations` sniff to detect group use declarations as introduced in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `NewScalarReturnTypeDeclarations` sniff to detect scalar return type hints as introduced in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `NewScalarTypeDeclarations` sniff to detect scalar function parameter type hints as introduced in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `RemovedFunctionParameters` sniff to detect use of removed parameters in build-in PHP functions. Initially only sniffing for the function parameters removed in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star2: New `RemovedGlobalVariables` sniff to detect the PHP 7.0 removed `$HTTP_RAW_POST_DATA` superglobal. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `DeprecatedFunctions` sniff: detection of the PHP 5.4 deprecated OCI8 functions. [#93](https://github.com/wimg/PHPCompatibility/pull/93)
- :star: `ForbiddenNamesAsInvokedFunctions` sniff: recognize PHP 5.5 `finally` as a reserved keywords when invoked as a function. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `NewKeywords` sniff: detection of the use of the PHP 5.1+ `__halt_compiler` keyword. Fixes [#50](https://github.com/wimg/PHPCompatibility/issues/50).
- :star: `NewKeywords` sniff: detection of the PHP 5.3+ `nowdoc` syntax. Fixes [#48](https://github.com/wimg/PHPCompatibility/issues/48).
- :star: `NewKeywords` sniff: detection of the use of the `const` keyword outside of a class for PHP < 5.3. Fixes [#50](https://github.com/wimg/PHPCompatibility/issues/50).
- :star: `DeprecatedFunctions` sniff: recognize PHP 7.0 deprecated and removed functions. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.0 removed ini directives. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `ForbiddenNamesAsInvokedFunctions` sniff: recognize new PHP 7.0 reserved keywords when invoked as functions. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `ForbiddenNames` sniff: recognize new PHP 7.0 reserved keywords. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `NewFunctions` sniff: recognize new functions as introduced in PHP 7.0. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `NewLanguageConstructs` sniff: recognize new PHP 7.0 `<=>` "spaceship" and `??` null coalescing operators. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :star: `RemovedExtensions` sniff: recognize PHP 7.0 removed `ereg`, `mssql`, `mysql` and `sybase_ct` extensions. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :umbrella: Additional unit tests for the `NewLanguageConstructs` sniff. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :books: Readme: New section containing information about the use of the `testVersion` config variable.
- :books: Readme: Sponsor credits.

### Changed
- :pushpin: The `DeprecatedIniDirectives` sniff used to always throw an `warning`. Now it will throw an `error` when a removed ini directive is used. [#110](https://github.com/wimg/PHPCompatibility/pull/110).
- :pushpin: The `DeprecatedNewReference` sniff will now throw an error when the `testVersion` includes PHP 7.0 or higher. [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :pushpin: The `ForbiddenNames` sniff now supports detection of reserved keywords when used in combination with PHP 7 anonymous classes. [#108](https://github.com/wimg/PHPCompatibility/pull/108), [#110](https://github.com/wimg/PHPCompatibility/pull/110).
- :pushpin: The `PregReplaceEModifier` sniff will now throw an error when the `testVersion` includes PHP 7.0 or higher.  [#110](https://github.com/wimg/PHPCompatibility/pull/110)
- :pencil2: `NewKeywords` sniff: clarified the error message text for the `use` keyword. Fixes [#46](https://github.com/wimg/PHPCompatibility/issues/46).
- :recycle: Minor refactor of the `testVersion` related utility functions. [#98](https://github.com/wimg/PHPCompatibility/pull/98)
- :wrench: Add autoload to the `composer.json` file. [#96](https://github.com/wimg/PHPCompatibility/pull/96) Fixes [#67](https://github.com/wimg/PHPCompatibility/issues/67).
- :wrench: Minor other updates to the `composer.json` file. [#75](https://github.com/wimg/PHPCompatibility/pull/75)
- :wrench: Improved creation of the code coverage reports needed by coveralls via Travis.
- :green_heart: The sniffs are now also tested against PHP 7.0 for consistent results.

### Fixed
- :bug: The `ForbiddenCallTimePassByReference` sniff was throwing `Undefined index` notices when used in combination with PHPCS 2.2.0. [#100](https://github.com/wimg/PHPCompatibility/pull/100). Fixes [#42](https://github.com/wimg/PHPCompatibility/issues/42).
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would incorrectly throw an error if the `throw` keyword was used with parenthesis. Fixes [#118](https://github.com/wimg/PHPCompatibility/issues/118).
- :bug: The `PregReplaceEModifier` sniff incorrectly identified `e`'s in the pattern as the `e` modifier when using `{}` bracket delimiters for the regex. [#94](https://github.com/wimg/PHPCompatibility/pull/94)
- :bug: The `RemovedExtensions` sniff was throwing an `error` instead of a `warning` for deprecated, but not (yet) removed extensions. Fixes [#62](https://github.com/wimg/PHPCompatibility/issues/62).

### Credits
Thanks go out to AlexMiroshnikov, [Chris Abernethy], [dgudgeon], [djaenecke], [Eugene Maslovich], [Ken Guest], Koen Eelen, [Komarov Alexey], [Mark Clements] and [Remko van Bezooijen] for their contributions to this version. :clap:


## [5.6] - 2015-09-14

See all related issues and PRs in the [5.6 milestone].

### Added
- :star2: New: `NewLanguageConstructs` sniff. The initial version of this sniff checks for the PHP 5.6 `**` power operator and the `**=` power assignment operator. [#87](https://github.com/wimg/PHPCompatibility/pull/87). Fixes [#60](https://github.com/wimg/PHPCompatibility/issues/60).
- :star2: New: `ParameterShadowSuperGlobals` sniff which covers the PHP 5.4 change _Parameter names that shadow super globals now cause a fatal error.`_. [#74](https://github.com/wimg/PHPCompatibility/pull/74)
- :star2: New: `PregReplaceEModifier` sniff which detects usage of the `e` modifier in literal regular expressions used with `preg_replace()`. The `e` modifier will not (yet) be detected when the regex passed is a variable or constant. [#81](https://github.com/wimg/PHPCompatibility/pull/81), [#84](https://github.com/wimg/PHPCompatibility/pull/84). Fixes [#71](https://github.com/wimg/PHPCompatibility/issues/71), [#83](https://github.com/wimg/PHPCompatibility/issues/83).
- :star: `DeprecatedIniDirectives` sniff: PHP 5.6 deprecated ini directives.
- :star: `NewKeywords` sniff: detection of the `goto` keyword introduced in PHP 5.3 and the `callable` keyword introduced in PHP 5.4. [#57](https://github.com/wimg/PHPCompatibility/pull/57)
- :recycle: `PHPCompatibility_Sniff` base class initially containing the `supportsAbove()` and `supportsBelow()` utility methods. (Nearly) All sniffs now extend this base class and use these methods to determine whether or not violations should be reported for a set `testVersion`. [#77](https://github.com/wimg/PHPCompatibility/pull/77)
- :books: Readme: Composer installation instructions. [#32](https://github.com/wimg/PHPCompatibility/pull/32), [#61](https://github.com/wimg/PHPCompatibility/pull/61)
- :wrench: `.gitignore` to ignore vendor and IDE related directories. [#78](https://github.com/wimg/PHPCompatibility/pull/78)
- :green_heart: Code coverage checking via coveralls.

### Changed
- :twisted_rightwards_arrows: The check for the `\` namespace separator has been moved from the `NewKeywords` sniff to the `NewLanguageConstructs` sniff. [#88](https://github.com/wimg/PHPCompatibility/pull/88)
- :pencil2: `DeprecatedIniDirectives` sniff: minor change in the sniff error message text.
- :pencil2: `DeprecatedFunctions` sniff: minor change in the sniff error message text.
- :wrench: Minor updates to the `composer.json` file. [#31](https://github.com/wimg/PHPCompatibility/pull/31), [34](https://github.com/wimg/PHPCompatibility/pull/34), [#70](https://github.com/wimg/PHPCompatibility/pull/70)
- :wrench: Tests: The unit tests can now be run without configuration.
- :wrench: Tests: Skipped unit tests will now be annotated as such. [#85](https://github.com/wimg/PHPCompatibility/pull/85)
- :green_heart: The sniffs are now also tested against PHP 5.6 for consistent results.
- :green_heart: The sniffs are now also tested against PHPCS 2.0+.
- :green_heart: The sniffs are now tested using the new container-based infrastructure in Travis CI. [#37](https://github.com/wimg/PHPCompatibility/pull/37)

### Fixed
- :bug: The `ForbiddenCallTimePassByReference` sniff was throwing false positives when a bitwise and `&` was used in combination with class constants and class properties within function calls. [#44](https://github.com/wimg/PHPCompatibility/pull/44). Fixes [#39](https://github.com/wimg/PHPCompatibility/issues/39).
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff was throwing false positives in certain cases when a comment separated a `try` from the `catch` block. [#29](https://github.com/wimg/PHPCompatibility/pull/29)
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff was incorrectly reporting `instanceof` as being introduced in PHP 5.4 while it has been around since PHP 5.0. [#80](https://github.com/wimg/PHPCompatibility/pull/80)
- :white_check_mark: Compatibility with PHPCS 2.0 - 2.3. [#63](https://github.com/wimg/PHPCompatibility/pull/63), [#65](https://github.com/wimg/PHPCompatibility/pull/65)

### Credits
Thanks go out to Daniel Jänecke, [Declan Kelly], [Dominic], [Jaap van Otterdijk], [Marin Crnkovic], [Mark Clements], [Nick Pack], [Oliver Klee], [Rowan Collins] and [Sam Van der Borght] for their contributions to this version. :clap:


## 5.5 - 2014-04-04

First tagged release.

See all related issues and PRs in the [5.5 milestone].



[Unreleased]: https://github.com/wimg/PHPCompatibility/compare/7.1.5...HEAD
[7.1.5]: https://github.com/wimg/PHPCompatibility/compare/7.1.4...7.1.5
[7.1.4]: https://github.com/wimg/PHPCompatibility/compare/7.1.3...7.1.4
[7.1.3]: https://github.com/wimg/PHPCompatibility/compare/7.1.2...7.1.3
[7.1.2]: https://github.com/wimg/PHPCompatibility/compare/7.1.1...7.1.2
[7.1.1]: https://github.com/wimg/PHPCompatibility/compare/7.1.0...7.1.1
[7.1.0]: https://github.com/wimg/PHPCompatibility/compare/7.0.8...7.1.0
[7.0.8]: https://github.com/wimg/PHPCompatibility/compare/7.0.7...7.0.8
[7.0.7]: https://github.com/wimg/PHPCompatibility/compare/7.0.6...7.0.7
[7.0.6]: https://github.com/wimg/PHPCompatibility/compare/7.0.5...7.0.6
[7.0.5]: https://github.com/wimg/PHPCompatibility/compare/7.0.4...7.0.5
[7.0.4]: https://github.com/wimg/PHPCompatibility/compare/7.0.3...7.0.4
[7.0.3]: https://github.com/wimg/PHPCompatibility/compare/7.0.2...7.0.3
[7.0.2]: https://github.com/wimg/PHPCompatibility/compare/7.0.1...7.0.2
[7.0.1]: https://github.com/wimg/PHPCompatibility/compare/7.0...7.0.1
[7.0]: https://github.com/wimg/PHPCompatibility/compare/5.6...7.0
[5.6]: https://github.com/wimg/PHPCompatibility/compare/5.5...5.6

[7.1.5 milestone]: https://github.com/wimg/PHPCompatibility/milestone/17
[7.1.4 milestone]: https://github.com/wimg/PHPCompatibility/milestone/15
[7.1.3 milestone]: https://github.com/wimg/PHPCompatibility/milestone/14
[7.1.2 milestone]: https://github.com/wimg/PHPCompatibility/milestone/13
[7.1.1 milestone]: https://github.com/wimg/PHPCompatibility/milestone/12
[7.1.0 milestone]: https://github.com/wimg/PHPCompatibility/milestone/11
[7.0.8 milestone]: https://github.com/wimg/PHPCompatibility/milestone/10
[7.0.7 milestone]: https://github.com/wimg/PHPCompatibility/milestone/9
[7.0.6 milestone]: https://github.com/wimg/PHPCompatibility/milestone/8
[7.0.5 milestone]: https://github.com/wimg/PHPCompatibility/milestone/7
[7.0.4 milestone]: https://github.com/wimg/PHPCompatibility/milestone/6
[7.0.3 milestone]: https://github.com/wimg/PHPCompatibility/milestone/5
[7.0.2 milestone]: https://github.com/wimg/PHPCompatibility/milestone/4
[7.0.1 milestone]: https://github.com/wimg/PHPCompatibility/milestone/3
[7.0 milestone]: https://github.com/wimg/PHPCompatibility/milestone/2
[5.6 milestone]: https://github.com/wimg/PHPCompatibility/milestone/1
[5.5 milestone]: https://github.com/wimg/PHPCompatibility/milestone/16

[Arthur Edamov]: https://github.com/edamov
[Chris Abernethy]: https://github.com/cabernet-zerve
[Declan Kelly]: https://github.com/declank
[dgudgeon]: https://github.com/dgudgeon
[djaenecke]: https://github.com/djaenecke
[Dominic]: https://github.com/dol
[Eugene Maslovich]: https://github.com/ehpc
[Jaap van Otterdijk]: https://github.com/jaapio
[Jason Stallings]: https://github.com/octalmage
[Juliette Reinders Folmer]: https://github.com/jrfnl
[Ken Guest]: https://github.com/kenguest
[Komarov Alexey]: https://github.com/erdraug
[Marin Crnkovic]: https://github.com/anorgan
[Mark Clements]: https://github.com/MarkMaldaba
[Nick Pack]: https://github.com/nickpack
[Oliver Klee]: https://github.com/oliverklee
[Remko van Bezooijen]: https://github.com/emkookmer
[Rowan Collins]: https://github.com/IMSoP
[Ryan Neufeld]: https://github.com/ryanneufeld
[Sam Van der Borght]: https://github.com/samvdb
[Tadas Juozapaitis]: https://github.com/kasp3r
[Yoshiaki Yoshida]: https://github.com/kakakakakku
