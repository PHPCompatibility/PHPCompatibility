# Change Log for the PHPCompatibility standard for PHP CodeSniffer

All notable changes to this project will be documented in this file.

This projects adheres to [Keep a CHANGELOG](http://keepachangelog.com/).

Up to version 8.0.0, the `major.minor` version numbers were based on the PHP version for which compatibility check support was added, with `patch` version numbers being specific to this library.
From version 8.0.0 onwards, [Semantic Versioning](https://semver.org/) is used.

<!--
Legend to the icons used: https://github.com/PHPCompatibility/PHPCompatibility/pull/506#discussion_r131650488

:zap:                       = new feature for the project
:two_hearts:                = support for a new PHPCS major.
:gift:                      = (positive impact) policy change
:star2:                     = new sniff
:star:                      = new feature in existing sniff

:fire:                      = hot fix / breaking change
:twisted_rightwards_arrows: = rename or move of a check from one sniff to its own sniff (=> breaking change)
:pushpin:                   = change in error vs warning/severity + significant improvement to existing functionality
:pencil2:                   = minor change in existing functionality
:no_entry_sign:             = code removal
:fire_engine:               = hot fix
:bug:                       = bug fix

:recycle:                   = refactoring
:books:                     = documentation
:umbrella:                  = test improvements
:wrench:                    = CI and configuration files
:green_heart:               = monitoring project health
:white_check_mark:          = compatibility verification/dependencies

(no longer relevant) :rewind: backport of detection of something to older PHPCS versions

-->


## [Unreleased]

_Nothing yet._


## [10.0.0-alpha2] - 2025-11-xx

**IMPORTANT**: This release contains **breaking changes**. Please read and follow the [Upgrade guide in the wiki][wiki-upgrade-to-10.0] carefully before upgrading!

This release includes all improvements and bugfixes from PHPCompatibility [10.0.0-alpha1].

See all related issues and PRs in the [10.0.0-alpha2 milestone].

### Added
- PHP cross-version:
    * :star2: New `PHPCompatibility.Keywords.ForbiddenClassAlias` sniff. [#1952]
    * :star2: New `PHPCompatibility.LanguageConstructs.RemovedLanguageConstructs` sniff. [#1948]
        This sniff in its initial version will detect the PHP 8.5 deprecation of the backtick operator.
- PHP 8.3:
    * :star2: New `PHPCompatibility.ParameterValues.NewClassAliasInternalClass` sniff. [#1951]
    * :star2: New `PHPCompatibility.Syntax.NewDynamicClassConstantFetch` sniff. [#1974]
- PHP 8.5:
    * :star2: New `PHPCompatibility.Classes.NewStaticAvizProperties` sniff. [#1950]
- :star: `PHPCompatibility.Classes.NewClasses` sniff: recognize the new DateTime and SQLite extension related error and exception classes as introduced in PHP 8.3. [#1936], [#1937]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: recognize various constants from the Mhash extension. [#1938]
- :star: `PHPCompatibility.ParameterValues.ChangedIntToBoolParamType` sniff: detect the Zlib `$use_include_path` parameter type change as per PHP 8.5. [#1949]
- :star: The "list based" sniffs, like `NewFunctions`, `RemovedIniDirectives`, `ForbiddenNames` etc, have received updates to account for new/deprecated/removed PHP classes, constants, functions, function parameters, ini directives, reserved namespaces and type casts as per PHP 8.5.
    <details>
    <summary>Associated PRs</summary>

    [#1941],
    [#1942],
    [#1943],
    [#1944],
    [#1945],
    [#1946],
    [#1947],
    [#1953],
    [#1954],
    [#1955],
    [#1956],
    [#1957],
    [#1958],
    [#1959],
    [#1960],
    [#1961],
    [#1962],
    [#1963],
    [#1965],
    [#1966],
    [#1967],
    [#1968],
    [#1969],
    [#1970],
    [#1971]

    </details>
- :books: Documentation for the following sniffs:
    * PHPCompatibility.Syntax.NewShortArray [#1997]

### Changed
- :twisted_rightwards_arrows: `PHPCompatibility.TypeCasts.RemovedTypeCasts` has new error codes. [#1941]
    * The `t_unset_castDeprecatedRemoved` has been changed to `unsetDeprecatedRemoved`.
    * The `t_double_castDeprecatedRemoved` has been changed to `realDeprecatedRemoved`.
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will now also detect incompatible use of the "other" reserved keywords `parent` and `self`. [#1940]
- :wrench: :umbrella: Various housekeeping, including general maintenance, improvements to speed up the sniffs, improvements to CI, the tests and documentation.
    <details>
    <summary>Associated PRs</summary>

    [#1934],
    [#1939],
    [#1964],
    [#1973],
    [#1976],
    [#1983],
    [#1984],
    [#1993],
    [#1995],
    [#1998],
    [#1999]

    </details>

### Credits
Thanks go out to [Anna Filina] and [Shota Okunaka] for their contributions to this version. :clap:

[#1934]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1934
[#1936]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1936
[#1937]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1937
[#1938]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1938
[#1939]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1939
[#1940]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1940
[#1941]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1941
[#1942]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1942
[#1943]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1943
[#1944]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1944
[#1945]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1945
[#1946]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1946
[#1947]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1947
[#1948]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1948
[#1949]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1949
[#1950]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1950
[#1951]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1951
[#1952]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1952
[#1953]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1953
[#1954]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1954
[#1955]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1955
[#1956]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1956
[#1957]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1957
[#1958]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1958
[#1959]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1959
[#1960]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1960
[#1961]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1961
[#1962]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1962
[#1963]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1963
[#1964]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1964
[#1965]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1965
[#1966]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1966
[#1967]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1967
[#1968]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1968
[#1969]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1969
[#1970]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1970
[#1971]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1971
[#1973]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1973
[#1974]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1974
[#1976]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1976
[#1983]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1983
[#1984]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1984
[#1993]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1993
[#1995]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1995
[#1997]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1997
[#1998]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1998
[#1999]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1999


## [10.0.0-alpha1] - 2025-10-21

**IMPORTANT**: This release contains **breaking changes**. Please read and follow the [Upgrade guide in the wiki][wiki-upgrade-to-10.0] carefully before upgrading!

If you use any of the framework/CMS/polyfill specific rulesets, please use the corresponding release for that package.

### Highlighted Changes
- Policy change: As of this release, PHPCompatibility will no longer support a wide range of PHP_CodeSniffer versions.
    The minimum supported PHP_CodeSniffer version will be raised anytime syntax support for a new PHP feature is added to PHP_CodeSniffer and this syntax support is needed for the PHPCompatibility sniffs.
- Policy change: As of this release, the only supported manner of installation of PHPCompatibility will be via Composer.
    Installation using git clones or with the PHP_CodeSniffer PHAR files is still possible, but no support will be provided for this.
- New runtime dependencies:
    * The Composer plugin to register the standard with PHP_CodeSniffer.
    * [PHPCSUtils][phpcsutils], a library of utility function for PHP_CodeSniffer.
- All PHPCompatibility sniffs are now `final` classes.
    * This prevents issues with sniff autoloading.
- All sniffs are now compatible with PHP_CodeSniffer 3.x as well as 4.0.

[wiki-upgrade-to-10.0]: https://github.com/PHPCompatibility/PHPCompatibility/wiki/Upgrading-to-PHPCompatibility-10.0


### Changelog for version 10.0.0-alpha1

See all related issues and PRs in the [10.0.0-alpha1 milestone].

### Added
- New dependencies:
    * The Composer PHPCS installer plugin (`DealerDirect/phpcodesniffer-composer-installer`).
    * [PHPCSUtils][phpcsutils] at "^1.1.2". [#979], [#1362], [#1714], [#1806], [#1900]
- PHP cross-version:
    * :star2: New `PHPCompatibility.Classes.RemovedClasses` sniff. [#1062], [#1705]
- PHP 5.3:
    * :star2: New `PHPCompatibility.Namespaces.ReservedNames` sniff. [#1026]. Fixes [#1025], [#1592], [#1729]
- PHP 5.4:
    * :star2: New `PHPCompatibility.ParameterValues.ChangedObStartEraseFlags` sniff. [#1027], [#1371], [#1876]
    * :star2: New `PHPCompatibility.ParameterValues.NewNumberFormatMultibyteSeparators` sniff. [#1139], [#1380], [#1679] [#1680]
- PHP 7.0:
    * :star2: New `PHPCompatibility.Numbers.RemovedHexadecimalNumericStrings` sniff. [#1004], [#1366]. Fixes [#1345]
    * :star2: New `PHPCompatibility.ParameterValues.NewAssertCustomException` sniff. [#1029]. Partially fixes [#908]
    * :star2: New `PHPCompatibility.Syntax.NewNestedStaticAccess` sniff. [#963], [#1262]. Fixes [#946]
- PHP 7.2:
    * :star2: New `PHPCompatibility.ParameterValues.ForbiddenSessionModuleNameUser` sniff. [#1138], [#1373]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedAssertStringAssertion` sniff. [#1028], [#1282]. Partially fixes [#908]
- PHP 7.4:
    * :star2: New `PHPCompatibility.Numbers.NewNumericLiteralSeparator` sniff. [#984]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedProprietaryCSVEscaping` sniff. [#1787]
- PHP 8.0:
    * :star2: New `PHPCompatibility.Attributes.NewAttributes` sniff. [#1279], [#1480], [#1730], [#1926]
    * :star2: New `PHPCompatibility.Classes.ForbiddenExtendingFinalPHPClass` sniff. [#1486], [#1739]
    * :star2: New `PHPCompatibility.Classes.NewConstructorPropertyPromotion` sniff. [#1417]
    * :star2: New `PHPCompatibility.ControlStructures.NewNonCapturingCatch` sniff. [#1151]
    * :star2: New `PHPCompatibility.FunctionDeclarations.ForbiddenFinalPrivateMethods` sniff. [#1201]
    * :star2: New `PHPCompatibility.FunctionDeclarations.NewTrailingComma` sniff. [#1164], [#1190]
    * :star2: New `PHPCompatibility.FunctionDeclarations.RemovedCallingDestructAfterConstructorExit` sniff. [#1200], [#1227], [#1283]
    * :star2: New `PHPCompatibility.FunctionDeclarations.RemovedOptionalBeforeRequiredParam` sniff. [#1165], [#1206], [#1692], [#1699], [#1886]
    * :star2: New `PHPCompatibility.FunctionUse.NewNamedParameters` sniff. [#1423], [#1806], [#1807]
    * :star2: New `PHPCompatibility.ParameterValues.ChangedIntToBoolParamType` sniff. [#1232], [#1370]
    * :star2: New `PHPCompatibility.ParameterValues.ForbiddenGetClassNoArgsOutsideOO` sniff. [#1602]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedGetDefinedFunctionsExcludeDisabledFalse` sniff. [#1150], [#1162], [#1386], [#1880]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedSplAutoloadRegisterThrowFalse` sniff. [#1181], [#1391], [#1882]
    * :star2: New `PHPCompatibility.Syntax.InterpolatedStringDereferencing` sniff. [#1242]
    * :star2: New `PHPCompatibility.Syntax.NewMagicConstantDereferencing` sniff. [#1233]
- PHP 8.1:
    * :star2: New `PHPCompatibility.Classes.NewFinalConstants` sniff. [#1317], [#1496], [#1629]
    * :star2: New `PHPCompatibility.Classes.NewReadonlyProperties` sniff. [#1426]
    * :star2: New `PHPCompatibility.FunctionDeclarations.RemovedReturnByReferenceFromVoid` sniff. [#1316], [#1560]
    * :star2: New `PHPCompatibility.InitialValue.NewNewInDefine` sniff. [#1465]
    * :star2: New `PHPCompatibility.InitialValue.NewNewInInitializers` sniff. [#1464]
    * :star2: New `PHPCompatibility.Interfaces.RemovedSerializable` sniff. [#1330]
    * :star2: New `PHPCompatibility.Numbers.NewExplicitOctalNotation` sniff. [#1420]
    * :star2: New `PHPCompatibility.ParameterValues.NewArrayMergeRecursiveWithGlobalsVar` sniff. [#1488]
    * :star2: New `PHPCompatibility.ParameterValues.NewHTMLEntitiesFlagsDefault` sniff. [#1419]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedMbCheckEncodingNoArgs` sniff. [#1315]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedVersionCompareOperators` sniff. [#1418]
    * :star2: New `PHPCompatibility.Syntax.NewFirstClassCallables` sniff. [#1425], [#1807]
    * :star2: New `PHPCompatibility.Variables.RemovedIndirectModificationOfGlobals` sniff. [#1487]
- PHP 8.2:
    * :star2: New `PHPCompatibility.Classes.NewReadonlyClasses` sniff. [#1453], [#1686]
    * :star2: New `PHPCompatibility.Constants.NewConstantsInTraits` sniff. [#1443]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedGetClassNoArgss` sniff. [#1614]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedLdapConnectSignatures` sniff. [#1620], [#1671], [#1881]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedMbStrimWidthNegativeWidth` sniff. [#1615]
    * :star2: New `PHPCompatibility.TextStrings.RemovedDollarBraceStringEmbeds` sniff. [#1424]
- PHP 8.3:
    * :star2: New `PHPCompatibility.Classes.NewTypedConstants` sniff. [#1808]
    * :star2: New `PHPCompatibility.Generators.NewYieldFromComment` sniff. [#1792], [#1812]
- PHP 8.4:
    * :star2: New `PHPCompatibility.Classes.ForbiddenClassNameUnderscore` sniff. [#1742]
    * :star2: New `PHPCompatibility.Classes.NewAbstractProperties` sniff. [#1901]
    * :star2: New `PHPCompatibility.Classes.NewFinalProperties` sniff. [#1815]
    * :star2: New `PHPCompatibility.FunctionDeclarations.RemovedImplicitlyNullableParam` sniff. [#1689], [#1694], [#1897]
    * :star2: New `PHPCompatibility.Interfaces.NewPropertiesInInterfaces` sniff. [#1814]
    * :star2: New `PHPCompatibility.ParameterValues.NewExitAsFunctionCall` sniff. [#1807], [#1923]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedDbaKeySplitNullFalse` sniff. [#1745], [#1879]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedTriggerErrorLevel` sniff. [#1741]
    * :star2: New `PHPCompatibility.ParameterValues.RemovedXmlSetHandlerCallbackUnset` sniff. [#1744]
    * :star2: New `PHPCompatibility.Syntax.NewClassMemberAccessWithoutParentheses` sniff. [#1903]
- :star: `PHPCompatibility.Constants.NewMagicClassConstant`: detection of `$obj::class` as allowed since PHP 8.0. [#1166]
- :star: `PHPCompatibility.Operators.NewOperators`: detection of the PHP 8.0 nullsafe object operator. [#1210]
- :star: All "list based" sniffs, like `NewFunctions`, `RemovedIniDirectives`, `ForbiddenNames` etc, have received updates to account for more new/deprecated/removed PHP classes, constants, functions, function parameters, hash algorithms, interfaces, ini directives, reserved keywords and type casts.
    The listed information in these sniffs has also received general accuracy and completeness updates.
    <details>
    <summary>Associated PRs</summary>

    [#985],
    [#1031],
    [#1032],
    [#1033],
    [#1034],
    [#1035],
    [#1036],
    [#1037],
    [#1038],
    [#1039],
    [#1040],
    [#1048],
    [#1049],
    [#1050],
    [#1051],
    [#1052],
    [#1053],
    [#1054],
    [#1055],
    [#1056],
    [#1057],
    [#1058],
    [#1059],
    [#1060],
    [#1061],
    [#1062],
    [#1063],
    [#1064],
    [#1065],
    [#1066],
    [#1067],
    [#1068],
    [#1069],
    [#1070],
    [#1071],
    [#1072],
    [#1073],
    [#1074],
    [#1075],
    [#1076],
    [#1077],
    [#1078],
    [#1079],
    [#1080],
    [#1081],
    [#1082],
    [#1083],
    [#1084],
    [#1085],
    [#1086],
    [#1087],
    [#1088],
    [#1089],
    [#1090],
    [#1091],
    [#1092],
    [#1093],
    [#1094],
    [#1095],
    [#1096],
    [#1097],
    [#1098],
    [#1099],
    [#1100],
    [#1101],
    [#1102],
    [#1103],
    [#1104],
    [#1105],
    [#1106],
    [#1107],
    [#1108],
    [#1109],
    [#1110],
    [#1111],
    [#1112],
    [#1113],
    [#1114],
    [#1115],
    [#1116],
    [#1117],
    [#1118],
    [#1119],
    [#1120],
    [#1121],
    [#1122],
    [#1123],
    [#1124],
    [#1125],
    [#1126],
    [#1127],
    [#1128],
    [#1129],
    [#1130],
    [#1131],
    [#1132],
    [#1133],
    [#1134],
    [#1135],
    [#1153],
    [#1161],
    [#1182],
    [#1183],
    [#1184],
    [#1185],
    [#1186],
    [#1187],
    [#1191],
    [#1195],
    [#1196],
    [#1197],
    [#1198],
    [#1199],
    [#1202],
    [#1210],
    [#1211],
    [#1228],
    [#1229],
    [#1230],
    [#1231],
    [#1234],
    [#1235],
    [#1241],
    [#1246],
    [#1247],
    [#1248],
    [#1278],
    [#1319],
    [#1320],
    [#1321],
    [#1322],
    [#1324],
    [#1325],
    [#1326],
    [#1327],
    [#1328],
    [#1329],
    [#1368],
    [#1415],
    [#1430],
    [#1432],
    [#1593],
    [#1594],
    [#1595],
    [#1597],
    [#1598],
    [#1599],
    [#1600],
    [#1601],
    [#1603],
    [#1604],
    [#1605],
    [#1606],
    [#1607],
    [#1609],
    [#1610],
    [#1611],
    [#1612],
    [#1613],
    [#1616],
    [#1617],
    [#1618],
    [#1619],
    [#1621],
    [#1622],
    [#1623],
    [#1624],
    [#1625],
    [#1626],
    [#1627],
    [#1628],
    [#1631],
    [#1632],
    [#1637],
    [#1638],
    [#1639],
    [#1640],
    [#1642],
    [#1643],
    [#1644],
    [#1657],
    [#1667],
    [#1672],
    [#1673],
    [#1674],
    [#1676],
    [#1677],
    [#1729],
    [#1732],
    [#1733],
    [#1734],
    [#1735],
    [#1736],
    [#1737],
    [#1738],
    [#1740],
    [#1743],
    [#1748],
    [#1749],
    [#1750],
    [#1751],
    [#1752],
    [#1753],
    [#1754],
    [#1755],
    [#1756],
    [#1757],
    [#1758],
    [#1760],
    [#1761],
    [#1763],
    [#1765],
    [#1766],
    [#1767],
    [#1768],
    [#1769],
    [#1770],
    [#1771],
    [#1772],
    [#1773],
    [#1774],
    [#1775],
    [#1776],
    [#1781],
    [#1782],
    [#1783],
    [#1784],
    [#1785],
    [#1786],
    [#1788],
    [#1789],
    [#1809]

    </details>
- :star: All type declaration related sniffs have received updates to account for new type keywords (like `mixed` and `never`) and new type syntaxes (union, intersection and DNF types) introduced in PHP.
    <details>
    <summary>Associated PRs</summary>

    [#1152],
    [#1217],
    [#1444],
    [#1458],
    [#1466],
    [#1467],
    [#1498],
    [#1714]

    </details>
- :star: A number of Helper classes and traits (for internal use only - see [#1484]).
    <details>
    <summary>Associated PRs</summary>

    [#1237],
    [#1250],
    [#1252],
    [#1261],
    [#1406],
    [#1452],
    [#1493],
    [#1555],
    [#1556],
    [#1557],
    [#1567],
    [#1568],
    [#1570],
    [#1571],
    [#1572],
    [#1575],
    [#1576]

    </details>
- :books: Documentation for the following sniffs:
    * PHPCompatibility.Classes.NewConstVisibility [#1323]
    * PHPCompatibility.FunctionNameRestrictions.RemovedPHP4StyleConstructors [#1292]
    * PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames [#1293]
    * PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue [#1294]
    * PHPCompatibility.ParameterValues.RemovedPCREModifiers [#1295]
    * PHPCompatibility.UseStatements.NewGroupUseDeclarations [#1470]
    * PHPCompatibility.UseStatements.NewUseConstFunction [#1470]
    * PHPCompatibility.Variables.NewUniformVariableSyntax [#1539]
    * Additionally, nearly all newly added sniffs include documentation.

### Changed
- :two_hearts: All sniffs are now cross-version compatibility with both PHP_CodeSniffer 3.x as well as 4.x. [#1911]
- :fire: All PHPCompatibility sniffs are now `final` classes. [#1261], [#1273], [#1875]. Fixes [#608], [#638], [#793], [#1042]
    Sniffs extending other sniffs can cause problems with the autoloading in PHP_CodeSniffer, leading to "class already declared" errors.
- :twisted_rightwards_arrows: The `PHPCompatibility.Classes.ForbiddenAbstractPrivateMethods` sniff has been renamed to `PHPCompatibility.FunctionDeclarations.AbstractPrivateMethods` and now also detects the PHP 8.0 change to allow `abstract private` methods in traits. [#1149]
- :twisted_rightwards_arrows: The `PHPCompatibility.Miscellaneous.ValidIntegers` has been moved to the `Numbers` category and is now called `PHPCompatibility.Numbers.ValidIntegers`. [#1004]
- :twisted_rightwards_arrows: The check for the PHP 7.0 change in how hexadecimal numeric strings are handled, has been removed from the `PHPCompatibility.Miscellaneous.ValidIntegers` sniff and now lives in a dedicated sniff, called `PHPCompatibility.Numbers.RemovedHexadecimalNumericStrings`. [#1004]
- :twisted_rightwards_arrows: `PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations`: the `InvalidTypeHintFound` error code has been split into two error codes. [#1727]. Fixes [#1726]
    * The `InvalidTypeHintFound` error code remains in effect for types which are invalid in a parameter context.
    * The new `InvalidLongTypeFound` error code will now warn about the use of "long" types, which are likely typos, but _could_ be valid class names.
- :twisted_rightwards_arrows: `PHPCompatibility.Classes.NewTypedProperties`: the `InvalidType` error code has been split into two error codes. [#1728]
    * The `InvalidType` error code remains in effect for types which are invalid in a property context.
    * The new `InvalidLongType` error code will now warn about the use of "long" types, which are likely typos, but _could_ be valid class names.
- :green_heart: `PHPCompatibility.Upgrade.LowPHP`: the minimum recommended PHP version is now 7.2.0. [#1550]
- :pushpin: The `testVersion` configuration variable will now also be recognized when provided in lowercase (`testversion`). [#969]
- :pushpin: Passing an invalid `testVersion` will now result in either an `InvalidTestVersion` or an `InvalidTestVersionRange` Exception being thrown. [#1548]
- :pushpin: A lot of the sniffs have had updates to handle various new PHP syntaxes and features.
    :warning: Note: this task is not yet complete, so some sniffs may still throw false positives/negatives when confronted with modern PHP.
    <details>
    <summary>Associated issues and PRs</summary>

    [#986],
    [#988],
    [#990],
    [#991],
    [#995],
    [#996],
    [#997],
    [#1000],
    [#1004],
    [#1239],
    [#1249],
    [#1259],
    [#1261],
    [#1310],
    [#1318],
    [#1331],
    [#1343],
    [#1364],
    [#1369],
    [#1372],
    [#1374],
    [#1375],
    [#1376],
    [#1377],
    [#1378],
    [#1379],
    [#1381],
    [#1382],
    [#1383],
    [#1384],
    [#1385],
    [#1387],
    [#1388],
    [#1389],
    [#1390],
    [#1392],
    [#1393],
    [#1394],
    [#1395],
    [#1396],
    [#1405],
    [#1407],
    [#1422],
    [#1430],
    [#1431],
    [#1432],
    [#1433],
    [#1434],
    [#1439],
    [#1440],
    [#1446],
    [#1447],
    [#1448],
    [#1449],
    [#1474],
    [#1475],
    [#1478],
    [#1494],
    [#1495],
    [#1496],
    [#1498],
    [#1499],
    [#1512],
    [#1515],
    [#1564],
    [#1565],
    [#1566],
    [#1569],
    [#1573],
    [#1588],
    [#1596],
    [#1683],
    [#1688],
    [#1704],
    [#1705],
    [#1706],
    [#1715],
    [#1716],
    [#1717],
    [#1724],
    [#1725],
    [#1746],
    [#1791],
    [#1807],
    [#1823],
    [#1858],
    [#1859],
    [#1860],
    [#1861],
    [#1862],
    [#1867],
    [#1888],
    [#1890],
    [#1891],
    [#1892]

    </details>
- :pushpin: `MiscHelper::isUseOfGlobalConstant()` will no longer recognize constant declarations as "use of". [#1888]
- :pushpin: Both the `PHPCompatibility.Constants.NewConstants` as well as the `PHPCompatibility.Constants.RemovedConstants` sniffs now special case the handling of the `T_BAD_CHARACTER` constant, which was removed from PHP in PHP 7.0, but then re-introduced in PHP 7.4. [#1586]. Fixes [#1351]
- :pushpin: `PHPCompatibility.Classes.RemovedOrphanedParent` the `$classScopeTokens` property is now `private`, it should never have been `public` in the first place. [#983]
- :pushpin: `PHPCompatibility.Classes.RemovedOrphanedParent` will now also flag `parent` when used as a type declaration in an interface. [#1499]
- :pushpin: `PHPCompatibility.ControlStructures.NewExecutionDirectives` now more accurately determines whether the value of a directive is valid. [#1416]
- :pushpin: `PHPCompatibility.FunctionDeclarations.NonStaticMagicMethods` will now also flag incorrect modifiers for the `__wakeup()` method, as enforced by PHP since PHP 8.0. [#1821]
- :pushpin: `PHPCompatibility.FunctionNameRestrictions.RemovedPHP4StyleConstructors` will not report on PHP-4 style constructors if the minimum supported PHP version, as indicated via `testVersion`, is PHP 8.0 or higher, as on PHP 8.0 the concept of PHP-4 style constructors no longer exists in PHP. [#1563]
- :pushpin: `PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames` will now bow out for the PHP 7.4 `__serialize()` and `__unserialize()` magic methods. [#1142]
- :pushpin: `PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames` will now ignore deprecated functions more consistently. [#1564]
- :pushpin: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` will now throw an error, instead of a warning, when a passed parameter is `unset()`. [#1286]
- :pushpin: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` will now recognize and handle numeric `$options` being passed to the target function calls. [#1892]
- :pushpin: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` will now warn against the use of `debug_*backtrace()` as a PHP 8.1+ first class callable. [#1892]
- :pushpin: `PHPCompatibility.Interfaces.InternalInterfaces` will now also flag internal interfaces which cannot be extended when used in an `extends` clause. [#1566]
- :pushpin: `PHPCompatibility.Interfaces.NewInterfaces` will now detect interfaces used in `catch` conditions. [#968]. Fixes [#967]
- :pushpin: `PHPCompatibility.Interfaces.NewInterfaces` will now also detect new interfaces when used in an `extends` clause. [#1259]
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will no longer report on reserved keywords being used in a namespace name as allowed since PHP 8.0 (reporting depends on the `testVersion` passed). [#1402]
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will now strictly only flag forbidden names at the point of declaration. [#1368]
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will now also check for declarations using "other" and soft reserved keywords. [#1399]
    This was previously checked via the `PHPCompatibility.Keywords.ForbiddenNamesAsDeclared` sniff (now removed).
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will not flag keywords, introduced in PHP 7.0 or later, when used as method or OO constant names, as this is not a compatibility issue. [#1633], [#1641]
- :pushpin: `PHPCompatibility.Keywords.ForbiddenNames` will not flag keywords, introduced in PHP 8.0 or later, when used in a namespace name, as this is not a compatibility issue. [#1641]
- :pushpin: `PHPCompatibility.ParameterValues.NewHTMLEntitiesEncodingDefault`: will now also sniff for affected function calls to `get_html_translation_table()`. [#1041]
- :pushpin: `PHPCompatibility.Syntax.NewFunctionCallTrailingComma` will now also detect trailing commas in exit/die as function calls (PHP 8.4+). [#1807]
- :pencil2: Various sniffs which would warn for the use of a deprecated feature have been updated to throw an error for PHP 8.0 having removed these features.
    <details>
    <summary>Associated PRs</summary>

    [#1154],
    [#1155],
    [#1156],
    [#1157],
    [#1158],
    [#1159],
    [#1180],
    [#1281],
    [#1599]

    </details>
- :pencil2: The error messages of various sniffs have been improved.
    In some cases, the line on which the error is thrown may have changed to flag the problematic code with higher precision.
    <details>
    <summary>Associated PRs</summary>

    [#1006],
    [#1009],
    [#1012],
    [#1015],
    [#1142],
    [#1171],
    [#1172],
    [#1173],
    [#1174],
    [#1177],
    [#1178],
    [#1179],
    [#1188],
    [#1210],
    [#1254],
    [#1257],
    [#1258],
    [#1259],
    [#1261],
    [#1368],
    [#1399],
    [#1416],
    [#1448],
    [#1452],
    [#1495],
    [#1514],
    [#1515],
    [#1722],
    [#1810]

    </details>
- :pencil2: The error levels (warning vs error) used in all sniffs have been reviewed and made consistent. [#1282]. Fixes [#1163]
    PHP deprecations should translate to a warning from PHPCompatibility, PHP removals and new features should translate to an error.
    This also impacts some of the error codes as detailed in the [upgrade guide][wiki-upgrade-to-10.0].
- :pencil2: The error codes of various (modular) warnings/errors have changed in light of parameter name renames done in PHP Core to support named arguments in PHP 8.0.
    <details>
    <summary>Associated PRs</summary>

    [#1430],
    [#1431],
    [#1432],
    [#1433]

    </details>
- :pencil2: Various sniffs no longer get confused over comments in unexpected places.
    <details>
    <summary>Associated PRs</summary>

    [#1170],
    [#1188],
    [#1257],
    [#1368],
    [#1516],
    [#1517],
    [#1530],
    [#1893]

    </details>
- :pencil2: Various sniffs now have improved parse error/live coding tolerance.
    <details>
    <summary>Associated PRs</summary>

    [#1009],
    [#1146]

    </details>
- :wrench: Composer: a `replace` directive has been added to discourage use of the old package name. [#976]
- :wrench: Composer: The package will now identify itself as a static analysis tool. [#1346]
- :wrench: :umbrella: Various housekeeping, including general maintenance, improvements to speed up the sniffs, improvements to CI, the tests and documentation.
    <details>
    <summary>Associated issues and PRs</summary>

    [#683],
    [#964],
    [#966],
    [#970],
    [#971],
    [#973],
    [#974],
    [#977],
    [#978],
    [#980],
    [#981],
    [#982],
    [#983],
    [#987],
    [#989],
    [#992],
    [#993],
    [#994],
    [#995],
    [#997],
    [#998],
    [#999],
    [#1000],
    [#1001],
    [#1002],
    [#1003],
    [#1004],
    [#1005],
    [#1007],
    [#1008],
    [#1011],
    [#1014],
    [#1017],
    [#1019],
    [#1021],
    [#1030],
    [#1140],
    [#1141],
    [#1142],
    [#1143],
    [#1144],
    [#1145],
    [#1146],
    [#1147],
    [#1148],
    [#1160],
    [#1167],
    [#1168],
    [#1169],
    [#1171],
    [#1174],
    [#1175],
    [#1176],
    [#1179],
    [#1188],
    [#1189],
    [#1193],
    [#1194],
    [#1205],
    [#1209],
    [#1214],
    [#1215],
    [#1216],
    [#1218],
    [#1219],
    [#1220],
    [#1221],
    [#1222],
    [#1225],
    [#1245],
    [#1253],
    [#1255],
    [#1256],
    [#1258],
    [#1259],
    [#1260],
    [#1261],
    [#1264],
    [#1266],
    [#1268],
    [#1270],
    [#1271],
    [#1273],
    [#1274],
    [#1277],
    [#1280],
    [#1286],
    [#1288],
    [#1289],
    [#1290],
    [#1297],
    [#1302],
    [#1307],
    [#1313],
    [#1314],
    [#1333],
    [#1334],
    [#1335],
    [#1336],
    [#1350],
    [#1354],
    [#1356],
    [#1357],
    [#1359],
    [#1360],
    [#1365],
    [#1368],
    [#1400],
    [#1403],
    [#1408],
    [#1414],
    [#1416],
    [#1421],
    [#1427],
    [#1428],
    [#1435],
    [#1436],
    [#1438],
    [#1439],
    [#1442],
    [#1444],
    [#1445],
    [#1448],
    [#1451],
    [#1455],
    [#1457],
    [#1461],
    [#1462],
    [#1463],
    [#1469],
    [#1476],
    [#1477],
    [#1479],
    [#1482],
    [#1483],
    [#1485],
    [#1493],
    [#1497],
    [#1499],
    [#1500],
    [#1501],
    [#1502],
    [#1503],
    [#1504],
    [#1505],
    [#1506],
    [#1507],
    [#1508],
    [#1509],
    [#1510],
    [#1511],
    [#1512],
    [#1513],
    [#1514],
    [#1515],
    [#1516],
    [#1517],
    [#1518],
    [#1519],
    [#1520],
    [#1521],
    [#1522],
    [#1523],
    [#1524],
    [#1525],
    [#1526],
    [#1527],
    [#1528],
    [#1529],
    [#1530],
    [#1531],
    [#1532],
    [#1533],
    [#1534],
    [#1535],
    [#1536],
    [#1537],
    [#1538],
    [#1539],
    [#1544],
    [#1545],
    [#1546],
    [#1547],
    [#1552],
    [#1553],
    [#1554],
    [#1558],
    [#1559],
    [#1560],
    [#1561],
    [#1562],
    [#1563],
    [#1564],
    [#1565],
    [#1569],
    [#1573],
    [#1574],
    [#1575],
    [#1576],
    [#1577],
    [#1579],
    [#1580],
    [#1582],
    [#1584],
    [#1587],
    [#1588],
    [#1590],
    [#1591],
    [#1619],
    [#1634],
    [#1635],
    [#1636],
    [#1645],
    [#1646],
    [#1647],
    [#1655],
    [#1656],
    [#1658],
    [#1659],
    [#1660],
    [#1661],
    [#1662],
    [#1668],
    [#1684],
    [#1692],
    [#1693],
    [#1701],
    [#1702],
    [#1708],
    [#1709],
    [#1710],
    [#1719],
    [#1723],
    [#1729],
    [#1736],
    [#1737],
    [#1747],
    [#1759],
    [#1762],
    [#1791],
    [#1793],
    [#1794],
    [#1796],
    [#1797],
    [#1798],
    [#1803],
    [#1805],
    [#1806],
    [#1811],
    [#1813],
    [#1816],
    [#1817],
    [#1818],
    [#1819],
    [#1820],
    [#1822],
    [#1824],
    [#1825],
    [#1826],
    [#1827],
    [#1828],
    [#1829],
    [#1830],
    [#1831],
    [#1832],
    [#1833],
    [#1834],
    [#1835],
    [#1836],
    [#1837],
    [#1838],
    [#1839],
    [#1840],
    [#1841],
    [#1842],
    [#1843],
    [#1844],
    [#1845],
    [#1846],
    [#1847],
    [#1848],
    [#1850],
    [#1851],
    [#1853],
    [#1854],
    [#1855],
    [#1856],
    [#1857],
    [#1863],
    [#1864],
    [#1865],
    [#1866],
    [#1868],
    [#1869],
    [#1870],
    [#1871],
    [#1872],
    [#1873],
    [#1874],
    [#1883],
    [#1885],
    [#1887],
    [#1888],
    [#1890],
    [#1892],
    [#1893],
    [#1894],
    [#1895],
    [#1896],
    [#1898],
    [#1899],
    [#1904],
    [#1905],
    [#1907],
    [#1908],
    [#1909],
    [#1910],
    [#1913],
    [#1915],
    [#1916],
    [#1919],
    [#1920],
    [#1921],
    [#1922],
    [#1925],
    [#1927],
    [#1929],
    [#1930],
    [#1931],
    [#1932],
    [#1933]

    </details>

### Removed
- :no_entry_sign: Support for PHP 5.3. [#956]. Fixes [#835]
- :no_entry_sign: Support for PHP_CodeSniffer < 3.13.3. [#956], [#1352], [#1355], [#1662], [#1686], [#1714], [#1806], [#1900]. Fixes [#835], [#1347]
- :no_entry_sign: `PHPCompatibility.Keywords.ForbiddenNamesAsInvokedFunctions` sniff. [#1367]. Fixes [#105], [#1024]
- :no_entry_sign: `PHPCompatibility.Keywords.ForbiddenNamesAsDeclared` sniff. [#1399]. Fixes [#1024]
- :no_entry_sign: `PHPCompatibility.Upgrade.LowPHPCS` sniff. [#1549]
- :no_entry_sign: `PHPCompatibility\PHPCSHelper` class in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\AbstractComplexVersionSniff` class in favour of internal Helper traits. [#1406]
- :no_entry_sign: `PHPCompatibility\AbstractNewFeatureSniff` class in favour of internal Helper traits. [#1406]
- :no_entry_sign: `PHPCompatibility\AbstractRemovedFeatureSniff` class in favour of internal Helper traits. [#1406]
- :no_entry_sign: `PHPCompatibility\ComplexVersionInterface` class in favour of internal Helper traits. [#1406]
- :no_entry_sign: `PHPCompatibility\Sniff::addMessage()` in favour of PHPCSUtils. [#1363]
- :no_entry_sign: `PHPCompatibility\Sniff::arrayKeysToLowercase()`. Use the PHP native `array_change_key_case()` function instead. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::determineNamespace()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::doesFunctionCallHaveParameters()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getCompleteTextString()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getDeclaredNamespaceName()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getFQClassNameFromDoubleColonToken()` in favour of an internal Helper class. [#1571]
- :no_entry_sign: `PHPCompatibility\Sniff::getFQClassNameFromNewToken()` in favour of an internal Helper class. [#1571]
- :no_entry_sign: `PHPCompatibility\Sniff::getFQExtendedClassName()` in favour of an internal Helper class. [#1571]
- :no_entry_sign: `PHPCompatibility\Sniff::getFQName()` in favour of an internal Helper class. [#1571]
- :no_entry_sign: `PHPCompatibility\Sniff::getFunctionCallParameter()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getFunctionCallParameters()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getFunctionCallParameterCount()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::getHashAlgorithmParameter()` in favour of an internal Helper trait. [#1250]
- :no_entry_sign: `PHPCompatibility\Sniff::getReturnTypeHintName()` in favour of PHPCSUtils. [#1448]
- :no_entry_sign: `PHPCompatibility\Sniff::getReturnTypeHintToken()` in favour of PHPCSUtils. [#1448]
- :no_entry_sign: `PHPCompatibility\Sniff::getTypeHintsFromFunctionDeclaration()` in favour of PHPCSUtils. [#1448]
- :no_entry_sign: `PHPCompatibility\Sniff::inClassScope()` in favour of PHPCSUtils. [#1429]
- :no_entry_sign: `PHPCompatibility\Sniff::isClassProperty()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::isClassConstant()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::isNamespaced()` without replacement. [#1551]
- :no_entry_sign: `PHPCompatibility\Sniff::isNegativeNumber()` in favour of an internal Helper class. [#1493], [#1567]
- :no_entry_sign: `PHPCompatibility\Sniff::isNumber()` in favour of an internal Helper class. [#1493], [#1567]
- :no_entry_sign: `PHPCompatibility\Sniff::isNumericCalculation()` in favour of an internal Helper class. [#1568]
- :no_entry_sign: `PHPCompatibility\Sniff::isPositiveNumber()` in favour of an internal Helper class. [#1493], [#1567]
- :no_entry_sign: `PHPCompatibility\Sniff::isShortList()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::isShortTernary()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::isUnaryPlusMinus()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::isUseOfGlobalConstant()` in favour of an internal Helper class. [#1572]
- :no_entry_sign: `PHPCompatibility\Sniff::isVariable()` in favour of an internal Helper class. [#1570]
- :no_entry_sign: `PHPCompatibility\Sniff::stringToErrorCode()` in favour of PHPCSUtils. [#1363]
- :no_entry_sign: `PHPCompatibility\Sniff::stripQuotes()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::stripVariables()` in favour of PHPCSUtils. [#1398]
- :no_entry_sign: `PHPCompatibility\Sniff::supportsAbove()` in favour of an internal Helper class. [#1237], [#1555]
- :no_entry_sign: `PHPCompatibility\Sniff::supportsBelow()` in favour of an internal Helper class. [#1237], [#1555]
- :no_entry_sign: `PHPCompatibility\Sniff::tokenHasScope()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::validDirectScope()` in favour of PHPCSUtils. [#979]
- :no_entry_sign: `PHPCompatibility\Sniff::$iniFunctions` without replacement. [#1251]
- :no_entry_sign: `PHPCompatibility\Sniff::$hashAlgoFunctions` in favour of an internal Helper trait. [#1250]
- :no_entry_sign: `PHPCompatibility\Sniff::$superglobals` in favour of PHPCSUtils. [#1018]
- :no_entry_sign: `PHPCompatibility\Sniff::REGEX_COMPLEX_VARS` in favour of PHPCSUtils. [#1398]

### Fixed
- :bug: Deprecation notice in `testVersion` handling when running on PHP 8.1+. [#1276]
- :bug: Sniffs looking for function calls will now determine more accurately if whatever is being looked at, is actually a (non-namespaced) function call. [#962], related to [#961]
- :bug: Various sniffs explicitly looking for/ignoring `null`/`true`/`false` could get confused over fully qualified and/or non-lowercase use of these keywords. [#1877], [#1902], [#1906]
- :bug: `PHPCompatibility.Classes.NewClasses` will no longer throw false positives for imported namespaced classes with a class name overlapping one of the new global PHP classes. [#1695]. Fixes [#1291]
- :bug: `PHPCompatibility.Classes.NewClasses` and `PHPCompatibility.Classes.RemovedClasses` did not always correctly correlate the class name from `self`. [#1893]
- :bug: `PHPCompatibility.Classes.NewLateStaticBinding` did not flag `instanceof static` or `new static. [#957]
- :bug: `PHPCompatibility.Classes.NewTypedProperties` PHP native build-in types should be handled case-insensitively. [#1205]
- :bug: `PHPCompatibility.Classes.NewTypedProperties` false negative for edge case where a property declaration would be closed via a PHP close tag. [#1703]
- :bug: `PHPCompatibility.Classes.RemovedClasses` will no longer throw false positives for imported namespaced classes with a class name overlapping one of the new global PHP classes. [#1924]
- :bug: `PHPCompatibility.Classes.RemovedOrphanedParent` could get confused over an upstream tokenizer issue when a function declared to return by reference would be called "parent". [#1499]. Fixes [#1489]
- :bug: `PHPCompatibility.Constants.NewConstants` and `PHPCompatibility.Constants.RemovedConstants` will throw fewer false positives for constructs which are not a global constant, but mirror the name of a PHP constant. [#1888]
- :bug: `PHPCompatibility.Constants.NewMagicClassConstant` would incorrectly flag static function calls to a method called `class`. [#1500]
- :bug: `PHPCompatibility.ControlStructures.NewExecutionDirectives`: directive names should be handled case-insensitively. [#1416]
- :bug: `PHPCompatibility.ControlStructures.NewExecutionDirectives`: multi-directive statements were not analyzed correctly. [#1416]
- :bug: `PHPCompatibility.FunctionDeclarations.ForbiddenParametersWithSameName` did not examine abstract methods or interface methods. [#991]
- :bug: `PHPCompatibility.FunctionDeclarations.NewClosure` could get confused over `$this` and `self` being used in nested closed structures in the body of the closure. [#1928]
- :bug: `PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations` did not examine nested function declarations. [#996]
- :bug: `PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations` PHP native build-in types should be handled case-insensitively. [#1203]
- :bug: `PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations` would incorrectly throw the `OutsideClassScopeFound` error for PHP < 5.2. [#1203]
- :bug: `PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations` PHP native build-in types should be handled case-insensitively. [#1204]
- :bug: `PHPCompatibility.FunctionDeclarations.NonStaticMagicMethods` would incorrectly flag non-`public` `__destruct()` methods as having the wrong visibility. [#1543]. Fixes [#1542]
- :bug: `PHPCompatibility.FunctionNameRestrictions.RemovedNamespacedAssert` did not handle nested function declarations correctly. [#1169]
- :bug: `PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames` did not handle nested function declarations correctly. [#1142]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsUsage`: TypeError when run on PHP 8.0+. [#1213]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsUsage` would throw false positives for namespaced functions mirroring a name of a target function of the sniff. [#1890]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsUsage` could get confused over a method declared to return by reference. [#1890]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue`: prevent false positive when passed parameter is used in a return, exit or throw statement. [#1208], [#1286]. Fixes [#1207]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue`: prevent false positive when passed parameter is used in `isset()` or `empty()`. [#1286]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue`: prevent false positive when passed parameter is only assigned to another variable in a "simple assignment". [#1286]. Fixes [#1240]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` would incorrectly ignore function calls when passed a namespaced constant mirroring the global `DEBUG_BACKTRACE_IGNORE_ARGS` constant. [#1892]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` will now check for calls to `array_slice()`/`array_splice()` case-insensitively and will prevent false negatives if a namespaced function is encountered mirroring the global PHP function we're looking for. [#1892]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` now recognizes and handles negative offsets passed to `array_slice()`/`array_splice()` correctly. [#1892]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` now recognizes and handles the $length parameter if passed to `array_slice()`/`array_splice()`. [#1892]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` did not handle (parameter overrides via) list assignments correctly. [#1892]
- :bug: `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` will now more often recognize when a parameter is _touched_, but not _changed_ when the parameter is used in a control structure condition. [#1892]
- :bug: `PHPCompatibility.Interfaces.InternalInterfaces` will no longer throw false positives for imported namespaced classes with a class name overlapping one of the new global PHP classes. [#1700]. Fixes [#1649]
- :bug: `PHPCompatibility.Interfaces.NewInterfaces` did not handle nested function declarations correctly. [#1259]
- :bug: `PHPCompatibility.Interfaces.NewInterfaces` will no longer throw false positives for imported namespaced classes with a class name overlapping one of the new global PHP classes. [#1700].
- :bug: `PHPCompatibility.InitialValue.NewConstantArraysUsingDefine` could get confused over namespaced function calls and class instantiations. [#1518].
- :bug: `PHPCompatibility.InitialValue.NewConstantArraysUsingDefine` could fail to detect array values in a compound parameter. [#1518].
- :bug: `PHPCompatibility.InitialValue.NewConstantArraysUsingDefine` could throw false positives for constants with a closure/arrow function callback as its value. [#1518].
- :bug: `PHPCompatibility.Keywords.ForbiddenNames` did not handle nested function declarations correctly. [#1368]
- :bug: `PHPCompatibility.Keywords.ForbiddenNames` did not always flag keywords used in a namespace name correctly. [#1368]
- :bug: `PHPCompatibility.Keywords.ForbiddenNames` did not always flag aliases in import multi-use, group use and trait use statements correctly. [#1368]
- :bug: `PHPCompatibility.Lists.ForbiddenEmptyListAssignment`: false positive for a hard-coded empty (sub-)array in a foreach iterable expression. [#1401]. Fixes [#1341]
- :bug: `PHPCompatibility.ParameterValues.NewFopenModes` could throw false positives for dynamically generated parameter values. [#1045]
- :bug: `PHPCompatibility.ParameterValues.NewIconvMbstringCharsetDefault` now examines each array item in the `$options` parameter individually to prevent false negatives. [#1588]
- :bug: `PHPCompatibility.ParameterValues.NewIconvMbstringCharsetDefault` would throw false positives for the `$encoding` parameter not being explicitly passed on functions which didn't exist in PHP 5.6 (when the default value of the parameter was changed). [#1779]. Fixes [#1777]
- :bug: `PHPCompatibility.ParameterValues.NewPackFormat` could throw false positives for dynamically generated parameter values. [#1044]. Fixes [#1043]
- :bug: `PHPCompatibility.ParameterValues.NewPasswordAlgoConstantValues` could throw false positives for dynamically generated parameter values. [#1531]
- :bug: `PHPCompatibility.ParameterValues.NewPCREModifiers` and `PHPCompatibility.ParameterValues.RemovedPCREModifiers` could throw false positives for dynamically generated parameter values. [#1764]
- :bug: `PHPCompatibility.ParameterValues.NewProcOpenCmdArray` could fail to warn about the use of `escapeshellarg()` when the PHP 7.4 array format for the `$command` parameter is used, if the function call was not in lowercase. [#1878]
- :bug: `PHPCompatibility.ParameterValues.RemovedIconvEncoding`: TypeError when run on PHP 8.0+. [#1212]
- :bug: `PHPCompatibility.ParameterValues.RemovedMbstringModifiers` could throw false positives for dynamically generated parameter values. [#1046]
- :bug: `PHPCompatibility.ParameterValues.RemovedMbStrrposEncodingThirdParam` could throw both false positives as well as have false negatives for dynamically generated parameter values. [#1722]. Fixes [#1721]
- :bug: `PHPCompatibility.ParameterValues.RemovedNonCryptoHashSniff` did not handle a fully qualified `HASH_HMAC` constant as a parameter value correctly. [#1532]
- :bug: `PHPCompatibility.ParameterValues.RemovedSetlocaleString` could throw false positives for dynamically generated parameter values. [#1047]
- :bug: `PHPCompatibility.Syntax.ForbiddenCallTimePassByReference` was not recognized for anonymous class instantiations and class instantiations using hierarchy keywords. [#1912]
- :bug: `PHPCompatibility.Syntax.NewFlexibleHeredocNowdoc` the `ClosingMarkerNoNewLine` error code was inadvertently used for two different errors. This error code has now been split into `ClosingMarkerNoNewLine` and `CloserFoundInBody`. [#1697]. Fixes [#1696]
- :bug: `PHPCompatibility.Syntax.NewFunctionCallTrailingComma` would throw a false positive for a trailing comma in a function declaration if the function was declared to return by reference. [#1534]
- :bug: `PHPCompatibility.Syntax.NewFunctionCallTrailingComma` false negative on trailing comma in class instantiations. [#1534]
- :bug: `PHPCompatibility.Syntax.NewShortArray` would incorrectly also flag short lists. [#1010]
- :bug: `PHPCompatibility.Syntax.RemovedCurlyBraceArrayAccess` did not flag curly brace array access on a namespaced constant. [#1889]
- :bug: `PHPCompatibility.UseDeclarations.NewUseConstFunction` could throw false positive in some parse error edge cases. [#1537]
- :bug: `PHPCompatibility.Variables.ForbiddenThisUseContexts` false positive for _use_ of `$this` in `unset()`, while `$this` is not the variable being _unset_. [#1670]. Fixes [#1666]
- :bug: `PHPCompatibility.Variables.NewUniformVariableSyntax` did not handle static access using the hierarchy keywords correctly. [#1013]
- :bug: `PHPCompatibility.Variables.RemovedPredefinedGlobalVariables` false positive on static access to class property shadowing the name of one of the removed global variables. [#1014]
- :bug: `PHPCompatibility.Variables.RemovedPredefinedGlobalVariables` did not correctly limit the scope of the token walking to determine whether `$php_errormsg` was the deprecated global variable. [#1014]

### Credits
Thanks go out to [Anna Filina], [bebehr], [Dan Wallis], [Daniel Fahlke], [Diede Exterkate], [Eloy Lafuente], [Gary Jones], [Go Kudo], [Hugo van Kemenade], [Kevin Porras], [Mark Clements], [magikstm], [Matthew Turland], [Sebastian Knott], and [Steve Grunwell] for their contributions to this version. :clap:

Additionally thanks go out to everyone who has been testing the `develop` branch and has reported issues to help us get to where we are now.

[#105]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/105
[#608]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/608
[#638]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/638
[#683]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/683
[#793]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/793
[#835]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/835
[#908]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/908
[#946]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/946
[#956]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/956
[#957]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/957
[#961]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/961
[#962]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/962
[#963]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/963
[#964]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/964
[#966]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/966
[#967]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/967
[#968]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/968
[#969]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/969
[#970]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/970
[#971]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/971
[#973]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/973
[#974]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/974
[#976]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/976
[#977]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/977
[#978]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/978
[#979]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/979
[#980]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/980
[#981]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/981
[#982]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/982
[#983]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/983
[#984]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/984
[#985]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/985
[#986]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/986
[#987]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/987
[#988]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/988
[#989]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/989
[#990]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/990
[#991]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/991
[#992]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/992
[#993]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/993
[#994]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/994
[#995]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/995
[#996]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/996
[#997]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/997
[#998]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/998
[#999]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/999
[#1000]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1000
[#1001]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1001
[#1002]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1002
[#1003]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1003
[#1004]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1004
[#1005]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1005
[#1006]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1006
[#1007]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1007
[#1008]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1008
[#1009]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1009
[#1010]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1010
[#1011]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1011
[#1012]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1012
[#1013]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1013
[#1014]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1014
[#1015]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1015
[#1017]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1017
[#1018]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1018
[#1019]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1019
[#1021]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1021
[#1024]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1024
[#1025]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1025
[#1026]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1026
[#1027]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1027
[#1028]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1028
[#1029]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1029
[#1030]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1030
[#1031]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1031
[#1032]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1032
[#1033]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1033
[#1034]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1034
[#1035]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1035
[#1036]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1036
[#1037]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1037
[#1038]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1038
[#1039]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1039
[#1040]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1040
[#1041]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1041
[#1042]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1042
[#1043]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1043
[#1044]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1044
[#1045]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1045
[#1046]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1046
[#1047]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1047
[#1048]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1048
[#1049]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1049
[#1050]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1050
[#1051]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1051
[#1052]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1052
[#1053]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1053
[#1054]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1054
[#1055]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1055
[#1056]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1056
[#1057]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1057
[#1058]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1058
[#1059]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1059
[#1060]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1060
[#1061]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1061
[#1062]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1062
[#1063]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1063
[#1064]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1064
[#1065]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1065
[#1066]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1066
[#1067]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1067
[#1068]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1068
[#1069]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1069
[#1070]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1070
[#1071]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1071
[#1072]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1072
[#1073]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1073
[#1074]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1074
[#1075]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1075
[#1076]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1076
[#1077]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1077
[#1078]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1078
[#1079]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1079
[#1080]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1080
[#1081]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1081
[#1082]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1082
[#1083]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1083
[#1084]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1084
[#1085]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1085
[#1086]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1086
[#1087]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1087
[#1088]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1088
[#1089]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1089
[#1090]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1090
[#1091]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1091
[#1092]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1092
[#1093]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1093
[#1094]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1094
[#1095]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1095
[#1096]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1096
[#1097]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1097
[#1098]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1098
[#1099]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1099
[#1100]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1100
[#1101]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1101
[#1102]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1102
[#1103]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1103
[#1104]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1104
[#1105]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1105
[#1106]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1106
[#1107]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1107
[#1108]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1108
[#1109]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1109
[#1110]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1110
[#1111]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1111
[#1112]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1112
[#1113]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1113
[#1114]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1114
[#1115]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1115
[#1116]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1116
[#1117]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1117
[#1118]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1118
[#1119]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1119
[#1120]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1120
[#1121]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1121
[#1122]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1122
[#1123]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1123
[#1124]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1124
[#1125]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1125
[#1126]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1126
[#1127]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1127
[#1128]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1128
[#1129]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1129
[#1130]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1130
[#1131]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1131
[#1132]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1132
[#1133]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1133
[#1134]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1134
[#1135]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1135
[#1138]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1138
[#1139]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1139
[#1140]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1140
[#1141]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1141
[#1142]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1142
[#1143]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1143
[#1144]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1144
[#1145]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1145
[#1146]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1146
[#1147]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1147
[#1148]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1148
[#1149]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1149
[#1150]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1150
[#1151]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1151
[#1152]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1152
[#1153]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1153
[#1154]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1154
[#1155]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1155
[#1156]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1156
[#1157]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1157
[#1158]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1158
[#1159]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1159
[#1160]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1160
[#1161]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1161
[#1162]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1162
[#1163]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1163
[#1164]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1164
[#1165]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1165
[#1166]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1166
[#1167]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1167
[#1168]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1168
[#1169]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1169
[#1170]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1170
[#1171]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1171
[#1172]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1172
[#1173]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1173
[#1174]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1174
[#1175]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1175
[#1176]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1176
[#1177]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1177
[#1178]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1178
[#1179]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1179
[#1180]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1180
[#1181]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1181
[#1182]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1182
[#1183]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1183
[#1184]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1184
[#1185]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1185
[#1186]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1186
[#1187]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1187
[#1188]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1188
[#1189]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1189
[#1190]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1190
[#1191]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1191
[#1193]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1193
[#1194]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1194
[#1195]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1195
[#1196]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1196
[#1197]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1197
[#1198]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1198
[#1199]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1199
[#1200]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1200
[#1201]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1201
[#1202]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1202
[#1203]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1203
[#1204]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1204
[#1205]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1205
[#1206]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1206
[#1207]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1207
[#1208]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1208
[#1209]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1209
[#1210]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1210
[#1211]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1211
[#1212]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1212
[#1213]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1213
[#1214]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1214
[#1215]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1215
[#1216]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1216
[#1217]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1217
[#1218]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1218
[#1219]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1219
[#1220]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1220
[#1221]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1221
[#1222]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1222
[#1225]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1225
[#1227]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1227
[#1228]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1228
[#1229]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1229
[#1230]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1230
[#1231]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1231
[#1232]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1232
[#1233]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1233
[#1234]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1234
[#1235]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1235
[#1237]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1237
[#1239]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1239
[#1240]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1240
[#1241]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1241
[#1242]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1242
[#1245]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1245
[#1246]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1246
[#1247]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1247
[#1248]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1248
[#1249]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1249
[#1250]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1250
[#1252]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1252
[#1253]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1253
[#1254]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1254
[#1255]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1255
[#1256]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1256
[#1257]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1257
[#1258]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1258
[#1259]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1259
[#1260]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1260
[#1261]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1261
[#1262]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1262
[#1264]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1264
[#1266]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1266
[#1268]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1268
[#1270]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1270
[#1271]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1271
[#1273]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1273
[#1274]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1274
[#1276]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1276
[#1277]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1277
[#1278]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1278
[#1279]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1279
[#1280]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1280
[#1281]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1281
[#1282]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1282
[#1283]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1283
[#1286]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1286
[#1288]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1288
[#1289]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1289
[#1290]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1290
[#1291]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1291
[#1292]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1292
[#1293]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1293
[#1294]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1294
[#1295]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1295
[#1297]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1297
[#1302]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1302
[#1307]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1307
[#1310]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1310
[#1313]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1313
[#1314]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1314
[#1315]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1315
[#1316]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1316
[#1317]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1317
[#1318]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1318
[#1319]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1319
[#1320]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1320
[#1321]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1321
[#1322]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1322
[#1323]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1323
[#1324]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1324
[#1325]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1325
[#1326]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1326
[#1327]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1327
[#1328]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1328
[#1329]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1329
[#1330]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1330
[#1331]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1331
[#1333]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1333
[#1334]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1334
[#1335]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1335
[#1336]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1336
[#1341]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1341
[#1343]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1343
[#1345]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1345
[#1346]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1346
[#1347]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1347
[#1350]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1350
[#1351]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1351
[#1352]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1352
[#1354]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1354
[#1355]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1355
[#1356]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1356
[#1357]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1357
[#1359]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1359
[#1360]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1360
[#1362]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1362
[#1363]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1363
[#1364]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1364
[#1365]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1365
[#1366]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1366
[#1367]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1367
[#1368]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1368
[#1369]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1369
[#1370]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1370
[#1371]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1371
[#1372]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1372
[#1373]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1373
[#1374]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1374
[#1375]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1375
[#1376]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1376
[#1377]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1377
[#1378]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1378
[#1379]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1379
[#1380]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1380
[#1381]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1381
[#1382]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1382
[#1383]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1383
[#1384]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1384
[#1385]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1385
[#1386]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1386
[#1387]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1387
[#1388]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1388
[#1389]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1389
[#1390]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1390
[#1391]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1391
[#1392]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1392
[#1393]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1393
[#1394]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1394
[#1395]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1395
[#1396]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1396
[#1398]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1398
[#1399]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1399
[#1400]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1400
[#1401]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1401
[#1402]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1402
[#1403]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1403
[#1405]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1405
[#1406]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1406
[#1407]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1407
[#1408]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1408
[#1414]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1414
[#1415]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1415
[#1416]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1416
[#1417]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1417
[#1418]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1418
[#1419]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1419
[#1420]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1420
[#1421]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1421
[#1422]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1422
[#1423]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1423
[#1424]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1424
[#1425]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1425
[#1426]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1426
[#1427]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1427
[#1428]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1428
[#1429]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1429
[#1430]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1430
[#1431]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1431
[#1432]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1432
[#1433]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1433
[#1434]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1434
[#1435]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1435
[#1436]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1436
[#1438]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1438
[#1439]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1439
[#1440]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1440
[#1442]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1442
[#1443]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1443
[#1444]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1444
[#1445]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1445
[#1446]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1446
[#1447]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1447
[#1448]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1448
[#1449]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1449
[#1451]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1451
[#1452]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1452
[#1453]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1453
[#1455]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1455
[#1457]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1457
[#1458]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1458
[#1461]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1461
[#1462]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1462
[#1463]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1463
[#1464]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1464
[#1465]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1465
[#1466]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1466
[#1467]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1467
[#1469]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1469
[#1470]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1470
[#1474]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1474
[#1475]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1475
[#1476]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1476
[#1477]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1477
[#1478]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1478
[#1479]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1479
[#1480]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1480
[#1482]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1482
[#1483]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1483
[#1484]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1484
[#1485]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1485
[#1486]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1486
[#1487]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1487
[#1488]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1488
[#1489]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1489
[#1493]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1493
[#1494]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1494
[#1495]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1495
[#1496]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1496
[#1497]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1497
[#1498]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1498
[#1499]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1499
[#1500]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1500
[#1501]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1501
[#1502]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1502
[#1503]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1503
[#1504]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1504
[#1505]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1505
[#1506]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1506
[#1507]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1507
[#1508]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1508
[#1509]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1509
[#1510]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1510
[#1511]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1511
[#1512]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1512
[#1513]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1513
[#1514]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1514
[#1515]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1515
[#1516]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1516
[#1517]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1517
[#1518]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1518
[#1519]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1519
[#1520]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1520
[#1521]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1521
[#1522]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1522
[#1523]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1523
[#1524]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1524
[#1525]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1525
[#1526]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1526
[#1527]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1527
[#1528]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1528
[#1529]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1529
[#1530]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1530
[#1531]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1531
[#1532]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1532
[#1533]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1533
[#1534]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1534
[#1535]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1535
[#1536]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1536
[#1537]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1537
[#1538]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1538
[#1539]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1539
[#1542]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1542
[#1543]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1543
[#1544]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1544
[#1545]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1545
[#1546]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1546
[#1547]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1547
[#1548]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1548
[#1549]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1549
[#1550]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1550
[#1551]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1551
[#1552]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1552
[#1553]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1553
[#1554]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1554
[#1555]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1555
[#1556]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1556
[#1557]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1557
[#1558]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1558
[#1559]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1559
[#1560]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1560
[#1561]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1561
[#1562]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1562
[#1563]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1563
[#1564]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1564
[#1565]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1565
[#1566]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1566
[#1567]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1567
[#1568]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1568
[#1569]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1569
[#1570]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1570
[#1571]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1571
[#1572]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1572
[#1573]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1573
[#1574]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1574
[#1575]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1575
[#1576]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1576
[#1577]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1577
[#1579]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1579
[#1580]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1580
[#1582]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1582
[#1584]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1584
[#1586]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1586
[#1587]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1587
[#1588]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1588
[#1590]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1590
[#1591]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1591
[#1592]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1592
[#1593]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1593
[#1594]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1594
[#1595]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1595
[#1596]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1596
[#1597]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1597
[#1598]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1598
[#1599]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1599
[#1600]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1600
[#1601]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1601
[#1602]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1602
[#1603]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1603
[#1604]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1604
[#1605]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1605
[#1606]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1606
[#1607]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1607
[#1609]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1609
[#1610]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1610
[#1611]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1611
[#1612]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1612
[#1613]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1613
[#1614]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1614
[#1615]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1615
[#1616]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1616
[#1617]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1617
[#1618]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1618
[#1619]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1619
[#1620]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1620
[#1621]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1621
[#1622]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1622
[#1623]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1623
[#1624]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1624
[#1625]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1625
[#1626]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1626
[#1627]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1627
[#1628]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1628
[#1629]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1629
[#1631]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1631
[#1632]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1632
[#1633]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1633
[#1634]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1634
[#1635]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1635
[#1636]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1636
[#1637]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1637
[#1638]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1638
[#1639]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1639
[#1640]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1640
[#1641]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1641
[#1642]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1642
[#1643]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1643
[#1644]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1644
[#1645]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1645
[#1646]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1646
[#1647]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1647
[#1649]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1649
[#1655]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1655
[#1656]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1656
[#1657]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1657
[#1658]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1658
[#1659]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1659
[#1660]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1660
[#1661]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1661
[#1662]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1662
[#1666]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1666
[#1667]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1667
[#1668]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1668
[#1670]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1670
[#1671]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1671
[#1672]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1672
[#1673]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1673
[#1674]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1674
[#1676]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1676
[#1677]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1677
[#1679]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1679
[#1680]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1680
[#1683]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1683
[#1684]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1684
[#1686]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1686
[#1688]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1688
[#1689]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1689
[#1692]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1692
[#1693]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1693
[#1694]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1694
[#1695]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1695
[#1696]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1696
[#1697]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1697
[#1699]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1699
[#1700]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1700
[#1701]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1701
[#1702]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1702
[#1703]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1703
[#1704]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1704
[#1705]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1705
[#1706]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1706
[#1708]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1708
[#1709]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1709
[#1710]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1710
[#1714]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1714
[#1715]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1715
[#1716]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1716
[#1717]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1717
[#1719]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1719
[#1721]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1721
[#1722]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1722
[#1723]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1723
[#1724]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1724
[#1725]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1725
[#1726]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1726
[#1727]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1727
[#1728]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1728
[#1729]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1729
[#1730]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1730
[#1732]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1732
[#1733]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1733
[#1734]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1734
[#1735]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1735
[#1736]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1736
[#1737]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1737
[#1738]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1738
[#1739]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1739
[#1740]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1740
[#1741]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1741
[#1742]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1742
[#1743]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1743
[#1744]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1744
[#1745]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1745
[#1746]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1746
[#1747]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1747
[#1748]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1748
[#1749]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1749
[#1750]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1750
[#1751]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1751
[#1752]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1752
[#1753]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1753
[#1754]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1754
[#1755]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1755
[#1756]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1756
[#1757]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1757
[#1758]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1758
[#1759]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1759
[#1760]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1760
[#1761]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1761
[#1762]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1762
[#1763]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1763
[#1764]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1764
[#1765]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1765
[#1766]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1766
[#1767]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1767
[#1768]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1768
[#1769]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1769
[#1770]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1770
[#1771]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1771
[#1772]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1772
[#1773]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1773
[#1774]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1774
[#1775]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1775
[#1776]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1776
[#1777]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1777
[#1779]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1779
[#1781]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1781
[#1782]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1782
[#1783]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1783
[#1784]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1784
[#1785]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1785
[#1786]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1786
[#1787]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1787
[#1788]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1788
[#1789]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1789
[#1791]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1791
[#1792]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1792
[#1793]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1793
[#1794]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1794
[#1796]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1796
[#1797]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1797
[#1798]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1798
[#1803]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1803
[#1805]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1805
[#1806]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1806
[#1807]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1807
[#1808]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1808
[#1809]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1809
[#1810]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1810
[#1811]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1811
[#1812]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1812
[#1813]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1813
[#1814]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1814
[#1815]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1815
[#1816]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1816
[#1817]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1817
[#1818]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1818
[#1819]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1819
[#1820]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1820
[#1821]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1821
[#1822]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1822
[#1823]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1823
[#1824]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1824
[#1825]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1825
[#1826]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1826
[#1827]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1827
[#1828]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1828
[#1829]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1829
[#1830]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1830
[#1831]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1831
[#1832]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1832
[#1833]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1833
[#1834]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1834
[#1835]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1835
[#1836]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1836
[#1837]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1837
[#1838]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1838
[#1839]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1839
[#1840]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1840
[#1841]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1841
[#1842]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1842
[#1843]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1843
[#1844]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1844
[#1845]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1845
[#1846]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1846
[#1847]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1847
[#1848]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1848
[#1850]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1850
[#1851]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1851
[#1853]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1853
[#1854]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1854
[#1855]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1855
[#1856]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1856
[#1857]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1857
[#1858]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1858
[#1859]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1859
[#1860]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1860
[#1861]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1861
[#1862]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1862
[#1863]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1863
[#1864]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1864
[#1865]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1865
[#1866]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1866
[#1867]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1867
[#1868]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1868
[#1869]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1869
[#1870]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1870
[#1871]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1871
[#1872]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1872
[#1873]: https://github.com/PHPCompatibility/PHPCompatibility/issues/1873
[#1874]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1874
[#1875]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1875
[#1876]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1876
[#1877]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1877
[#1878]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1878
[#1879]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1879
[#1880]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1880
[#1881]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1881
[#1882]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1882
[#1883]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1883
[#1885]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1885
[#1886]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1886
[#1887]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1887
[#1888]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1888
[#1889]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1889
[#1890]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1890
[#1891]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1891
[#1892]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1892
[#1893]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1893
[#1894]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1894
[#1895]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1895
[#1896]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1896
[#1897]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1897
[#1898]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1898
[#1899]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1899
[#1900]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1900
[#1901]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1901
[#1902]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1902
[#1903]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1903
[#1904]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1904
[#1905]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1905
[#1906]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1906
[#1907]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1907
[#1908]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1908
[#1909]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1909
[#1910]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1910
[#1911]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1911
[#1912]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1912
[#1913]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1913
[#1915]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1915
[#1916]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1916
[#1919]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1919
[#1920]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1920
[#1921]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1921
[#1922]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1922
[#1923]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1923
[#1924]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1924
[#1925]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1925
[#1926]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1926
[#1927]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1927
[#1928]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1928
[#1929]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1929
[#1930]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1930
[#1931]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1931
[#1932]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1932
[#1933]: https://github.com/PHPCompatibility/PHPCompatibility/pull/1933


## [9.3.5] - 2019-12-27

See all related issues and PRs in the [9.3.5 milestone].

### Added
- :star: `PHPCompatibility.Classes.NewClasses` sniff: recognize the new `FFI` extension related classes as introduced in PHP 7.4. [#949]
- :star: `PHPCompatibility.IniDirectives.NewIniDirectives` sniff: detect use of the new `FFI` extension related ini directives as introduced in PHP 7.4. [#949]

### Changed
- :pencil2: `PHPCompatibility.Syntax.NewShortArray`: improved clarity of the error message and made it consistent with other error messages in this standard. [#934]
- :pencil2: `PHPCompatibility.Interfaces.NewInterfaces`: updated the URL which is mentioned in select error messages. [#942]
- :recycle: Another slew of code documentation fixes. [#937], [#939], [#940], [#941], [#943], [#944], [#951], [#950]. Fixes [#734].
- :green_heart: Travis: various tweaks. The builds against PHP 7.4 are no longer allowed to fail. [#935], [#938]
    For running the sniffs on PHP 7.4, it is recommended to use PHP_CodeSniffer 3.5.0+ as PHP_CodeSniffer itself is
    not compatible with PHP 7.4 until version 3.5.0.

### Fixed
- :bug: `PHPCompatibility.Classes.NewClasses`: two new PHP 7.4 classes were being checked as if they were Exceptions. [#945]

### Credits
Thanks go out to [William Entriken] for their contribution to this version. :clap:

[#734]: https://github.com/PHPCompatibility/PHPCompatibility/issues/734
[#934]: https://github.com/PHPCompatibility/PHPCompatibility/pull/934
[#935]: https://github.com/PHPCompatibility/PHPCompatibility/pull/935
[#937]: https://github.com/PHPCompatibility/PHPCompatibility/pull/937
[#938]: https://github.com/PHPCompatibility/PHPCompatibility/pull/938
[#939]: https://github.com/PHPCompatibility/PHPCompatibility/pull/939
[#940]: https://github.com/PHPCompatibility/PHPCompatibility/pull/940
[#941]: https://github.com/PHPCompatibility/PHPCompatibility/pull/941
[#942]: https://github.com/PHPCompatibility/PHPCompatibility/pull/942
[#943]: https://github.com/PHPCompatibility/PHPCompatibility/pull/943
[#944]: https://github.com/PHPCompatibility/PHPCompatibility/pull/944
[#945]: https://github.com/PHPCompatibility/PHPCompatibility/pull/945
[#949]: https://github.com/PHPCompatibility/PHPCompatibility/pull/949
[#950]: https://github.com/PHPCompatibility/PHPCompatibility/pull/950
[#951]: https://github.com/PHPCompatibility/PHPCompatibility/pull/951


## [9.3.4] - 2019-11-15

See all related issues and PRs in the [9.3.4 milestone].

### Fixed
- :bug: `PHPCompatibility.Keywords.ForbiddenNames`: false positive for list when used in a `foreach()` condition. [#930]. Fixes [#928], [#929]

### Credits
Thanks go out to [Sergii Bondarenko] for their contribution to this version. :clap:

[#928]: https://github.com/PHPCompatibility/PHPCompatibility/issues/928
[#929]: https://github.com/PHPCompatibility/PHPCompatibility/pull/929
[#930]: https://github.com/PHPCompatibility/PHPCompatibility/pull/930


## [9.3.3] - 2019-11-11

See all related issues and PRs in the [9.3.3 milestone].

### Added
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of yet more (undocumented) PHP 7.2 Sodium constants. [#924]
- :star: `PHPCompatibility.Keywords.ForbiddenNames` sniff: detect the use of more reserved keywords which are not allowed to be used to name certain constructs. [#923]. Fixes [#922]

### Fixed
- :bug: `PHPCompatibility.FunctionNameRestrictions.RemovedPHP4StyleConstructors`: false positive detecting PHP4-style constructors when declared in interfaces. The class implementing the interface will not have the same name as the interface, so the actual method would not be regarded as a PHP4 style constructor. [#921]

### Credits
Thanks go out to [Nikhil] for their contribution to this version. :clap:

[#921]: https://github.com/PHPCompatibility/PHPCompatibility/pull/921
[#922]: https://github.com/PHPCompatibility/PHPCompatibility/issues/922
[#923]: https://github.com/PHPCompatibility/PHPCompatibility/pull/923
[#924]: https://github.com/PHPCompatibility/PHPCompatibility/pull/924


## [9.3.2] - 2019-10-16

See all related issues and PRs in the [9.3.2 milestone].

### Added
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of the PHP 7.2 `SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13` constant. [#915]
- :books: Readme: a list of projects which are build upon or extend PHPCompatibility. [#904]

### Changed
- :pushpin: `PHPCompatibility.FunctionNameRestrictions.RemovedPHP4StyleConstructors`: minor efficiency fix to make the sniff faster. [#912]
- :pushpin: `PHPCompatibility.FunctionNameRestrictions.ReservedFunctionNames`: functions marked as `@deprecated` in the function docblock will now be ignored by this sniff. [#917]. Fixes [#911]
- :pencil: `PHPCompatibility.FunctionDeclarations.ForbiddenToStringParameters`: the `$ooScopeTokens` property is now `protected`, it should never have been `public` in the first place. [#907]
- :recycle: More code documentation fixes. [#903], [#916]
- :books: Readme/Contributing: various tweaks. [#904], [#905]

### Fixed
- :bug: `PHPCompatibility.FunctionUse.OptionalToRequiredFunctionParameters`: false positive when a class is instantiated which has the same name as one of the affected functions. [#914]. Fixes [#913]
- :bug: `PHPCompatibility.FunctionUse.RequiredToOptionalFunctionParameters`: false positive when a class is instantiated which has the same name as one of the affected functions. [#914]
- :bug: `PHPCompatibility.MethodUse.NewDirectCallsToClone`: false positive on calling `__clone()` from within the class being cloned [#910]. Fixes [#629 (comment)][#629-comment]
- :bug: `PHPCompatibility.Miscellaneous.ValidIntegers`: binary numbers using an uppercase `B` were not always recognized correctly. [#909]

[#629-comment]: https://github.com/PHPCompatibility/PHPCompatibility/issues/629#issuecomment-532607809
[#903]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/903
[#904]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/904
[#905]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/905
[#907]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/907
[#909]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/909
[#910]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/910
[#911]:         https://github.com/PHPCompatibility/PHPCompatibility/issues/911
[#912]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/912
[#913]:         https://github.com/PHPCompatibility/PHPCompatibility/issues/913
[#914]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/914
[#915]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/915
[#916]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/916
[#917]:         https://github.com/PHPCompatibility/PHPCompatibility/pull/917


## [9.3.1] - 2019-09-06

See all related issues and PRs in the [9.3.1 milestone].

### Changed
- :recycle: A whole slew of code documentation fixes. [#892], [#895], [#896], [#897], [#898], [#899], [#900]
- :wrench: Travis: minor tweaks to the build script. [#893]

### Fixed
- :bug: `PHPCompatibility.ParameterValues.RemovedImplodeFlexibleParamOrder`: false positive when an array item in the second parameter contained a ternary. [#891]. Fixes [#890]
- :bug: `PHPCompatibility.ParameterValues.RemovedImplodeFlexibleParamOrder`: will now take array casts into account when determining which parameter is `$pieces`. [#891].
- :bug: `PHPCompatibility.ParameterValues.RemovedImplodeFlexibleParamOrder`: hardening of the logic to not examine the second parameter when the first is just and only a text string (`$glue`). [#891].

[#890]: https://github.com/PHPCompatibility/PHPCompatibility/issues/890
[#891]: https://github.com/PHPCompatibility/PHPCompatibility/pull/891
[#892]: https://github.com/PHPCompatibility/PHPCompatibility/pull/892
[#893]: https://github.com/PHPCompatibility/PHPCompatibility/pull/893
[#895]: https://github.com/PHPCompatibility/PHPCompatibility/pull/895
[#896]: https://github.com/PHPCompatibility/PHPCompatibility/pull/896
[#897]: https://github.com/PHPCompatibility/PHPCompatibility/pull/897
[#898]: https://github.com/PHPCompatibility/PHPCompatibility/pull/898
[#899]: https://github.com/PHPCompatibility/PHPCompatibility/pull/899
[#900]: https://github.com/PHPCompatibility/PHPCompatibility/pull/900


## [9.3.0] - 2019-08-29

See all related issues and PRs in the [9.3.0 milestone].

To keep informed of the progress of covering "_everything PHP 7.4_" in PHPCompatibility, please subscribe to issue [#808].

### Changes expected in PHPCompatibility 10.0.0
The next version of PHPCompatibility is expected to include a new external dependency.

In this same release, support for PHP < 5.4 and PHP_CodeSniffer < 2.6.0 will be dropped.

The `10.0.0` release is expected around the same time as the release of PHP 7.4 - end of November/beginning of December 2019.

### Added
- :star2: New `PHPCompatibility.Miscellaneous.NewPHPOpenTagEOF` sniff to detect a stand-alone PHP open tag at the end of a file, without trailing newline, as will be supported as of PHP 7.4. [#843]
- :star2: New `PHPCompatibility.ParameterValues.ForbiddenStripTagsSelfClosingXHTML` sniff to detect calls to `strip_tags()` passing self-closing XHTML tags in the `$allowable_tags` parameter. This has not been supported since PHP 5.3.4. [#866]
- :star2: New `PHPCompatibility.ParameterValues.NewHTMLEntitiesEncodingDefault` sniff to detect calls to `html_entity_decode()`, `htmlentities()` and `htmlspecialchars()` which are impacted by the change to the default value of the `$encoding` parameter in PHP 5.4. [#862]
- :star2: New `PHPCompatibility.ParameterValues.NewIconvMbstringCharsetDefault` sniff to detect code impacted by the change in the `default_charset` value in PHP 5.6. [#864] Fixes [#839]
- :star2: New `PHPCompatibility.ParameterValues.NewIDNVariantDefault` sniff to detect calls to `idn_to_ascii()` and `idn_to_utf8()` impacted by the PHP 7.4 change in the default value for the `$variant` parameter. [#861]
- :star2: New `PHPCompatibility.ParameterValues.NewPasswordAlgoConstantValues` sniff to detect calls to `password_hash()` and `password_needs_rehash()` impacted by the changed value of the `PASSWORD_DEFAULT`, `PASSWORD_BCRYPT`, `PASSWORD_ARGON2I` and `PASSWORD_ARGON2ID` constants in PHP 7.4. [#865]
- :star2: New `PHPCompatibility.ParameterValues.NewProcOpenCmdArray` sniff to detect calls to `proc_open()` passing an array for the `$cmd` parameter as supported as of PHP 7.4. [#869]
- :star2: New `PHPCompatibility.ParameterValues.NewStripTagsAllowableTagsArray` sniff to detect calls to `strip_tags()` passing an array for the `$allowable_tags` parameter as will be supported as of PHP 7.4. [#867]
- :star2: New `PHPCompatibility.ParameterValues.RemovedImplodeFlexibleParamOrder` sniff to detect `implode()` being called with `$glue` and `$pieces` in reverse order from the documented argument order. This was previously allowed for historical reasons, but will be deprecated in PHP 7.4. [#846]
- :star2: New `PHPCompatibility.ParameterValues.RemovedMbStrrposEncodingThirdParam` sniff to detect the `$encoding` being passed as the third, instead of the fourth parameter, to `mb_strrpos()` as has been soft deprecated since PHP 5.2 and will be hard deprecated as of PHP 7.4. [#860]
- :star2: New `PHPCompatibility.Syntax.RemovedCurlyBraceArrayAccess` sniff to detect array and string offset access using curly braces as will be deprecated as of PHP 7.4. [#855]
    * In contrast to any other sniff in the PHPCompatibility standard, this sniff contains an auto-fixer.
- :star2: New `PHPCompatibility.TextStrings.NewUnicodeEscapeSequence` sniff to detect use of the PHP 7.0+ unicode codepoint escape sequences and issues with invalid sequences. [#856]
- :star2: New `PHPCompatibility.Upgrade.LowPHP` sniff to give users of old PHP versions advance warning when support will be dropped in the near future. [#838]
    At this moment, the intention is to drop support for PHP 5.3 by the end of this year.
- :star: `PHPCompatibility.Classes.NewClasses` sniff: recognize the new `WeakReference` class as introduced in PHP 7.4. [#857]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of new Curl constants as introduced in PHP 7.3.5. [#878]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of the revived `T_BAD_CHARACTER` constant as re-introduced in PHP 7.4. [#882]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of the new `IMG_FILTER_SCATTER` and `PASSWORD_ARGON2_PROVIDER` constants as introduced in PHP 7.4. [#887]
- :star: `PHPCompatibility.Constants.RemovedConstants` sniff: detection of use of the `CURLPIPE_HTTP1` constant which will be deprecated in PHP 7.4. [#879]
- :star: `PHPCompatibility.Constants.RemovedConstants` sniff: detection of use of the `FILTER_SANITIZE_MAGIC_QUOTES` constant which will be deprecated in PHP 7.4. [#845]
- :star: `PHPCompatibility.Constants.RemovedConstants` sniff: detection of use of the `T_CHARACTER` and `T_BAD_CHARACTER` constants which were removed in PHP 7.0. [#882]
- :star: `PHPCompatibility.FunctionDeclarations.NewMagicMethods` sniff: recognize the new `__serialize()` and `__unserialize()` magic methods as introduced in PHP 7.4. [#868]
- :star: `PHPCompatibility.FunctionDeclarations.NewMagicMethods` sniff: recognize the PHP 5.0 `__construct()` and `__destruct()` magic methods. [#884]
- :star: `PHPCompatibility.FunctionDeclarations.NonStaticMagicMethods` sniff: recognize the new `__serialize()` and `__unserialize()` magic methods as introduced in PHP 7.4. [#868]
- :star: `PHPCompatibility.FunctionUse.NewFunctions` sniff: recognize the new PHP 7.4 function `imagecreatefromtga()`. [#873]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctionParameters` sniff: recognize the deprecation of the `$age` parameter of the `curl_version()` function. [#874]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctions` sniff: recognize the PHP 7.4 deprecated `convert_cyr_string()()`, `ezmlm_hash()`, `get_magic_quotes_gpc()`, `get_magic_quotes_runtime()`, `hebrevc()`, `is_real()`, `money_format()` and `restore_include_path()` functions. [#847]
- :star: `PHPCompatibility.IniDirectives.NewIniDirectives` sniff: detect use of the new PHP 7.4 `zend.exception_ignore_args` ini directive. [#871]
- :star: `PHPCompatibility.IniDirectives.RemovedIniDirectives` sniff: detect use of the `allow_url_include` ini directive which is deprecated as of PHP 7.4. [#870]
- :star: `PHPCompatibility.IniDirectives.RemovedIniDirectives` sniff: detection of use of the `opcache.load_comments` directive which was removed in PHP 7.0. [#883]
- :star: `PHPCompatibility.ParameterValues.NewHashAlgorithms`: recognize use of the new PHP 7.4 `crc32c` hash algorithm. [#872]
- :star: `PHPCompatibility.TypeCasts.RemovedTypeCasts` sniff: detect usage of the `(real)` type cast which will be deprecated in PHP 7.4. [#844]
- :star: Recognize the `recode` extension functionality which will be removed in PHP 7.4 (moved to PECL) in the `RemovedExtensions` and `RemovedFunctions` sniffs. [#841]
- :star: Recognize the `OPcache` extension functionality which was be introduced in PHP 5.5, but not yet fully accounted for in the `NewFunctions` and `NewIniDirectives` sniffs.  [#883]
- :star: New `getCompleteTextString()` utility method to the `PHPCompatibility\Sniff` class. [#856]
- :umbrella: Unit test for the `PHPCompatibility.Upgrade.LowPHPCS` sniff.
- :umbrella: Some extra unit tests for the `PHPCompatibility.ParameterValues.NewNegativeStringOffset`, `PHPCompatibility.ParameterValues.RemovedMbStringModifiers` and sniffs. [#876], [#877]
- :books: `CONTRIBUTING.md`: Added a list of typical sources for information about changes to PHP. [#875]

### Changed
- :pushpin: `PHPCompatibility.FunctionDeclarations.NewExceptionsFromToString` sniff: the sniff will now also examine the function docblock, if available, and will throw an error when a `@throws` tag is found in the docblock. [#880]. Fixes [#863]
- :pushpin: `PHPCompatibility.FunctionDeclarations.NonStaticMagicMethods` sniff: will now also check the visibility and `static` (or not) requirements of the magic `__construct()`, `__destruct()`, `__clone()`, `__debugInfo()`, `__invoke()` and `__set_state()` methods. [#885]
- :pushpin: `PHPCompatibility.Syntax.NewArrayStringDereferencing` sniff: the sniff will now also recognize array string dereferencing using curly braces as was (silently) supported since PHP 7.0. [#851]
    * The sniff will now also throw errors for each dereference found on the array/string, not just the first one.
- :pushpin: `PHPCompatibility.Syntax.NewClassMemberAccess` sniff: the sniff will now also recognize class member access on instantiation and cloning using curly braces as was (silently) supported since PHP 7.0. [#852]
    * The sniff will now also throw errors for each access detected, not just the first one.
    * The line number on which the error is thrown in now set more precisely.
- :pushpin: `PHPCompatibility.Syntax.NewFunctionArrayDereferencing` sniff: the sniff will now also recognize function array dereferencing using curly braces as was (silently) supported since PHP 7.0. [#853]
    * The sniff will now also throw errors for each access detected, not just the first one.
    * The line number on which the error is thrown in now set more precisely.
- :recycle: Various code clean-up and improvements. [#849], [#850]
- :recycle: Various minor inline documentation fixes. [#854], [#886]
- :wrench: Travis: various tweaks to the build script. [#834], [#842]

### Fixed
- :bug: `PHPCompatibility.FunctionDeclarations.ForbiddenParametersWithSameName` sniff: variable names are case-sensitive, so recognition of same named parameters should be done in a case-sensitive manner. [#848]
- :bug: `PHPCompatibility.FunctionDeclarations.NewExceptionsFromToString` sniff: Exceptions thrown within a `try` should not trigger the sniff. [#880]. Fixes [#863]
- :bug: `PHPCompatibility.FunctionDeclarations.NewExceptionsFromToString` sniff: the `$ooScopeTokens` property should never have been a public property. [#880].
- :umbrella: Some of the unit tests for the `PHPCompatibility.Operators.RemovedTernaryAssociativity` sniff were not being run. [#836]

[#834]: https://github.com/PHPCompatibility/PHPCompatibility/pull/834
[#836]: https://github.com/PHPCompatibility/PHPCompatibility/pull/836
[#838]: https://github.com/PHPCompatibility/PHPCompatibility/pull/838
[#839]: https://github.com/PHPCompatibility/PHPCompatibility/issues/839
[#841]: https://github.com/PHPCompatibility/PHPCompatibility/pull/841
[#842]: https://github.com/PHPCompatibility/PHPCompatibility/pull/842
[#843]: https://github.com/PHPCompatibility/PHPCompatibility/pull/843
[#844]: https://github.com/PHPCompatibility/PHPCompatibility/pull/844
[#845]: https://github.com/PHPCompatibility/PHPCompatibility/pull/845
[#846]: https://github.com/PHPCompatibility/PHPCompatibility/pull/846
[#847]: https://github.com/PHPCompatibility/PHPCompatibility/pull/847
[#848]: https://github.com/PHPCompatibility/PHPCompatibility/pull/848
[#849]: https://github.com/PHPCompatibility/PHPCompatibility/pull/849
[#850]: https://github.com/PHPCompatibility/PHPCompatibility/pull/850
[#851]: https://github.com/PHPCompatibility/PHPCompatibility/pull/851
[#852]: https://github.com/PHPCompatibility/PHPCompatibility/pull/852
[#853]: https://github.com/PHPCompatibility/PHPCompatibility/pull/853
[#854]: https://github.com/PHPCompatibility/PHPCompatibility/pull/854
[#855]: https://github.com/PHPCompatibility/PHPCompatibility/pull/855
[#856]: https://github.com/PHPCompatibility/PHPCompatibility/pull/856
[#857]: https://github.com/PHPCompatibility/PHPCompatibility/pull/857
[#860]: https://github.com/PHPCompatibility/PHPCompatibility/pull/860
[#861]: https://github.com/PHPCompatibility/PHPCompatibility/pull/861
[#862]: https://github.com/PHPCompatibility/PHPCompatibility/pull/862
[#863]: https://github.com/PHPCompatibility/PHPCompatibility/issues/863
[#864]: https://github.com/PHPCompatibility/PHPCompatibility/pull/864
[#865]: https://github.com/PHPCompatibility/PHPCompatibility/pull/865
[#866]: https://github.com/PHPCompatibility/PHPCompatibility/pull/866
[#867]: https://github.com/PHPCompatibility/PHPCompatibility/pull/867
[#868]: https://github.com/PHPCompatibility/PHPCompatibility/pull/868
[#869]: https://github.com/PHPCompatibility/PHPCompatibility/pull/869
[#870]: https://github.com/PHPCompatibility/PHPCompatibility/pull/870
[#871]: https://github.com/PHPCompatibility/PHPCompatibility/pull/871
[#872]: https://github.com/PHPCompatibility/PHPCompatibility/pull/872
[#873]: https://github.com/PHPCompatibility/PHPCompatibility/pull/873
[#874]: https://github.com/PHPCompatibility/PHPCompatibility/pull/874
[#875]: https://github.com/PHPCompatibility/PHPCompatibility/pull/875
[#876]: https://github.com/PHPCompatibility/PHPCompatibility/pull/876
[#877]: https://github.com/PHPCompatibility/PHPCompatibility/pull/877
[#878]: https://github.com/PHPCompatibility/PHPCompatibility/pull/878
[#879]: https://github.com/PHPCompatibility/PHPCompatibility/pull/879
[#880]: https://github.com/PHPCompatibility/PHPCompatibility/pull/880
[#882]: https://github.com/PHPCompatibility/PHPCompatibility/pull/882
[#883]: https://github.com/PHPCompatibility/PHPCompatibility/pull/883
[#884]: https://github.com/PHPCompatibility/PHPCompatibility/pull/884
[#885]: https://github.com/PHPCompatibility/PHPCompatibility/pull/885
[#886]: https://github.com/PHPCompatibility/PHPCompatibility/pull/886
[#887]: https://github.com/PHPCompatibility/PHPCompatibility/pull/887


## [9.2.0] - 2019-06-28

See all related issues and PRs in the [9.2.0 milestone].

To keep informed of the progress of covering "_everything PHP 7.4_" in PHPCompatibility, please subscribe to issue [#808].

### Added
- :star2: New `PHPCompatibility.Classes.ForbiddenAbstractPrivateMethods` sniff to detect methods declared as both `private` as well as `abstract`. This was allowed between PHP 5.0.0 and 5.0.4, but disallowed in PHP 5.1 as the behaviour of `private` and `abstract` are mutually exclusive. [#822]
- :star2: New `PHPCompatibility.Classes.NewTypedProperties` sniff to detect PHP 7.4 typed property declarations. [#801], [#829]
- :star2: New `PHPCompatibility.Classes.RemovedOrphanedParent` sniff to detect the use of the `parent` keyword in classes without a parent (non-extended classes). This code pattern is deprecated in PHP 7.4 and will become a compile-error in PHP 8.0. [#818]
- :star2: New `PHPCompatibility.FunctionDeclarations.NewExceptionsFromToString` sniff to detect throwing exceptions from `__toString()` methods. This would previously result in a fatal error, but will be allowed as of PHP 7.4. [#814]
- :star2: New `PHPCompatibility.FunctionDeclarations.ForbiddenToStringParameters` sniff to detect `__toString()` function declarations expecting parameters. This was disallowed in PHP 5.3. [#815]
- :star2: New `PHPCompatibility.MethodUse.ForbiddenToStringParameters` sniff to detect direct calls to `__toString()` magic methods passing parameters. This was disallowed in PHP 5.3. [#830]
- :star2: New `PHPCompatibility.Operators.ChangedConcatOperatorPrecedence` sniff to detect code affected by the upcoming change in operator precedence for the concatenation operator. The concatenation operator precedence will be lowered in PHP 8.0, with deprecation notices for code affected being thrown in PHP 7.4. [#805]
- :star2: New `PHPCompatibility.Operators.RemovedTernaryAssociativity` sniff to detect code relying on left-associativity of the ternary operator. This behaviour will be deprecated in PHP 7.4 and removed in PHP 8.0. [#810]
- :star2: New `PHPCompatibility.Syntax.NewArrayUnpacking` sniff to detect the use of the spread operator to unpack arrays when declaring a new array, as introduced in PHP 7.4. [#804]
- :star: `PHPCompatibility.Classes.NewClasses` sniff: recognize the new `ReflectionReference` class as introduced in PHP 7.4. [#820]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: detection of the new PHP 7.4 Core (Standard), MBString, Socket and Tidy constants. [#821]
- :star: `PHPCompatibility.FunctionUse.NewFunctions` sniff: detect usage of the new PHP 7.4 `get_mangled_object_vars()`, `mb_str_split()`, `openssl_x509_verify()`, `password_algos()`, `pcntl_unshare()`, `sapi_windows_set_ctrl_handler()` and `sapi_windows_generate_ctrl_event()` functions. [#811], [#819], [#827]
- :star: `PHPCompatibility.FunctionUse.NewFunctions` sniff: recognize the new OCI functions as introduced in PHP 7.2.14 and PHP 7.3.1. [#786]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctions` sniff: recognize the PHP 7.4 deprecated `ldap_control_paged_result_response()` and `ldap_control_paged_result()` functions. [#831]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctions` sniff: recognize the `Payflow Pro/pfpro` functions as removed in PHP 5.1. [#823]
- :star: `PHPCompatibility.FunctionUse.RequiredToOptionalFunctionParameters` sniff: account for the parameters for `array_merge()` and `array_merge_recursive()` becoming optional in PHP 7.4. [#817]
- :star: `PHPCompatibility.IniDirectives.RemovedIniDirectives` sniff: recognize the `Payflow Pro/pfpro` ini directives as removed in PHP 5.1. [#823]
- :star: Recognize the `interbase/Firebird` extension functionality which will be removed in PHP 7.4 (moved to PECL) in the `RemovedConstants`, `RemovedExtensions`, `RemovedFunctions` and `RemovedIniDirectives` sniffs. [#807]
- :star: Recognize the `wddx` extension functionality which will be removed in PHP 7.4 (moved to PECL) in the `RemovedExtensions` and `RemovedFunctions` sniffs. [#826]
- :star: New `isShortTernary()` and `isUnaryPlusMinus()` utility methods to the `PHPCompatibility\Sniff` class. [#810], [#805]

### Changed
- :pencil2: The `PHPCompatibility.Extensions.RemovedExtensions` sniff will now only report on the removed `Payflow Pro` extension when a function uses `pfpro_` as a prefix. Previously, it used the `pfpro` prefix (without underscore) for detection. [#812]
- :pencil2: The error message thrown when the `T_ELLIPSIS` token, i.e. the spread operator, is detected. [#803]
    PHP 7.4 adds a third use-case for the spread operator. The adjusted error message accounts for this.
- :umbrella: `PHPCompatibility.FunctionDeclarations.NewParamTypeDeclarations` is now also tested with parameters using the splat operator. [#802]
- :books: The documentation now uses the GitHub repo of `PHP_CodeSniffer` as the canonical entry point for `PHP_CodeSniffer`. Previously, it would point to the PEAR package. [#788]
- :books: The links in the changelog now all point to the `PHPCompatibility/PHPCompatibility` repo and no longer to the (deprecated) `wimg/PHPCompatibility` repo. [#828]
- :recycle: Various minor inline documentation improvements. [#825]
- :wrench: Various performance optimizations and code simplifications. [#783], [#784], [#795], [#813]
- :green_heart: Travis: build tests are now being run against PHP 7.4 (unstable) as well. [#790]
    Note: the builds are currently not (yet) tested against PHP 8.0 (unstable) as there is no compatible PHPUnit version available (yet).
- :wrench: Travis: The build script has been refactored to use [stages][travis-stages] to get the most relevant results faster. Additionally some more tweaks have been made to improve and/or simplify the build script. [#798]
- :wrench: Build/PHPCS: warnings are no longer allowed for the PHPCompatibility native code. [#800]
- :wrench: Build/PHPCS: added variable assignment alignment check and file include check to the PHPCompatibility native CS configuration. [#824]
- :wrench: The minimum version for the recommended `DealerDirect/phpcodesniffer-composer-installer` Composer plugin has been upped to `0.5.0`. [#791]

### Fixed
- :bug: The `PHPCompatibility.Extensions.RemovedExtensions` sniff contained a typo in the alternative recommended for the removed `mcve` extension. [#806]
- :bug: The `PHPCompatibility.Extensions.RemovedExtensions` sniff listed the wrong removal version number for the `Payflow Pro/pfpro` extension (PHP 5.3 instead of the correct 5.1). [#823]

### Credits
Thanks go out to [Yılmaz] and [Tim Millwood] for their contribution to this version. :clap:

[travis-stages]: https://docs.travis-ci.com/user/build-stages/

[#783]: https://github.com/PHPCompatibility/PHPCompatibility/pull/783
[#784]: https://github.com/PHPCompatibility/PHPCompatibility/pull/784
[#786]: https://github.com/PHPCompatibility/PHPCompatibility/pull/786
[#788]: https://github.com/PHPCompatibility/PHPCompatibility/pull/788
[#790]: https://github.com/PHPCompatibility/PHPCompatibility/pull/790
[#791]: https://github.com/PHPCompatibility/PHPCompatibility/pull/791
[#795]: https://github.com/PHPCompatibility/PHPCompatibility/pull/795
[#798]: https://github.com/PHPCompatibility/PHPCompatibility/pull/798
[#800]: https://github.com/PHPCompatibility/PHPCompatibility/pull/800
[#801]: https://github.com/PHPCompatibility/PHPCompatibility/pull/801
[#802]: https://github.com/PHPCompatibility/PHPCompatibility/pull/802
[#803]: https://github.com/PHPCompatibility/PHPCompatibility/pull/803
[#804]: https://github.com/PHPCompatibility/PHPCompatibility/pull/804
[#805]: https://github.com/PHPCompatibility/PHPCompatibility/pull/805
[#806]: https://github.com/PHPCompatibility/PHPCompatibility/pull/806
[#807]: https://github.com/PHPCompatibility/PHPCompatibility/pull/807
[#808]: https://github.com/PHPCompatibility/PHPCompatibility/issues/808
[#810]: https://github.com/PHPCompatibility/PHPCompatibility/pull/810
[#811]: https://github.com/PHPCompatibility/PHPCompatibility/pull/811
[#812]: https://github.com/PHPCompatibility/PHPCompatibility/pull/812
[#813]: https://github.com/PHPCompatibility/PHPCompatibility/pull/813
[#814]: https://github.com/PHPCompatibility/PHPCompatibility/pull/814
[#815]: https://github.com/PHPCompatibility/PHPCompatibility/pull/815
[#817]: https://github.com/PHPCompatibility/PHPCompatibility/pull/817
[#818]: https://github.com/PHPCompatibility/PHPCompatibility/pull/818
[#819]: https://github.com/PHPCompatibility/PHPCompatibility/pull/819
[#820]: https://github.com/PHPCompatibility/PHPCompatibility/pull/820
[#821]: https://github.com/PHPCompatibility/PHPCompatibility/pull/821
[#822]: https://github.com/PHPCompatibility/PHPCompatibility/pull/822
[#823]: https://github.com/PHPCompatibility/PHPCompatibility/pull/823
[#824]: https://github.com/PHPCompatibility/PHPCompatibility/pull/824
[#825]: https://github.com/PHPCompatibility/PHPCompatibility/pull/825
[#826]: https://github.com/PHPCompatibility/PHPCompatibility/pull/826
[#827]: https://github.com/PHPCompatibility/PHPCompatibility/pull/827
[#828]: https://github.com/PHPCompatibility/PHPCompatibility/pull/828
[#829]: https://github.com/PHPCompatibility/PHPCompatibility/pull/829
[#830]: https://github.com/PHPCompatibility/PHPCompatibility/pull/830
[#831]: https://github.com/PHPCompatibility/PHPCompatibility/pull/831


## [9.1.1] - 2018-12-31

See all related issues and PRs in the [9.1.1 milestone].

### Fixed
- :bug: `ForbiddenThisUseContexts`: false positive for unsetting `$this['key']` on objects implementing `ArrayAccess`. [#781]. Fixes [#780]

[#780]: https://github.com/PHPCompatibility/PHPCompatibility/issues/780
[#781]: https://github.com/PHPCompatibility/PHPCompatibility/pull/781


## [9.1.0] - 2018-12-16

See all related issues and PRs in the [9.1.0 milestone].

### Added
- :star2: New `PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue` sniff to detect code which could be affected by the PHP 7.0 change in the values reported by `func_get_arg()`, `func_get_args()`, `debug_backtrace()` and exception backtraces. [#750]. Fixes [#585].
- :star2: New `PHPCompatibility.MethodUse.NewDirectCallsToClone` sniff to detect direct call to a `__clone()` magic method which wasn't allowed prior to PHP 7.0. [#743]. Fixes [#629].
- :star2: New `PHPCompatibility.Variables.ForbiddenThisUseContext` sniff to detect most of the inconsistencies surrounding the use of the `$this` variable, which were removed in PHP 7.1. [#762], [#771]. Fixes [#262] and [#740].
- :star: `NewClasses`: detection of more native PHP Exceptions. [#743], [#753]
- :star: `NewConstants` : detection of the new PHP 7.3 Curl, Stream Crypto and LDAP constants and some more PHP 7.0 Tokenizer constants. [#752], [#767], [#778]
- :star: `NewFunctions` sniff: recognize (more) new LDAP functions as introduced in PHP 7.3. [#768]
- :star: `NewFunctionParameters` sniff: recognize the new `$serverctrls` parameter which was added to a number of LDAP functions in PHP 7.3. [#769]
- :star: `NewIniDirectives` sniff: recognize the new `imap.enable_insecure_rsh` ini directive as introduced in PHP 7.1.25, 7.2.13 and 7.3.0. [#770]
- :star: `NewInterfaces` sniff: recognize two more Session related interfaces which were introduced in PHP 5.5.1 and 7.0 respectively. [#748]
- :star: Duplicate of upstream `findStartOfStatement()` method to the `PHPCompatibility\PHPCSHelper` class to allow for PHPCS cross-version usage of that method. [#750]

### Changed
- :pushpin: `RemovedPHP4StyleConstructors`: will now also detect PHP4-style constructors when declared in interfaces. [#751]
- :pushpin: `Sniff::validDirectScope()`: the return value of this method has changed. Previously it would always be a boolean. It will stil return `false` when no valid direct scope has been found, but it will now return the `stackPtr` to the scope token if a _valid_ direct scope was encountered. [#758]
- :rewind: `NewOperators` : updated the version number for `T_COALESCE_EQUAL`. [#746]
- :pencil2: Minor improvement to an error message in the unit test suite. [#742]
- :recycle: Various code clean-up and improvements. [#745], [#756], [#774]
- :recycle: Various minor inline documentation fixes. [#749], [#757]
- :umbrella: Improved code coverage recording. [#744], [#776]
- :green_heart: Travis: build tests are now being run against PHP 7.3 as well. [#764]
    Note: full PHP 7.3 support is only available in combination with PHP_CodeSniffer 2.9.2 or 3.3.1+ due to an incompatibility within PHP_CodeSniffer itself.

### Fixed
- :white_check_mark: Compatibility with the upcoming release of PHPCS 3.4.0. Deal with changed behaviour of the PHPCS `Tokenizer` regarding binary type casts. [#760]
- :bug: `InternalInterfaces`: false negative for implemented/extended interfaces prefixed with a namespace separator. [#775]
- :bug: `NewClasses`: the introduction version of various native PHP Exceptions has been corrected. [#743], [#753]
- :bug: `NewInterfaces`: false negative for implemented/extended interfaces prefixed with a namespace separator. [#775]
- :bug: `RemovedPHP4StyleConstructors`: the sniff would examine methods in nested anonymous classes as if they were methods of the higher level class. [#751]
- :rewind: `RemovedPHP4StyleConstructors`: the sniff will no longer throw false positives for the first method in an anonymous class when used in combination with PHPCS 2.3.x. [#751]
- :rewind: `ReservedFunctionNames`: fixed incorrect error message text for methods in anonymous classes when used in combination with PHPCS 2.3.x. [#755]
- :bug: `ReservedFunctionNames`: prevent duplicate errors being thrown for methods in nested anonymous classes. [#755]
- :bug: `PHPCSHelper::findEndOfStatement()`: minor bug fix. [#749]
- :bug: `Sniff::isClassProperty()`: class properties for classes nested in conditions or function calls were not always recognized as class properties. [#758]

### Credits
Thanks go out to [Jonathan Champ] for his contribution to this version. :clap:

[#262]: https://github.com/PHPCompatibility/PHPCompatibility/issues/262
[#585]: https://github.com/PHPCompatibility/PHPCompatibility/pull/585
[#629]: https://github.com/PHPCompatibility/PHPCompatibility/issues/629
[#740]: https://github.com/PHPCompatibility/PHPCompatibility/issues/740
[#742]: https://github.com/PHPCompatibility/PHPCompatibility/pull/742
[#743]: https://github.com/PHPCompatibility/PHPCompatibility/pull/743
[#744]: https://github.com/PHPCompatibility/PHPCompatibility/pull/744
[#745]: https://github.com/PHPCompatibility/PHPCompatibility/pull/745
[#746]: https://github.com/PHPCompatibility/PHPCompatibility/pull/746
[#748]: https://github.com/PHPCompatibility/PHPCompatibility/pull/748
[#749]: https://github.com/PHPCompatibility/PHPCompatibility/pull/749
[#750]: https://github.com/PHPCompatibility/PHPCompatibility/pull/750
[#751]: https://github.com/PHPCompatibility/PHPCompatibility/pull/751
[#752]: https://github.com/PHPCompatibility/PHPCompatibility/pull/752
[#753]: https://github.com/PHPCompatibility/PHPCompatibility/pull/753
[#755]: https://github.com/PHPCompatibility/PHPCompatibility/pull/755
[#756]: https://github.com/PHPCompatibility/PHPCompatibility/pull/756
[#757]: https://github.com/PHPCompatibility/PHPCompatibility/pull/757
[#758]: https://github.com/PHPCompatibility/PHPCompatibility/pull/758
[#760]: https://github.com/PHPCompatibility/PHPCompatibility/pull/760
[#762]: https://github.com/PHPCompatibility/PHPCompatibility/pull/762
[#764]: https://github.com/PHPCompatibility/PHPCompatibility/pull/764
[#767]: https://github.com/PHPCompatibility/PHPCompatibility/pull/767
[#768]: https://github.com/PHPCompatibility/PHPCompatibility/pull/768
[#769]: https://github.com/PHPCompatibility/PHPCompatibility/pull/769
[#770]: https://github.com/PHPCompatibility/PHPCompatibility/pull/770
[#771]: https://github.com/PHPCompatibility/PHPCompatibility/pull/771
[#774]: https://github.com/PHPCompatibility/PHPCompatibility/pull/774
[#775]: https://github.com/PHPCompatibility/PHPCompatibility/pull/775
[#776]: https://github.com/PHPCompatibility/PHPCompatibility/pull/776
[#778]: https://github.com/PHPCompatibility/PHPCompatibility/pull/778


## [9.0.0] - 2018-10-07

**IMPORTANT**: This release contains **breaking changes**. Please read and follow the [Upgrade guide in the wiki][wiki-upgrade-to-9.0] carefully before upgrading!

All sniffs have been placed in meaningful categories and a number of sniffs have been renamed to have more consistent, meaningful and future-proof names.

Both the `PHPCompatibilityJoomla` [[GH][gh-phpcompat-joomla] | [Packagist][packagist-phpcompat-joomla]] as well as the `PHPCompatibilityWP` [[GH][gh-phpcompat-wp] | [Packagist][packagist-phpcompat-wp]] rulesets have already been adjusted for this change and have released a new version which is compatible with this version of PHPCompatibility.

Aside from those CMS-based rulesets, this project now also offers a number of polyfill-library specific rulesets, such as `PHPCompatibilityPasswordCompat` [[GH][gh-phpcompat-passwordcompat] | [Packagist][packagist-phpcompat-passwordcompat]] for @ircmaxell's [`password_compat`][polyfills-password_compat] libary, `PHPCompatibilityParagonieRandomCompat` and `PHPCompatibilityParagonieSodiumCompat` [[GH][gh-phpcompat-paragonie] | [Packagist][packagist-phpcompat-paragonie]] for the [Paragonie polyfills][polyfills-paragonie] and a number of rulesets related to various [polyfills offered by the Symfony project][polyfills-symfony] [[GH][gh-phpcompat-symfony] | [Packagist][packagist-phpcompat-symfony]].

If your project uses one of these polyfills, please consider using these special polyfill rulesets to prevent false positives.

Also as of this version, [Juliette Reinders Folmer] is now officially a co-maintainer of this package.

[wiki-upgrade-to-9.0]: https://github.com/PHPCompatibility/PHPCompatibility/wiki/Upgrading-to-PHPCompatibility-9.0


### Changelog for version 9.0.0

See all related issues and PRs in the [9.0.0 milestone].

### Added
- :star2: New `PHPCompatibility.ControlStructures.NewForeachExpressionReferencing` sniff to detect referencing of `$value` within a `foreach()` when the iterated array is not a variable. This was not supported prior to PHP 5.5. [#664]
- :star2: New `PHPCompatibility.ControlStructures.NewListInForeach` sniff to detect unpacking nested arrays into separate variables via the `list()` construct in a `foreach()` statement. This was not supported prior to PHP 5.5. [#657]
- :star2: New `PHPCompatibility.FunctionNameRestrictions.RemovedNamespacedAssert` sniff to detect declaring a function called `assert()` within a namespace. This has been deprecated as of PHP 7.3. [#735]. Partially fixes [#718].
- :star2: New `PHPCompatibility.Lists.AssignmentOrder` sniff to detect `list()` constructs affected by the change in assignment order in PHP 7.0. [#656]
- :star2: New `PHPCompatibility.Lists.NewKeyedList` sniff to detect usage of keys in `list()`, support for which was added in PHP 7.1. [#655]. Fixes [#252].
- :star2: New `PHPCompatibility.Lists.NewListReferenceAssignment` sniff to detect reference assignments being used in `list()` constructs, support for which has been added in PHP 7.3. [#731]
- :star2: New `PHPCompatibility.Lists.NewShortList` sniff to detect the shorthand array syntax `[]` being used for symmetric array destructuring as introduced in PHP 7.1. [#654]. Fixes [#248].
- :star2: New `PHPCompatibility.Operators.NewOperators` sniff which checks for usage of the pow, pow equals, spaceship and coalesce (equals) operators. [#738]
    These checks were previously contained within the `PHPCompatibility.LanguageConstructs.NewLanguageConstructs` sniff.
- :star2: New `PHPCompatibility.ParameterValues.ForbiddenGetClassNull` sniff to detect `null` being passed to `get_class()`, support for which has been removed in PHP 7.2 [#659]. Fixes [#557].
- :star2: New `PHPCompatibility.ParameterValues.NewArrayReduceInitialType` sniff to detect non-integers being passed as the `$initial` parameter to the `array_reduce()` function, which was not supported before PHP 5.3. [#666]. Fixes [#649]
- :star2: New `PHPCompatibility.ParameterValues.NewFopenModes` sniff to examine the `$mode` parameter passed to `fopen()` for modes not available in older PHP versions. [#658]
- :star2: New `PHPCompatibility.ParameterValues.NewNegativeStringOffset` sniff to detect negative string offsets being passed to string manipulation functions which was not supported before PHP 7.1. [#662]. Partially fixes [#253].
- :star2: New `PHPCompatibility.ParameterValues.NewPackFormats` sniff to examine the `$format` parameter passed to `pack()` for formats not available in older PHP versions. [#665]
- :star2: New `PHPCompatibility.ParameterValues.RemovedIconvEncoding` sniff to detect the PHP 5.6 deprecated encoding `$type`s being passed to `iconv_set_encoding()`. [#660]. Fixes [#475].
- :star2: New `PHPCompatibility.ParameterValues.RemovedNonCryptoHashes` sniff to detect non-cryptographic hash algorithms being passed to various `hash_*()` functions. This is no longer accepted as of PHP 7.2. [#663]. Fixes [#559]
- :star2: New `PHPCompatibility.ParameterValues.RemovedSetlocaleString` sniff to detect string literals being passed to the `$category` parameter of the `setlocale()` function. This behaviour was deprecated in PHP 4.2 and support has been removed in PHP 7.0. [#661]
- :star2: New `PHPCompatibility.Syntax.NewFlexibleHeredocNowdoc` sniff to detect the new heredoc/nowdoc format as allowed as of PHP 7.3. [#736]. Fixes [#705].
    Note: This sniff is only supported in combination with PHP_CodeSniffer 2.6.0 and higher.
- :star: `PHPCompatibility.Classes.NewClasses` sniff: recognize the new `CompileError` and `JsonException` classes as introduced in PHP 7.3. [#676]
- :star: `PHPCompatibility.Constants.NewConstants` sniff: recognize new constants which are being introduced in PHP 7.3. [#678]
- :star: `PHPCompatibility.Constants.RemovedConstants` sniff: recognize constants which have been deprecated or removed in PHP 7.3. [#710]. Partially fixes [#718].
- :star: `PHPCompatibility.FunctionUse.NewFunctions` sniff: recognize various new functions being introduced in PHP 7.3. [#679]
- :star: `PHPCompatibility.FunctionUse.NewFunctions` sniff: recognize the `sapi_windows_*()`, `hash_hkdf()` and `pcntl_signal_get_handler()` functions as introduced in PHP 7.1. [#728]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctionParameters` sniff: recognize the deprecation of the `$case_insensitive` parameter for the `define()` function in PHP 7.3. [#706]
- :star: `PHPCompatibility.FunctionUse.RemovedFunctions` sniff: recognize the PHP 7.3 deprecation of the `image2wbmp()`, `fgetss()` and `gzgetss()` functions, as well as the deprecation of undocumented Mbstring function aliases. [#681], [#714], [#720]. Partially fixes [#718].
- :star: `PHPCompatibility.FunctionUse.RequiredToOptionalFunctionParameters` sniff: account for the second parameter for `array_push()` and `array_unshift()` becoming optional in PHP 7.3, as well as for the `$mode` parameter for a range of `ftp_*()` functions becoming optional. [#680]
- :star: `PHPCompatibility.IniDirectives.NewIniDirectives` sniff: recognize new `syslog` and `session` ini directives as introduced in PHP 7.3. [#702], [#719], [#730]
- :star: `PHPCompatibility.IniDirectives.NewIniDirectives` sniff: recognize some more ini directives which were introduced in PHP 7.1. [#727]
- :star: `PHPCompatibility.IniDirectives.RemovedIniDirectived` sniff: recognize ini directives removed in PHP 7.3. [#677], [#717]. Partially fixes [#718].
- :star: New `isNumericCalculation()` and `isVariable()` utility methods to the `PHPCompatibility\Sniff` class. [#664], [#666]
- :books: A section about the new sniff naming conventions to the `Contributing` file. [#738]

### Changed
- :fire: All sniffs have been placed in meaningful categories and a number of sniffs have been renamed to have more consistent, meaningful and future-proof names. [#738]. Fixes [#601], [#692]
    See the table at the top of this changelog for details of all the file renames.
- :umbrella: The unit test files have been moved about as well. [#738]
    * The directory structure for these now mirrors the default directory structure used by PHPCS itself.
    * The file names of the unit test files have been adjusted for the changes made in the sniffs.
    * The unit test case files have been renamed and moved to the same directory as the actual test file they apply to.
    * The `BaseSniffTest::sniffFile()` method has been adjusted to match. The signature of this method has changed. Where it previously expected a relative path to the unit test case file, it now expects an absolute path.
    * The unit tests for the utility methods in the `PHPCompatibility\Sniff` class have been moved to a new `PHPCompatibility\Util\Tests\Core` subdirectory.
    * The bootstrap file used for PHPUnit has been moved to the project root directory and renamed `phpunit-bootstrap.php`.
- :twisted_rightwards_arrows: The `PHPCompatibility.LanguageConstructs.NewLanguageConstructs` sniff has been split into two sniffs. [#738]
    The `PHPCompatibility.LanguageConstructs.NewLanguageConstructs` sniff now contains just the checks for the namespace separator and the ellipsis.
    The new `PHPCompatibility.Operators.NewOperators` sniff now contains the checks regarding the pow, pow equals, spaceship and coalesce (equals) operators.
- :pushpin: The `PHPCompatibility.ParameterValues.RemovedMbstringModifiers` sniff will now also recognize removed regex modifiers when used within a function call to one of the undocumented Mbstring function aliases for the Mbstring regex functions. [#715]
- :pushpin: The `PHPCompatibility\Sniff::getFunctionCallParameter()` utility method now allows for closures called via a variable. [#723]
- :pencil2: `PHPCompatibility.Upgrade.LowPHPCS`: the minimum supported PHPCS version is now 2.3.0. [#699]
- :pencil2: Minor inline documentation improvements. [#738]
- :umbrella: Minor improvements to the unit tests for the `PHPCompatibility.FunctionNameRestrctions.RemovedMagicAutoload` sniff. [#716]
- :recycle: Minor other optimizations. [#698], [#697]
- :wrench: Minor improvements to the build tools. [#701]
- :wrench: Removed some unnecessary inline annotations. [#700]
- :books: Replaced some of the badges in the Readme file. [#721], [#722]
- :books: Composer: updated the list of package authors. [#739]

### Removed
- :no_entry_sign: Support for PHP_CodeSniffer 1.x and low 2.x versions. The new minimum version of PHP_CodeSniffer to be able to use this library is 2.3.0. [#699]. Fixes [#691].
    The minimum _recommended_ version of PHP_CodeSniffer remains the same, i.e. 2.6.0.
- :no_entry_sign: The `\PHPCompatibility\Sniff::inUseScope()` method has been removed as it is no longer needed now support for PHPCS 1.x has been dropped. [#699]
- :no_entry_sign: Composer: The `autoload` section has been removed from the `composer.json` file. [#738]. Fixes [#568].
    Autoloading for this library is done via the PHP_CodeSniffer default mechanism, enhanced with our own autoloader, so the Composer autoloader shouldn't be needed and was causing issues in a particular use-case.

### Fixed
- :bug: `PHPCompatibility.FunctionUse.NewFunctionParameters` sniff: The new `$mode` parameter of the `php_uname()` function was added in PHP 4.3, not in PHP 7.0 as was previously being reported.
    The previous implementation of this check was based on an error in the PHP documentation. The error in the PHP documentation has been rectified and the sniff has followed suit. [#711]
- :bug: `PHPCompatibility.Generators.NewGeneratorReturn` sniff: The sniff would throw false positives for `return` statements in nested constructs and did not correctly detect the scope which should be examined. [#725]. Fixes [#724].
- :bug: `PHPCompatibility.Keywords.NewKeywords` sniff: PHP magic constants are case _in_sensitive. This sniff now accounts for this. [#707]
- :bug: Various bugs in the `PHPCompatibility.Syntax.ForbiddenCallTimePassByReference` sniff [#723]:
    * Closures called via a variable will now also be examined. (false negative)
    * References within arrays/closures passed as function call parameters would incorrectly trigger an error. (false positive)
- :green_heart: Compatibility with PHPUnit 7.2. [#712]

### Credits
Thanks go out to [Jonathan Champ] for his contribution to this version. :clap:

[polyfills-password_compat]: https://github.com/ircmaxell/password_compat
[polyfills-paragonie]:       https://github.com/paragonie?utf8=?&q=polyfill
[polyfills-symfony]:         https://github.com/symfony?utf8=?&q=polyfill

[#248]: https://github.com/PHPCompatibility/PHPCompatibility/issues/248
[#252]: https://github.com/PHPCompatibility/PHPCompatibility/issues/252
[#253]: https://github.com/PHPCompatibility/PHPCompatibility/issues/253
[#475]: https://github.com/PHPCompatibility/PHPCompatibility/issues/475
[#557]: https://github.com/PHPCompatibility/PHPCompatibility/issues/557
[#559]: https://github.com/PHPCompatibility/PHPCompatibility/issues/559
[#568]: https://github.com/PHPCompatibility/PHPCompatibility/issues/568
[#601]: https://github.com/PHPCompatibility/PHPCompatibility/issues/601
[#649]: https://github.com/PHPCompatibility/PHPCompatibility/issues/649
[#654]: https://github.com/PHPCompatibility/PHPCompatibility/pull/654
[#655]: https://github.com/PHPCompatibility/PHPCompatibility/pull/655
[#656]: https://github.com/PHPCompatibility/PHPCompatibility/pull/656
[#657]: https://github.com/PHPCompatibility/PHPCompatibility/pull/657
[#658]: https://github.com/PHPCompatibility/PHPCompatibility/pull/658
[#659]: https://github.com/PHPCompatibility/PHPCompatibility/pull/659
[#660]: https://github.com/PHPCompatibility/PHPCompatibility/pull/660
[#661]: https://github.com/PHPCompatibility/PHPCompatibility/pull/661
[#662]: https://github.com/PHPCompatibility/PHPCompatibility/pull/662
[#663]: https://github.com/PHPCompatibility/PHPCompatibility/pull/663
[#664]: https://github.com/PHPCompatibility/PHPCompatibility/pull/664
[#665]: https://github.com/PHPCompatibility/PHPCompatibility/pull/665
[#666]: https://github.com/PHPCompatibility/PHPCompatibility/pull/666
[#676]: https://github.com/PHPCompatibility/PHPCompatibility/pull/676
[#677]: https://github.com/PHPCompatibility/PHPCompatibility/pull/677
[#678]: https://github.com/PHPCompatibility/PHPCompatibility/pull/678
[#679]: https://github.com/PHPCompatibility/PHPCompatibility/pull/679
[#680]: https://github.com/PHPCompatibility/PHPCompatibility/pull/680
[#681]: https://github.com/PHPCompatibility/PHPCompatibility/pull/681
[#691]: https://github.com/PHPCompatibility/PHPCompatibility/issues/691
[#692]: https://github.com/PHPCompatibility/PHPCompatibility/issues/692
[#697]: https://github.com/PHPCompatibility/PHPCompatibility/pull/697
[#698]: https://github.com/PHPCompatibility/PHPCompatibility/pull/698
[#699]: https://github.com/PHPCompatibility/PHPCompatibility/pull/699
[#700]: https://github.com/PHPCompatibility/PHPCompatibility/pull/700
[#701]: https://github.com/PHPCompatibility/PHPCompatibility/pull/701
[#702]: https://github.com/PHPCompatibility/PHPCompatibility/pull/702
[#705]: https://github.com/PHPCompatibility/PHPCompatibility/issues/705
[#706]: https://github.com/PHPCompatibility/PHPCompatibility/pull/706
[#707]: https://github.com/PHPCompatibility/PHPCompatibility/pull/707
[#710]: https://github.com/PHPCompatibility/PHPCompatibility/pull/710
[#711]: https://github.com/PHPCompatibility/PHPCompatibility/pull/711
[#712]: https://github.com/PHPCompatibility/PHPCompatibility/pull/712
[#714]: https://github.com/PHPCompatibility/PHPCompatibility/pull/714
[#715]: https://github.com/PHPCompatibility/PHPCompatibility/pull/715
[#716]: https://github.com/PHPCompatibility/PHPCompatibility/pull/716
[#717]: https://github.com/PHPCompatibility/PHPCompatibility/pull/717
[#718]: https://github.com/PHPCompatibility/PHPCompatibility/issues/718
[#719]: https://github.com/PHPCompatibility/PHPCompatibility/pull/719
[#720]: https://github.com/PHPCompatibility/PHPCompatibility/pull/720
[#721]: https://github.com/PHPCompatibility/PHPCompatibility/pull/721
[#722]: https://github.com/PHPCompatibility/PHPCompatibility/pull/722
[#723]: https://github.com/PHPCompatibility/PHPCompatibility/pull/723
[#724]: https://github.com/PHPCompatibility/PHPCompatibility/pull/724
[#725]: https://github.com/PHPCompatibility/PHPCompatibility/pull/725
[#727]: https://github.com/PHPCompatibility/PHPCompatibility/pull/727
[#728]: https://github.com/PHPCompatibility/PHPCompatibility/pull/728
[#730]: https://github.com/PHPCompatibility/PHPCompatibility/pull/730
[#731]: https://github.com/PHPCompatibility/PHPCompatibility/pull/731
[#735]: https://github.com/PHPCompatibility/PHPCompatibility/pull/735
[#736]: https://github.com/PHPCompatibility/PHPCompatibility/pull/736
[#738]: https://github.com/PHPCompatibility/PHPCompatibility/pull/738
[#739]: https://github.com/PHPCompatibility/PHPCompatibility/pull/739


## [8.2.0] - 2018-07-17

See all related issues and PRs in the [8.2.0 milestone].

### Important changes

#### The repository has moved
As of July 13 2018, the PHPCompatibility repository has moved from the personal account of Wim Godden `wimg` to its own organization `PHPCompatibility`.
Composer users are advised to update their `composer.json`. The dependency is now called `phpcompatibility/php-compatibility`.

#### Framework/CMS specific PHPCompatibility rulesets
Within this new organization, hosting will be offered for framework/CMS specific PHPCompatibility rulesets.

The first two such repositories have been created and are now available for use:
- PHPCompatibilityJoomla [GitHub][gh-phpcompat-joomla]|[Packagist][packagist-phpcompat-joomla]
- PHPCompatibilityWP [GitHub][gh-phpcompat-wp]|[Packagist][packagist-phpcompat-wp]

If you want to make sure you have all PHPCompatibility rulesets available at any time, you can use the PHPCompatibilityAll package [GitHub][gh-phpcompat-all]|[Packagist][packagist-phpcompat-all].

For more information, see the [Readme][README-framework-rulesets] and [Contributing guidelines][CONTRIBUTING-framework-rulesets].

#### Changes expected in PHPCompatibility 9.0.0
The next version of PHPCompatibility will include a major directory layout restructuring which means that the sniff codes of all sniffs will change.

In this same release, support for PHP_CodeSniffer 1.5.x will be dropped. The new minimum supported PHPCS version will be 2.3.0.

For more information about these upcoming changes, please read the [announcement][#688].

The `9.0.0` release is expected to be ready later this summer.


### Added
- :star2: New `ArgumentFunctionsUsage` sniff to detect usage of the `func_get_args()`, `func_get_arg()` and `func_num_args()` functions and the changes regarding these functions introduced in PHP 5.3. [#596]. Fixes [#372].
- :star2: New `DiscouragedSwitchContinue` sniff to detect `continue` targetting a `switch` control structure for which `E_WARNINGS` will be thrown as of PHP 7.3. [#687]
- :star2: New `NewClassMemberAccess` sniff to detect class member access on instantiation as added in PHP 5.4 and class member access on cloning as added in PHP 7.0. [#619]. Fixes [#53].
- :star2: New `NewConstantScalarExpressions` sniff to detect PHP 5.6 scalar expression in contexts where PHP previously only allowed static values. [#617]. Fixes [#399].
- :star2: New `NewGeneratorReturn` sniff to detect `return` statements within generators as introduced in PHP 7.0. [#618]
- :star2: New `PCRENewModifiers` sniff to initially detect the new `J` regex modifier as introduced in PHP 7.2. [#600]. Fixes [#556].
- :star2: New `ReservedFunctionNames` sniff to report on double underscore prefixed functions and methods. This was previously reported via an upstream sniff. [#581]
- :star2: New `NewTrailingComma` sniff to detect trailing commas in function calls, method calls, `isset()`  and `unset()` as will be introduced in PHP 7.3. [#632]
- :star2: New `Upgrade/LowPHPCS` sniff to give users of old PHP_CodeSniffer versions advance warning when support will be dropped in the near future. [#693]
- :star: `NewClasses` sniff: check for some 40+ additional PHP native classes added in various PHP versions. [#573]
- :star: `NewClosure` sniff: check for usage of `self`/`parent`/`static::` being used within closures, support for which was only added in PHP 5.4. [#669]. Fixes [#668].
- :star: `NewConstants` sniff: recognize constants added by the PHP 5.5+ password extension. [#626]
- :star: `NewFunctionParameters` sniff: recognize a number of additional function parameters added in PHP 7.0, 7.1 and 7.2. [#602]
- :star: `NewFunctions` sniff: recognize the PHP 5.1 SPL extension functions, the PHP 5.1.1 `hash_hmac()` function, the PHP 5.6 `pg_lo_truncate()` function, more PHP 7.2 Sodium functions and the new PHP 7.3 `is_countable()` function. [#606], [#625], [#640], [#651]
- :star: `NewHashAlgorithms` sniff: recognize the new hash algorithms which were added in PHP 7.1. [#599]
- :star: `NewInterfaces` sniff: check for the PHP 5.0 `Reflector` interface. [#572]
- :star: `OptionalRequiredFunctionParameters` sniff: detect missing `$salt` parameter in calls to the `crypt()` function (PHP 5.6+). [#605]
- :star: `RequiredOptionalFunctionParameters` sniff: recognize that the `$varname` parameter of `getenv()` and the `$scale` parameter of `bcscale()` have become optional as of PHP 7.1 and 7.3 respectively. [#598], [#612]
- :star: New `AbstractFunctionCallParameterSniff` to be used as a basis for sniffs examining function call parameters. [#636]
- :star: New `getReturnTypeHintName()` utility method to the `PHPCompatibility\Sniff` class. [#578], [#642]
- :star: New `isNumber()`, `isPositiveNumber()` and `isNegativeNumber()` utility methods to the `PHPCompatibility\Sniff` class. [#610], [#650]
- :star: New `isShortList()` utility method to the `PHPCompatibility\Sniff` class. [#635]
- :star: New `getCommandLineData()` method to the `PHPCompatibility\PHPCSHelper` class to provide PHPCS cross-version compatible access to command line info at run time. [#693]
- :star: Duplicate of upstream `findEndOfStatement()` method to the `PHPCompatibility\PHPCSHelper` class to allow for PHPCS cross-version usage of that method. [#614]
- :umbrella: additional unit test to confirm that the `PHPCompatibility\Sniff::isUseOfGlobalConstant()` method handles multi-constant declarations correctly. [#587]
- :umbrella: additional unit tests to confirm that the `PHPCompatibility\Sniff::isClassProperty()` method handles multi-property declarations correctly. [#583]
- :books: [Readme][README-framework-rulesets] & [Contributing][CONTRIBUTING-framework-rulesets]: add information about the framework/CMS specific rulesets. Related PRs: [#615], [#624], [#648], [#674], [#685], [#694]. Related to issue [#530].
- :books: Readme: information about the PHPCS 3.3.0 change which allows for a `testVersion` in a custom ruleset to be overruled by the command-line. [#607]

### Changed
- :books: Adjusted references to the old repository location throughout the codebase to reflect the move to a GitHub organization. [#689]
    This repository will now live in <https://github.com/PHPCompatibility/PHPCompatibility> and the Packagist reference will now be `phpcompatibility/php-compatibility`.
- :white_check_mark: The `getReturnTypeHintToken()` utility method has been made compatible with the changes in the PHPCS tokenizer which were introduced in PHP_CodeSniffer 3.3.0. [#642]. Fixes [#639].
- :pushpin: `ConstantArrayUsingConst`: improved handling of multi-constant declarations. [#593]
- :pushpin: `NewHeredocInitialize`: improved handling of constant declarations using the `const` keyword.
    The sniff will now also report on multi-declarations for variables, constants and class properties and on using heredoc as a function parameter default. [#641]
- :pushpin: `ForbiddenEmptyListAssignment`: this sniff will now also report on empty list assignments when the PHP 7.1 short list syntax is used. [#653]
- :pushpin: The `ForbiddenNegativeBitshift` sniff would previously only report on "bitshift right". As of this version, "bitshift left" and bitshift assignments will also be recognized. [#614]
- :pushpin: The `NewClasses` and `NewInterfaces` sniffs will now also report on new classes/interfaces when used as _return type_ declarations. [#578
- :pushpin: The `NewScalarTypeDeclarations` sniff will now recognize `parent` as a valid type declaration.
    The sniff will now also throw an error about using `self` and `parent` when PHP < 5.2 needs to be supported as PHP 5.1 and lower would presume these to be class names instead of keywords. [#595]
- :pushpin: The `PregReplaceEModifier` sniff - and the `PCRENewModifiers` sniff by extension - will now correctly examine and report on modifiers in regexes passed via calls to `preg_replace_callback_array()`. [#600], [#636]
- :pushpin: `getReturnTypeHintToken()` utility method: improved support for interface methods and abstract function declarations. [#652]
- :pushpin: The `findExtendedClassName()`, `findImplementedInterfaceNames()`, `getMethodParameters()` utility methods which are duplicates of upstream PHPCS methods, have been moved from the `PHPCompatibility\Sniff` class to the `PHPCompatibility\PHPCSHelper` class and have become static methods. [#613]
- :white_check_mark: `getReturnTypeHintToken()` utility method: align returned `$stackPtr` with native PHPCS behaviour by returning the last token of the type declaration. [#575]
- :white_check_mark: PHPCS cross-version compatibility: sync `getMethodParameters()` method with improved upstream version. [#643]
- :pencil2: The `MbstringReplaceEModifier`, `PregReplaceEModifier` and the `PregReplaceEModifier` sniffs now `extend` the new `AbstractFunctionCallParameterSniff` class. This should yield more accurate results when checking whether one of the target PHP functions was called. [#636]
- :pencil2: `DeprecatedNewReference` sniff: minor change to the error text and code - was `Forbidden`, now `Removed` -. Custom rulesets which explicitly excluded this error code will need to be updated. [#594]
- :pencil2: `NewScalarTypeDeclarations` sniff: minor change to the error message text.[#644]
- :umbrella: The unit test framework now allows for sniffs in categories other than `PHP`. [#634]
- :umbrella: Boyscouting: fixed up some (non-relevant) parse errors in a unit test case file. [#576]
- :green_heart: Travis: build tests are now also being run against the lowest supported PHPCS 3.x version. Previously only the highest supported PHPCS 3.x version was tested against. [#633]
- :books: Readme: Improved Composer install instructions. [#690]
- :books: Minor documentation fixes. [#672]
- :wrench: Minor performance optimizations and code simplifications. [#592], [#630], [#671]
- :wrench: Composer: Various improvements, including improved information about the suggested packages, suggesting `roave/security-advisories`, allowing for PHPUnit 7.x. [#604], [#616], [#622], [#646]
- :wrench: Various Travis build script improvements, including tweaks for faster build time, validation of the `composer.json` file, validation of the framework specific rulesets. [#570], [#571], [#579], [#621], [#631]
- :wrench: Build/PHPCS: made some more CS conventions explicit and start using PHPCS 3.x options for the PHPCompatibility native ruleset. [#586], [#667], [#673]
- :wrench: Some code style clean up and start using the new inline PHPCS 3.2+ annotations where applicable. [#586], [#591], [#620], [#673]

### Removed
- :no_entry_sign: PHPCompatibility no longer explicitly supports PHP_CodeSniffer 2.2.0. [#687], [#690]
- :no_entry_sign: The PHPCompatibility ruleset no longer includes the PHPCS native `Generic.NamingConventions.CamelCapsFunctionName`. Double underscore prefixed function names are now being reported on by a new dedicated sniff. [#581]
- :no_entry_sign: PHPCompatibility no longer explicitly supports HHVM and builds are no longer tested against HHVM.
    For now, running PHPCompatibility on HHVM to test PHP code may still work for a little while, but HHVM has announced they are [dropping PHP support][hhvm-drops-php]. [#623]. Fixes [#603].
- :books: Readme: badges from services which are no longer supported or inaccurate. [#609], [#628]

### Fixed
- :bug: Previously, the PHPCS native `Generic.NamingConventions.CamelCapsFunctionName` sniff was included in PHPCompatibility. Some error codes of this sniff were excluded, as well as some error messages changed (via the ruleset).
    If/when PHPCompatibility would be used in combination with a code style-type ruleset, this could inadvertently lead to underreporting of issues which the CS-type ruleset intends to have reported - i.e. the error codes excluded by PHPCompatibility -. This has now been fixed. [#581]
- :bug: The `ForbiddenNegativeBitshift` sniff would incorrectly throw an error when a bitshift was based on a calculation which included a negative number, but would not necessarily result in a negative number. [#614]. Fixes [#294], [#466].
- :bug: The `NewClosure` sniff would report the same issue twice when the issue was encountered in a nested closure. [#669]
- :bug: The `NewKeywords` sniff would underreport on non-lowercase keywords. [#627]
- :bug: The `NewKeywords` sniff would incorrectly report on the use of class constants and class properties using the same name as a keyword. [#627]
- :bug: The `NewNullableTypes` sniff would potentially underreport when comments where interspersed in the (return) type declarations. [#577]
- :bug: The `Sniff::getFunctionCallParameters()` utility method would in rare cases return incorrect results when it encountered a closure as a parameter. [#682]
- :bug: The `Sniff::getReturnTypeHintToken()` utility method would not always return a `$stackPtr`. [#645]
- :bug: Minor miscellanous other bugfixes. [#670]
- :umbrella: `PHPCompatibility\Tests\BaseClass\MethodTestFrame::getTargetToken()` could potentially not find the correct token to run a test against. [#588]

### Credits
Thanks go out to [Michael Babker] and [Juliette Reinders Folmer] for their contributions to this version. :clap:

[hhvm-drops-php]: https://hhvm.com/blog/2017/09/18/the-future-of-hhvm.html

[#53]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/53
[#294]: https://github.com/PHPCompatibility/PHPCompatibility/issues/294
[#372]: https://github.com/PHPCompatibility/PHPCompatibility/issues/372
[#399]: https://github.com/PHPCompatibility/PHPCompatibility/issues/399
[#466]: https://github.com/PHPCompatibility/PHPCompatibility/issues/466
[#530]: https://github.com/PHPCompatibility/PHPCompatibility/issues/530
[#556]: https://github.com/PHPCompatibility/PHPCompatibility/issues/556
[#570]: https://github.com/PHPCompatibility/PHPCompatibility/pull/570
[#571]: https://github.com/PHPCompatibility/PHPCompatibility/pull/571
[#572]: https://github.com/PHPCompatibility/PHPCompatibility/pull/572
[#573]: https://github.com/PHPCompatibility/PHPCompatibility/pull/573
[#575]: https://github.com/PHPCompatibility/PHPCompatibility/pull/575
[#576]: https://github.com/PHPCompatibility/PHPCompatibility/pull/576
[#577]: https://github.com/PHPCompatibility/PHPCompatibility/pull/577
[#578]: https://github.com/PHPCompatibility/PHPCompatibility/pull/578
[#579]: https://github.com/PHPCompatibility/PHPCompatibility/pull/579
[#581]: https://github.com/PHPCompatibility/PHPCompatibility/pull/581
[#583]: https://github.com/PHPCompatibility/PHPCompatibility/pull/583
[#586]: https://github.com/PHPCompatibility/PHPCompatibility/pull/586
[#587]: https://github.com/PHPCompatibility/PHPCompatibility/pull/587
[#588]: https://github.com/PHPCompatibility/PHPCompatibility/pull/588
[#591]: https://github.com/PHPCompatibility/PHPCompatibility/pull/591
[#592]: https://github.com/PHPCompatibility/PHPCompatibility/pull/592
[#593]: https://github.com/PHPCompatibility/PHPCompatibility/pull/593
[#594]: https://github.com/PHPCompatibility/PHPCompatibility/pull/594
[#595]: https://github.com/PHPCompatibility/PHPCompatibility/pull/595
[#596]: https://github.com/PHPCompatibility/PHPCompatibility/pull/596
[#598]: https://github.com/PHPCompatibility/PHPCompatibility/pull/598
[#599]: https://github.com/PHPCompatibility/PHPCompatibility/pull/599
[#600]: https://github.com/PHPCompatibility/PHPCompatibility/pull/600
[#602]: https://github.com/PHPCompatibility/PHPCompatibility/pull/602
[#603]: https://github.com/PHPCompatibility/PHPCompatibility/issues/603
[#604]: https://github.com/PHPCompatibility/PHPCompatibility/pull/604
[#605]: https://github.com/PHPCompatibility/PHPCompatibility/pull/605
[#606]: https://github.com/PHPCompatibility/PHPCompatibility/pull/606
[#607]: https://github.com/PHPCompatibility/PHPCompatibility/pull/607
[#609]: https://github.com/PHPCompatibility/PHPCompatibility/pull/609
[#610]: https://github.com/PHPCompatibility/PHPCompatibility/pull/610
[#612]: https://github.com/PHPCompatibility/PHPCompatibility/pull/612
[#613]: https://github.com/PHPCompatibility/PHPCompatibility/pull/613
[#614]: https://github.com/PHPCompatibility/PHPCompatibility/pull/614
[#615]: https://github.com/PHPCompatibility/PHPCompatibility/pull/615
[#616]: https://github.com/PHPCompatibility/PHPCompatibility/pull/616
[#617]: https://github.com/PHPCompatibility/PHPCompatibility/pull/617
[#618]: https://github.com/PHPCompatibility/PHPCompatibility/pull/618
[#619]: https://github.com/PHPCompatibility/PHPCompatibility/pull/619
[#620]: https://github.com/PHPCompatibility/PHPCompatibility/pull/620
[#621]: https://github.com/PHPCompatibility/PHPCompatibility/pull/621
[#623]: https://github.com/PHPCompatibility/PHPCompatibility/pull/623
[#622]: https://github.com/PHPCompatibility/PHPCompatibility/pull/622
[#624]: https://github.com/PHPCompatibility/PHPCompatibility/pull/624
[#625]: https://github.com/PHPCompatibility/PHPCompatibility/pull/625
[#626]: https://github.com/PHPCompatibility/PHPCompatibility/pull/626
[#627]: https://github.com/PHPCompatibility/PHPCompatibility/pull/627
[#628]: https://github.com/PHPCompatibility/PHPCompatibility/pull/628
[#630]: https://github.com/PHPCompatibility/PHPCompatibility/pull/630
[#631]: https://github.com/PHPCompatibility/PHPCompatibility/pull/631
[#632]: https://github.com/PHPCompatibility/PHPCompatibility/pull/632
[#633]: https://github.com/PHPCompatibility/PHPCompatibility/pull/633
[#634]: https://github.com/PHPCompatibility/PHPCompatibility/pull/634
[#635]: https://github.com/PHPCompatibility/PHPCompatibility/pull/635
[#636]: https://github.com/PHPCompatibility/PHPCompatibility/pull/636
[#639]: https://github.com/PHPCompatibility/PHPCompatibility/issues/639
[#640]: https://github.com/PHPCompatibility/PHPCompatibility/pull/640
[#641]: https://github.com/PHPCompatibility/PHPCompatibility/pull/641
[#642]: https://github.com/PHPCompatibility/PHPCompatibility/pull/642
[#643]: https://github.com/PHPCompatibility/PHPCompatibility/pull/643
[#644]: https://github.com/PHPCompatibility/PHPCompatibility/pull/644
[#645]: https://github.com/PHPCompatibility/PHPCompatibility/pull/645
[#646]: https://github.com/PHPCompatibility/PHPCompatibility/pull/646
[#648]: https://github.com/PHPCompatibility/PHPCompatibility/pull/648
[#650]: https://github.com/PHPCompatibility/PHPCompatibility/pull/650
[#651]: https://github.com/PHPCompatibility/PHPCompatibility/pull/651
[#653]: https://github.com/PHPCompatibility/PHPCompatibility/pull/653
[#667]: https://github.com/PHPCompatibility/PHPCompatibility/pull/667
[#668]: https://github.com/PHPCompatibility/PHPCompatibility/pull/668
[#669]: https://github.com/PHPCompatibility/PHPCompatibility/pull/669
[#670]: https://github.com/PHPCompatibility/PHPCompatibility/pull/670
[#671]: https://github.com/PHPCompatibility/PHPCompatibility/pull/671
[#672]: https://github.com/PHPCompatibility/PHPCompatibility/pull/672
[#673]: https://github.com/PHPCompatibility/PHPCompatibility/pull/673
[#674]: https://github.com/PHPCompatibility/PHPCompatibility/pull/674
[#682]: https://github.com/PHPCompatibility/PHPCompatibility/pull/682
[#685]: https://github.com/PHPCompatibility/PHPCompatibility/pull/685
[#687]: https://github.com/PHPCompatibility/PHPCompatibility/pull/687
[#688]: https://github.com/PHPCompatibility/PHPCompatibility/issues/688
[#689]: https://github.com/PHPCompatibility/PHPCompatibility/pull/689
[#690]: https://github.com/PHPCompatibility/PHPCompatibility/pull/690
[#693]: https://github.com/PHPCompatibility/PHPCompatibility/pull/693
[#694]: https://github.com/PHPCompatibility/PHPCompatibility/pull/694


## [8.1.0] - 2017-12-27

See all related issues and PRs in the [8.1.0 milestone].

### Added
- :star2: New `NewConstants` and `RemovedConstants` sniffs to detect usage of new/removed PHP constants for all PHP versions from PHP 5 up. [#526], [#551], [#566]. Fixes [#263].
- :star2: New `MagicAutoloadDeprecation` sniff to detect deprecated `__autoload()` functions as deprecated in PHP 7.2. [#540]
- :star2: New `OptionalRequiredFunctionParameter` sniff to check for missing function call parameters which were required and only became optional in a later PHP version. [#524]
- :star2: New `DynamicAccessToStatic` sniff to detect dynamic access to static methods and properties, as well as class constants, prior to PHP 5.3. [#535]. Fixes [#534].
- :star: `DeprecatedFunctions` sniff: recognize yet more PHP 7.2 deprecated functions. [#561], [#566]
- :star: `DeprecatedIniDirectives` sniff: recognize the last of the PHP 7.2 deprecated ini directives. [#566], [#567]
- :star: `NewFunctions` : detection of all new PHP 7.2 functions added. [#522], [#545], [#551], [#565]
- :star: `RemovedExtensions` : report on usage of the `mcrypt` extension which has been removed in PHP 7.2. [#566]
- :star: `RemovedGlobalVariables` : detection of the use of `$php_errormsg` with `track_errors` which has been deprecated in PHP 7.2. [#528]
- :books: Documentation : added reporting usage instructions. [#533], [#552]

### Changed
- :pushpin: `NewClosures` : downgraded "$this found in closure outside class" to warning. [#536]. Fixes [#527].
- :pushpin: `ForbiddenGlobalVariableVariable` : the sniff will now throw an error for each variable in a `global` statement which is no longer supported and show the variable found to make it easier to fix this. Previously only one error would be thrown per `global` statement. [#564]
- :pushpin: `ForbiddenGlobalVariableVariable` : the sniff will now throw `warning`s for non-bare variables used in a `global` statement as those are discouraged since PHP 7.0. [#564]
- :rewind: `NewLanguageConstructs` : updated the version number for `T_COALESCE_EQUAL`. [#523]
- :pencil2: `Sniff::getTestVersion()` : simplified regex logic. [#520]
- :green_heart: Travis : build tests are now being run against PHP 7.2 as well. [#511]
- :wrench: Improved check for superfluous whitespaces in files. [#542]
- :wrench: Build/PHPCS : stabilized the exclude patterns. [#529]
- :wrench: Build/PHPCS : added array indentation check. [#538]
- :white_check_mark: PHPCS cross-version compatibility : sync `FindExtendedClassname()` method with upstream. [#507]
- :wrench: The minimum version for the recommended `DealerDirect/phpcodesniffer-composer-installer` Composer plugin has been upped to `0.4.3`. [#548]

### Fixed
- :bug: `ForbiddenCallTimePassByReference` : a false positive was being thrown when a global constant was followed by a _bitwise and_. [#562]. Fixes [#39].
- :bug: `ForbiddenGlobalVariableVariable` : the sniff was overzealous and would also report on `global` in combination with variable variables which are still supported. [#564]. Fixes [#537].
- :bug: `ForbiddenGlobalVariableVariable` : variables interspersed with whitespace and/or comments were not being reported. [#564]
- :rewind: `ForbiddenNamesAsInvokedFunctions` : improved recognition of function invocations using forbidden words and prevent warnings for keywords which are no longer forbidden as method names in PHP 7.0+. [#516]. Fixes [#515]
- :bug: `VariableVariables` : variables interspersed with whitespace and/or comments were not being reported. [#563]
- :umbrella: Fixed some unintentional syntax errors in test files. [#539]
- :umbrella: Tests : fixed case numbering error. [#525]
- :books: Tests : added missing test skip explanation. [#521]
- :wrench: Fixed PHPCS whitespaces. [#543]
- :wrench: Fixed code test coverage verification. [#550]. Fixes [#549].

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Jonathan Van Belle] for their contributions to this version. :clap:

[#263]: https://github.com/PHPCompatibility/PHPCompatibility/issues/263
[#507]: https://github.com/PHPCompatibility/PHPCompatibility/pull/507
[#511]: https://github.com/PHPCompatibility/PHPCompatibility/pull/511
[#515]: https://github.com/PHPCompatibility/PHPCompatibility/issues/515
[#516]: https://github.com/PHPCompatibility/PHPCompatibility/pull/516
[#520]: https://github.com/PHPCompatibility/PHPCompatibility/pull/520
[#521]: https://github.com/PHPCompatibility/PHPCompatibility/pull/521
[#522]: https://github.com/PHPCompatibility/PHPCompatibility/pull/522
[#523]: https://github.com/PHPCompatibility/PHPCompatibility/pull/523
[#524]: https://github.com/PHPCompatibility/PHPCompatibility/pull/524
[#525]: https://github.com/PHPCompatibility/PHPCompatibility/pull/525
[#526]: https://github.com/PHPCompatibility/PHPCompatibility/pull/526
[#527]: https://github.com/PHPCompatibility/PHPCompatibility/issues/527
[#528]: https://github.com/PHPCompatibility/PHPCompatibility/pull/528
[#529]: https://github.com/PHPCompatibility/PHPCompatibility/pull/529
[#533]: https://github.com/PHPCompatibility/PHPCompatibility/pull/533
[#534]: https://github.com/PHPCompatibility/PHPCompatibility/issues/534
[#535]: https://github.com/PHPCompatibility/PHPCompatibility/pull/535
[#536]: https://github.com/PHPCompatibility/PHPCompatibility/pull/536
[#537]: https://github.com/PHPCompatibility/PHPCompatibility/issues/537
[#538]: https://github.com/PHPCompatibility/PHPCompatibility/pull/538
[#539]: https://github.com/PHPCompatibility/PHPCompatibility/pull/539
[#540]: https://github.com/PHPCompatibility/PHPCompatibility/pull/540
[#542]: https://github.com/PHPCompatibility/PHPCompatibility/pull/542
[#543]: https://github.com/PHPCompatibility/PHPCompatibility/pull/543
[#545]: https://github.com/PHPCompatibility/PHPCompatibility/pull/545
[#548]: https://github.com/PHPCompatibility/PHPCompatibility/pull/548
[#549]: https://github.com/PHPCompatibility/PHPCompatibility/issues/549
[#550]: https://github.com/PHPCompatibility/PHPCompatibility/pull/550
[#551]: https://github.com/PHPCompatibility/PHPCompatibility/pull/551
[#552]: https://github.com/PHPCompatibility/PHPCompatibility/pull/552
[#561]: https://github.com/PHPCompatibility/PHPCompatibility/pull/561
[#562]: https://github.com/PHPCompatibility/PHPCompatibility/pull/562
[#563]: https://github.com/PHPCompatibility/PHPCompatibility/pull/563
[#564]: https://github.com/PHPCompatibility/PHPCompatibility/pull/564
[#565]: https://github.com/PHPCompatibility/PHPCompatibility/pull/565
[#566]: https://github.com/PHPCompatibility/PHPCompatibility/pull/566
[#567]: https://github.com/PHPCompatibility/PHPCompatibility/pull/567


## [8.0.1] - 2017-08-07

See all related issues and PRs in the [8.0.1 milestone].

### Added
- :star2: New `DeprecatedTypeCasts` sniff to detect deprecated and removed type casts, such as the `(unset)` type cast as deprecated in PHP 7.2. [#498]
- :star2: New `NewTypeCasts` sniff to detect type casts not present in older PHP versions such as the `(binary)` type cast as added in PHP 5.2.1. [#497]
- :star: `NewGroupUseDeclaration`: Detection of PHP 7.2 trailing commas in group use statements. [#504]
- :star: `DeprecatedFunctions` sniff: recognize some more PHP 7.2 deprecated functions. [#501]
- :star: `DeprecatedIniDirectives` sniff: recognize more PHP 7.2 deprecated ini directives. [#500]
- :star: `ForbiddenNames` sniff: recognize `object` as a forbidden keyword since PHP 7.2. [#499]
- :star: `NewReturnTypeDeclarations` sniff: recognize generic `parent`, PHP 7.1 `iterable` and PHP 7.2 `object` return type declarations. [#505], [#499]
- :star: `NewScalarTypeDeclarations` sniff: recognize PHP 7.2 `object` type declarion. [#499]

### Changed
- :pencil2: Improved clarity of the deprecated functions alternative in the error message. [#502]

### Fixed
- :fire_engine: Temporary hotfix for installed_paths (pending [upstream fix][phpcs-squiz-fix-1591].) [#503]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[phpcs-squiz-fix-1591]: https://github.com/squizlabs/PHP_CodeSniffer/issues/1591

[#497]: https://github.com/PHPCompatibility/PHPCompatibility/pull/497
[#498]: https://github.com/PHPCompatibility/PHPCompatibility/pull/498
[#499]: https://github.com/PHPCompatibility/PHPCompatibility/pull/499
[#500]: https://github.com/PHPCompatibility/PHPCompatibility/pull/500
[#501]: https://github.com/PHPCompatibility/PHPCompatibility/pull/501
[#502]: https://github.com/PHPCompatibility/PHPCompatibility/pull/502
[#503]: https://github.com/PHPCompatibility/PHPCompatibility/pull/503
[#504]: https://github.com/PHPCompatibility/PHPCompatibility/pull/504
[#505]: https://github.com/PHPCompatibility/PHPCompatibility/pull/505


## [8.0.0] - 2017-08-03

**IMPORTANT**: This release contains a **breaking change**. Please read and follow the [Upgrade guide in the wiki][wiki-upgrade-to-8.0] carefully before upgrading!

The directory layout of the PHPCompatibility standard has been changed for improved compatibility with Composer.
This means that the PHPCompatibility standard no longer extends from the root directory of the repository, but now lives in its own subdirectory `/PHPCompatibility`.

This release also bring compatibility with PHPCS 3.x to the PHPCompatibility standard.

[wiki-upgrade-to-8.0]: https://github.com/PHPCompatibility/PHPCompatibility/wiki/Upgrading-to-PHPCompatibility-8.0


### Changelog for version 8.0.0

See all related issues and PRs in the [8.0.0 milestone].

### Added
- :two_hearts: Support for PHP CodeSniffer 3.x. [#482], [#481], [#480], [#488], [#489], [#495]

### Changed
- :gift: As of this version PHPCompatibility will use semantic versioning.
- :fire: The directory structure of the repository has changed for better compatibility with installation via Composer. [#446]. Fixes [#102], [#107]
- :pencil2: The custom `functionWhitelist` property for the `PHPCompatibility.PHP.RemovedExtensions` sniff is now only supported in combination with PHP CodeSniffer 2.6.0 or higher (due to an upstream bug which was fixed in PHPCS 2.6.0). [#482]
- :wrench: Improved the information provided to Composer from the `composer.json` file. [#446], [#482], [#486]
- :wrench: Release archives will no longer contain the unit tests and other typical development files. You can still get these by using Composer with `--prefer-source` or by checking out a git clone of the repository. [#494]
- :wrench: A variety of minor improvements to the build process. [#485], [#486], [#487]
- :wrench: Some files for use by contributors have been renamed to use `.dist` extensions or moved for easier access. [#478], [#479], [#483], [#493]
- :books: The installation instructions in the Readme. [#496]
- :books: The unit test instructions in the Contributing file. [#496]
- :books: Improved the example code in the Readme. [#490]

### Removed
- :no_entry_sign: Support for PHP 5.1 and 5.2.
    The sniffs can now only be run on PHP 5.3 or higher in combination with PHPCS 1.5.6 or 2.x and on PHP 5.4 or higher in combination with PHPCS 3.x. [#484], [#482]

### Credits
Thanks go out to [Gary Jones] and [Juliette Reinders Folmer] for their contributions to this version. :clap:

[#102]: https://github.com/PHPCompatibility/PHPCompatibility/issues/102
[#107]: https://github.com/PHPCompatibility/PHPCompatibility/issues/107
[#446]: https://github.com/PHPCompatibility/PHPCompatibility/pull/446
[#478]: https://github.com/PHPCompatibility/PHPCompatibility/pull/478
[#479]: https://github.com/PHPCompatibility/PHPCompatibility/pull/479
[#480]: https://github.com/PHPCompatibility/PHPCompatibility/pull/480
[#481]: https://github.com/PHPCompatibility/PHPCompatibility/pull/481
[#482]: https://github.com/PHPCompatibility/PHPCompatibility/pull/482
[#483]: https://github.com/PHPCompatibility/PHPCompatibility/pull/483
[#484]: https://github.com/PHPCompatibility/PHPCompatibility/pull/484
[#485]: https://github.com/PHPCompatibility/PHPCompatibility/pull/485
[#486]: https://github.com/PHPCompatibility/PHPCompatibility/pull/486
[#487]: https://github.com/PHPCompatibility/PHPCompatibility/pull/487
[#488]: https://github.com/PHPCompatibility/PHPCompatibility/pull/488
[#489]: https://github.com/PHPCompatibility/PHPCompatibility/pull/489
[#490]: https://github.com/PHPCompatibility/PHPCompatibility/pull/490
[#493]: https://github.com/PHPCompatibility/PHPCompatibility/pull/493
[#494]: https://github.com/PHPCompatibility/PHPCompatibility/pull/494
[#495]: https://github.com/PHPCompatibility/PHPCompatibility/pull/495
[#496]: https://github.com/PHPCompatibility/PHPCompatibility/pull/496


## [7.1.5] - 2017-07-17

See all related issues and PRs in the [7.1.5 milestone].

### Added
- :star: The `NewKeywords` sniff will now also sniff for `yield from` which was introduced in PHP 7.0. [#477]. Fixes [#476]
- :books: The LGPL-3.0 license. [#447]

### Changed
- :rewind: The `NewExecutionDirectives` sniff will now also report on execution directives when used in combination with PHPCS 2.0.0-2.3.3. [#451]
- :rewind: The `getMethodParameters()` utility method will no longer break when used with PHPCS 1.5.x < 1.5.6. This affected a number of sniffs. [#452]
- :rewind: The `inUseScope()` utility method will no longer break when used with PHPCS 2.0.0 - 2.2.0. This affected a number of sniffs. [#454]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#443], [#474]
- :pencil2: Renamed a test file for consistency. [#453]
- :wrench: Code style clean up. [#429]
- :wrench: Prevent Composer installing PHPCS 3.x. **_PHPCS 3.x is not (yet) supported by the PHPCompatibility standard, but will be in the near future._** [#444]
- :green_heart: The code base will now be checked for consistent code style during build testing. [#429]
- :green_heart: The sniffs are now also tested against HHVM for consistent results. _Note: the sniffs do not contain any HHVM specific checks nor is there any intention to add them at this time._ [#450]
- :books: Made it explicit that - at this moment - PHPCS 3.x is not (yet) supported. [#444]
- :books: Minor improvements to the Readme. [#448], [#449], [#468]
- :books: Minor improvements to the Contributing guidelines. [#467]

### Removed
- :no_entry_sign: The `DefaultTimeZoneRequired` sniff. This sniff was checking server settings rather than code. [#458]. Fixes [#457]
- :no_entry_sign: The `NewMagicClassConstant` sniff as introduced in v 7.1.4 contained two additional checks for not strictly compatibility related issues. One of these was plainly wrong, the other opinionated. Both have been removed. [#442]. Fixes [#436]

### Fixed
- :bug: `NewClasses` sniff: was reporting an incorrect introduction version number for a few of the Exception classes. [#441]. Fixes [#440].
- :bug: `ForbiddenBreakContinueVariableArguments` sniff: was incorrectly reporting an error if the `break` or `continue` was followed by a PHP closing tag (breaking out of PHP). [#462]. Fixes [#460]
- :bug: `ForbiddenGlobalVariableVariable` sniff: was incorrectly reporting an error if the `global` statement was followed by a PHP closing tag (breaking out of PHP). [#463].
- :bug: `DeprecatedFunctions` sniff: was reporting false positives for classes using the same name as a deprecated function. [#465]. Fixes [#464]

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:

[#429]: https://github.com/PHPCompatibility/PHPCompatibility/pull/429
[#436]: https://github.com/PHPCompatibility/PHPCompatibility/issues/436
[#440]: https://github.com/PHPCompatibility/PHPCompatibility/issues/440
[#441]: https://github.com/PHPCompatibility/PHPCompatibility/pull/441
[#442]: https://github.com/PHPCompatibility/PHPCompatibility/pull/442
[#443]: https://github.com/PHPCompatibility/PHPCompatibility/pull/443
[#444]: https://github.com/PHPCompatibility/PHPCompatibility/pull/444
[#447]: https://github.com/PHPCompatibility/PHPCompatibility/pull/447
[#448]: https://github.com/PHPCompatibility/PHPCompatibility/pull/448
[#449]: https://github.com/PHPCompatibility/PHPCompatibility/pull/449
[#450]: https://github.com/PHPCompatibility/PHPCompatibility/pull/450
[#451]: https://github.com/PHPCompatibility/PHPCompatibility/pull/451
[#452]: https://github.com/PHPCompatibility/PHPCompatibility/pull/452
[#453]: https://github.com/PHPCompatibility/PHPCompatibility/pull/453
[#454]: https://github.com/PHPCompatibility/PHPCompatibility/pull/454
[#457]: https://github.com/PHPCompatibility/PHPCompatibility/issues/457
[#458]: https://github.com/PHPCompatibility/PHPCompatibility/pull/458
[#460]: https://github.com/PHPCompatibility/PHPCompatibility/issues/460
[#462]: https://github.com/PHPCompatibility/PHPCompatibility/pull/462
[#463]: https://github.com/PHPCompatibility/PHPCompatibility/pull/463
[#464]: https://github.com/PHPCompatibility/PHPCompatibility/issues/464
[#465]: https://github.com/PHPCompatibility/PHPCompatibility/pull/465
[#467]: https://github.com/PHPCompatibility/PHPCompatibility/pull/467
[#468]: https://github.com/PHPCompatibility/PHPCompatibility/pull/468
[#474]: https://github.com/PHPCompatibility/PHPCompatibility/pull/474
[#476]: https://github.com/PHPCompatibility/PHPCompatibility/issues/476
[#477]: https://github.com/PHPCompatibility/PHPCompatibility/pull/477


## [7.1.4] - 2017-05-06

See all related issues and PRs in the [7.1.4 milestone].

### Added
- :star2: New `CaseSensitiveKeywords` sniff to detect use of non-lowercase `self`, `static` and `parent` keywords which could cause compatibility issues pre-PHP 5.5. [#382]
- :star2: New `ConstantArraysUsingConst` sniff to detect constants defined using the `const` keyword being assigned an array value which was not supported prior to PHP 5.6. [#397]
- :star2: New `ForbiddenClosureUseVariableNames` sniff to detect PHP 7.1 forbidden variable names in closure use statements. [#386]. Fixes [#374]
- :star2: New `NewArrayStringDereferencing` sniff to detect array and string literal dereferencing as introduced in PHP 5.5. [#388]
- :star2: New `NewHeredocInitialize` sniff to detect initialization of static variables and class properties/constants using the heredoc syntax which is supported since PHP 5.3. [#391]. Fixes [#51]
- :star2: New `NewMagicClassConstant` sniff to detect use of the magic `::class` constant as introduced in PHP 5.5. [#403]. Fixes [#364].
- :star2: New `NewUseConstFunction` sniff to detect use statements importing constants and functions as introduced in PHP 5.6. [#401]
- :star: `DeprecatedFunctions` sniff: recognize PHP 7.2 deprecated GD functions. [#392]
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.2 deprecated `mbstring.func_overload` directive. [#377]
- :star: `NewClasses` sniff: check for the PHP 5.1 `libXMLError` class. [#412]
- :star: `NewClasses` sniff: recognize all native PHP Exception classes. [#418]
- :star: `NewClosure` sniff: check for closures being declared as static and closures using `$this`. Both of which was not supported pre-PHP 5.4. [#389]. Fixes [#24].
- :star: `NewFunctionParameters` sniff: recognize new `exclude_disabled` parameter for the `get_defined_functions()` function as introduced in PHP 7.0.15. [#375]
- :star: `NewFunctions` sniff: recognize new PHP 7.2 socket related functions. [#376]
- :star: `NewInterfaces` sniff: check for some more PHP native interfaces. [#411]
- :star: New `isClassProperty()`, `isClassConstant()` and `validDirectScope()` utility methods to the `PHPCompatibility_Sniff` class. [#393], [#391].
- :star: New `getTypeHintsFromFunctionDeclaration()` utility method to the `PHPCompatibility_Sniff` class. [#414].
- :umbrella: Unit tests against false positives for the `NewMagicMethods` sniff. [#381]
- :umbrella: More unit tests for the `getTestVersion()` utility method. [#405], [#430]
- :green_heart: The XML of the ruleset will now be validated and checked for consistent code style during the build testing by Travis. [#433]
- :books: Readme: information about setting `installed_paths` via a custom ruleset. [#407]
- :books: `Changelog.md` file containing a record of notable changes since the first tagged release. [#421]

### Changed
- :pushpin: The `ForbiddenNamesAsDeclared` sniff will now emit `warning`s for soft reserved keywords. [#406], [#370].
- :pushpin: The `ForbiddenNames` sniff will now allow for the more liberal rules for usage of reserved keywords as of PHP 7.0. [#417]
- :pushpin: The `InternalInterfaces`, `NewClasses`, `NewConstVisibility`, `NewInterfaces`, `NewMagicMethods`, `NonStaticMagicMethods` and `RemovedGlobalVariables` sniffs will now also sniff for and correctly report violations in combination with anonymous classes. [#378], [#383], [#393], [#394], [#395], [#396]. Fixes [#351] and [#333].
- :pushpin: The `NewClasses` and `NewInterfaces` sniffs will now also report on new classes/interfaces when used as type hints. [#414], [#416]. Fixes [#352]
- :pushpin: The `NewClasses` sniff will now also report on Exception classes when used in (multi-)`catch` statements. [#418]. Fixes [#373].
- :pushpin: The `NewScalarTypeDeclarations` sniff will now report on new type hints even when the type hint is nullable. [#379]
- :twisted_rightwards_arrows: The `NewNowdoc` sniff has been renamed to `NewNowdocQuotedHeredoc` and will now also check for double quoted heredoc identifiers as introduced in PHP 5.3. [#390]
- :rewind: The `NewClasses` sniff will now also report anonymous classes which `extend` a new sniff when used in combination with PHPCS 2.4.0-2.8.0. [#432]. Fixes [#334].
- :pencil2: `NewFunctionParameter` sniff: version number precision for two parameters. [#384], [#428]
- :umbrella: Skipping two unit tests for the `ForbiddenClosureUseVariable` sniff when run on PHPCS 2.5.1 as these cause an infinite loop due to an upstream bug. [#408]
- :umbrella: Skipping unit tests involving `trait`s in combination with PHP < 5.4 and PHPCS < 2.4.0 as `trait`s are not recognized in those circumstances. [#431]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#385], [#387], [#415], [#423], [#424]
- :recycle: Minor simplification of the PHPUnit 6 compatibility layer and other test code. [#426], [#425]
- General housekeeping. [#398], [#400]
- :wrench: Minor tweaks to the Travis build script. [#409]
- :green_heart: The sniffs are now also tested against PHP nightly for consistent results. [#380]

### Fixed
- :fire: Using unbounded ranges in `testVersion` resulted in unreported errors when used with sniffs using the `supportsBelow()` method. This affected the results of approximately half the sniffs. [#430]
- :bug: The `ForbiddenNames` sniff would throw false positives for `use` statements with the `final` modifier in traits. [#402].
- :bug: The `ForbiddenNames` sniff would fail to report on functions declared to return by reference using a reserved keyword as the function name. [#413]
- :bug: The `ForbiddenNames` sniff would only examine the first part of a namespace and not report on reserved keywords used in subsequent parts of a nested namespace. [#419]
- :bug: The `ForbiddenNames` sniff would not always correctly report on use statements importing constants or functions using reserved keywords. [#420]
- :bug: The `NewKeywords` sniff would sometimes fail to report on the `const` keyword when used in a class, but not for a class constant. [#424]
- :green_heart: PHPCS has released version 3.0 and updated the `master` branch to reflect this. This was causing the builds to fail. [#422]

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:

[#24]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/24
[#51]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/51
[#333]: https://github.com/PHPCompatibility/PHPCompatibility/issues/333
[#334]: https://github.com/PHPCompatibility/PHPCompatibility/issues/334
[#351]: https://github.com/PHPCompatibility/PHPCompatibility/issues/351
[#352]: https://github.com/PHPCompatibility/PHPCompatibility/issues/352
[#364]: https://github.com/PHPCompatibility/PHPCompatibility/issues/364
[#370]: https://github.com/PHPCompatibility/PHPCompatibility/pull/370
[#373]: https://github.com/PHPCompatibility/PHPCompatibility/issues/373
[#374]: https://github.com/PHPCompatibility/PHPCompatibility/issues/374
[#375]: https://github.com/PHPCompatibility/PHPCompatibility/pull/375
[#376]: https://github.com/PHPCompatibility/PHPCompatibility/pull/376
[#377]: https://github.com/PHPCompatibility/PHPCompatibility/pull/377
[#378]: https://github.com/PHPCompatibility/PHPCompatibility/pull/378
[#379]: https://github.com/PHPCompatibility/PHPCompatibility/pull/379
[#380]: https://github.com/PHPCompatibility/PHPCompatibility/pull/380
[#381]: https://github.com/PHPCompatibility/PHPCompatibility/pull/381
[#382]: https://github.com/PHPCompatibility/PHPCompatibility/pull/382
[#383]: https://github.com/PHPCompatibility/PHPCompatibility/pull/383
[#384]: https://github.com/PHPCompatibility/PHPCompatibility/pull/384
[#385]: https://github.com/PHPCompatibility/PHPCompatibility/pull/385
[#386]: https://github.com/PHPCompatibility/PHPCompatibility/pull/386
[#387]: https://github.com/PHPCompatibility/PHPCompatibility/pull/387
[#388]: https://github.com/PHPCompatibility/PHPCompatibility/pull/388
[#389]: https://github.com/PHPCompatibility/PHPCompatibility/pull/389
[#390]: https://github.com/PHPCompatibility/PHPCompatibility/pull/390
[#391]: https://github.com/PHPCompatibility/PHPCompatibility/pull/391
[#392]: https://github.com/PHPCompatibility/PHPCompatibility/pull/392
[#393]: https://github.com/PHPCompatibility/PHPCompatibility/pull/393
[#394]: https://github.com/PHPCompatibility/PHPCompatibility/pull/394
[#395]: https://github.com/PHPCompatibility/PHPCompatibility/pull/395
[#396]: https://github.com/PHPCompatibility/PHPCompatibility/pull/396
[#397]: https://github.com/PHPCompatibility/PHPCompatibility/pull/397
[#398]: https://github.com/PHPCompatibility/PHPCompatibility/pull/398
[#400]: https://github.com/PHPCompatibility/PHPCompatibility/pull/400
[#401]: https://github.com/PHPCompatibility/PHPCompatibility/pull/401
[#402]: https://github.com/PHPCompatibility/PHPCompatibility/pull/402
[#403]: https://github.com/PHPCompatibility/PHPCompatibility/pull/403
[#405]: https://github.com/PHPCompatibility/PHPCompatibility/pull/405
[#406]: https://github.com/PHPCompatibility/PHPCompatibility/pull/406
[#407]: https://github.com/PHPCompatibility/PHPCompatibility/pull/407
[#408]: https://github.com/PHPCompatibility/PHPCompatibility/pull/408
[#409]: https://github.com/PHPCompatibility/PHPCompatibility/pull/409
[#411]: https://github.com/PHPCompatibility/PHPCompatibility/pull/411
[#412]: https://github.com/PHPCompatibility/PHPCompatibility/pull/412
[#413]: https://github.com/PHPCompatibility/PHPCompatibility/pull/413
[#414]: https://github.com/PHPCompatibility/PHPCompatibility/pull/414
[#415]: https://github.com/PHPCompatibility/PHPCompatibility/pull/415
[#416]: https://github.com/PHPCompatibility/PHPCompatibility/pull/416
[#417]: https://github.com/PHPCompatibility/PHPCompatibility/pull/417
[#418]: https://github.com/PHPCompatibility/PHPCompatibility/pull/418
[#419]: https://github.com/PHPCompatibility/PHPCompatibility/pull/419
[#420]: https://github.com/PHPCompatibility/PHPCompatibility/pull/420
[#421]: https://github.com/PHPCompatibility/PHPCompatibility/pull/421
[#422]: https://github.com/PHPCompatibility/PHPCompatibility/pull/422
[#423]: https://github.com/PHPCompatibility/PHPCompatibility/pull/423
[#424]: https://github.com/PHPCompatibility/PHPCompatibility/pull/424
[#425]: https://github.com/PHPCompatibility/PHPCompatibility/pull/425
[#426]: https://github.com/PHPCompatibility/PHPCompatibility/pull/426
[#428]: https://github.com/PHPCompatibility/PHPCompatibility/pull/428
[#430]: https://github.com/PHPCompatibility/PHPCompatibility/pull/430
[#431]: https://github.com/PHPCompatibility/PHPCompatibility/pull/431
[#432]: https://github.com/PHPCompatibility/PHPCompatibility/pull/432
[#433]: https://github.com/PHPCompatibility/PHPCompatibility/pull/433


## [7.1.3] - 2017-04-02

See all related issues and PRs in the [7.1.3 milestone].

### Added
- :zap: The `testVersion` config parameter now allows for specifying unbounded ranges.
    For example: specifying `-5.6` means: check for compatibility with all PHP versions up to and including PHP 5.6;
    Specifying `7.0-` means: check for compatibility with all PHP versions from PHP 7.0 upwards.
    For more information about setting the `testVersion`, see [Using the compatibility sniffs][README-using-the-sniffs] in the readme.
- :umbrella: Unit test for multi-line short arrays for the `ShortArray` sniff. [#347]
- :umbrella: Various additional unit tests against false positives. [#345], [#369]
- :umbrella: Unit tests for the `supportsBelow()`, `supportsAbove()` and `getTestVersion()` utility methods. [#363]
- :books: Readme: information about installation of the standard using git check-out. [#349]
- :books: `Contributing.md` file with information about reporting bugs, requesting features, making pull requests and running the unit tests. [#350]

### Changed
- :pushpin: The `ForbiddenFunctionParametersWithSameName`, `NewScalarTypeDeclarations`, `ParameterShadowSuperGlobals` sniff will now also sniff for and report violations in closures. [#331]
- :twisted_rightwards_arrows: :rewind: The check for the PHP 5.3 `nowdoc` structure has been moved from the `NewKeywords` sniff to a new stand-alone `NewNowdoc` sniff which will now also recognize this structure when the sniffs are run on PHP 5.2. [#335]
- :rewind: The `ForbiddenNames` sniff will now also correctly recognize reserved keywords used in a declared namespace when run on PHP 5.2. [#362]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#360]
- :recycle: The unit tests would previously run each test case file against all PHPCompatibility sniffs. Now, they will only be tested against the sniff which the test case file is intended to test. This allows for more test cases to be tested, more precise testing in combination with `testVersion` settings and makes the unit tests run ~6 x faster.
    Relevant additional unit tests have been added and others adjusted. [#369]
- :recycle: Refactoring/tidying up of some unit test code. [#343], [#345], [#356], [#355], [#359]
- General housekeeping. [#346]
- :books: Readme: Clarify minimum requirements and influence on the results. [#348]

### Removed
- :twisted_rightwards_arrows: Removed the `LongArrays` sniff. The checks it contained have been moved into the `RemovedGlobalVariables` sniff. Both sniffs essentially did the same thing, just for different PHP native superglobals. [#354]

### Fixed
- :bug: The `PregReplaceEModifier` sniff would throw a false positive if a quote character was used as the regex delimiter. [#357]
- :bug: `RemovedGlobalVariables` sniff would report false positives for class properties shadowing the removed `$HTTP_RAW_POST_DATA` variables. [#354].
- :bug: The `getFQClassNameFromNewToken()` utility function could go into an infinite loop causing PHP to run out of memory when examining unfinished code (examination during live coding). [#338], [#342]
- :bug: The `determineNamespace()` utility method would in certain cases not break out a loop. [#358]
- :wrench: Travis script: Minor tweak for PHP 5.2 compatibility. [#341]
- :wrench: The unit test suite is now also compatible with PHPUnit 6. [#365]
- :books: Readme: Typo in the composer instructions. [#344]

### Credits
Thanks go out to [Arthur Edamov], [Juliette Reinders Folmer], [Mark Clements] and [Tadas Juozapaitis] for their contributions to this version. :clap:

[#331]: https://github.com/PHPCompatibility/PHPCompatibility/pull/331
[#335]: https://github.com/PHPCompatibility/PHPCompatibility/pull/335
[#338]: https://github.com/PHPCompatibility/PHPCompatibility/pull/338
[#341]: https://github.com/PHPCompatibility/PHPCompatibility/pull/341
[#342]: https://github.com/PHPCompatibility/PHPCompatibility/pull/342
[#343]: https://github.com/PHPCompatibility/PHPCompatibility/pull/343
[#344]: https://github.com/PHPCompatibility/PHPCompatibility/pull/344
[#345]: https://github.com/PHPCompatibility/PHPCompatibility/pull/345
[#346]: https://github.com/PHPCompatibility/PHPCompatibility/pull/346
[#347]: https://github.com/PHPCompatibility/PHPCompatibility/pull/347
[#348]: https://github.com/PHPCompatibility/PHPCompatibility/pull/348
[#349]: https://github.com/PHPCompatibility/PHPCompatibility/pull/349
[#350]: https://github.com/PHPCompatibility/PHPCompatibility/pull/350
[#354]: https://github.com/PHPCompatibility/PHPCompatibility/pull/354
[#355]: https://github.com/PHPCompatibility/PHPCompatibility/pull/355
[#356]: https://github.com/PHPCompatibility/PHPCompatibility/pull/356
[#357]: https://github.com/PHPCompatibility/PHPCompatibility/pull/357
[#358]: https://github.com/PHPCompatibility/PHPCompatibility/pull/358
[#359]: https://github.com/PHPCompatibility/PHPCompatibility/pull/359
[#360]: https://github.com/PHPCompatibility/PHPCompatibility/pull/360
[#362]: https://github.com/PHPCompatibility/PHPCompatibility/pull/362
[#363]: https://github.com/PHPCompatibility/PHPCompatibility/pull/363
[#365]: https://github.com/PHPCompatibility/PHPCompatibility/pull/365
[#369]: https://github.com/PHPCompatibility/PHPCompatibility/pull/369


## [7.1.2] - 2017-02-17

See all related issues and PRs in the [7.1.2 milestone].

### Added
- :star2: New `VariableVariables` sniff to detect variables variables for which the behaviour has changed in PHP 7.0. [#310] Fixes [#309].
- :star: The `NewReturnTypeDeclarations` sniff will now also sniff for non-scalar return type declarations, i.e. `array`, `callable`, `self` or a class name. [#323]
- :star: The `NewLanguageConstructs` sniff will now also sniff for the null coalesce equal operator `??=`. This operator is slated to be introduced in PHP 7.2 and PHPCS already accounts for it. [#340]
- :star: New `getReturnTypeHintToken()` utility method to the `PHPCompatibility_Sniff` class to retrieve return type hints from function declarations in a cross-PHPCS-version compatible way. [#323].
- :star: New `stripVariables()` utility method to the `PHPCompatibility_Sniff` class to strip variables from interpolated text strings. [#314].
- :umbrella: Additional unit tests covering previously uncovered code. [#308]

### Changed
- :pushpin: The `MbstringReplaceEModifier`, `PregReplaceEModifier` and `NewExecutionDirectives` sniffs will now also correctly interpret double quoted text strings with interpolated variables. [#314], [#324].
- :pushpin: The `NewNullableTypes` sniff will now also report on nullable (return) type hints when used with closures. [#323]
- :pushpin: The `NewReturnTypeDeclarations` sniff will now also report on return type hints when used with closures. [#323]
- :pushpin: Allow for anonymous classes in the `inClassScope()` utility method. [#315]
- :pushpin: The function call parameter related utility functions can now also be used to get the individual items from an array declaration. [#300]
- :twisted_rightwards_arrows: The `NewScalarReturnTypeDeclarations` sniff has been renamed to `NewReturnTypeDeclarations`. [#323]
- :rewind: The `ForbiddenNames` sniff will now also correctly ignore anonymous classes when used in combination with PHPCS < 2.3.4. [#319]
- :rewind: The `NewAnonymousClasses` sniff will now correctly recognize and report on anonymous classes when used in combination with PHPCS < 2.5.2. [#325]
- :rewind: The `NewGroupUseDeclarations` sniff will now correctly recognize and report on group use statements when used in combination with PHPCS < 2.6.0. [#320]
- :rewind: The `NewNullableTypes` sniff will now correctly recognize and report on nullable return types when used in combination with PHPCS < 2.6.0. [#323]
- :rewind: The `NewReturnTypeDeclarations` sniff will now correctly recognize and report on new return types when used in combination with PHPCS < 2.6.0. [#323]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#317]
- :recycle: Defer to upstream `hasCondition()` utility method where appropriate. [#315]
- :recycle: Minor refactoring of some unit test code. [#304], [#303], [#318]
- :wrench: All unit tests now have appropriate `@group` annotations allowing for quicker/easier testing of a select group of tests/sniffs. [#305]
- :wrench: All unit tests now have appropriate `@covers` annotations to improve code coverage reporting and remove bleed through of accidental coverage. [#307]
- :wrench: Minor tweaks to the travis script. [#322]
- :green_heart: The PHPCompatibility code base itself will now be checked for cross-version compatibility during build testing. [#322]

### Fixed
- :bug: The `ConstantArraysUsingDefine` sniff would throw false positives if the value of the `define()` was retrieved via a function call and an array parameter was passed. [#327]
- :bug: The `ForbiddenCallTimePassByReference` sniff would throw false positives on assign by reference within function calls or conditions. [#302] Fixes the last two cases reported in [#68][#68-comment]
- :bug: The `ForbiddenGlobalVariableVariableSniff` sniff would only examine the first variable in a `global ...` statement causing unreported issues if subsequent variables were variable variables. [#316]
- :bug: The `NewKeywords` sniff would throw a false positive for the `const` keyword when encountered in an interface. [#312]
- :bug: The `NewNullableTypes` sniff would not report on nullable return types for namespaced classnames used as a type hint. [#323]
- :bug: The `PregReplaceEModifier` sniff would always consider the first parameter passed as a single regex, while it could also be an array of regexes. This led to false positives and potentially unreported use of the `e` modifier when an array of regexes was passed. [#300]
- :bug: The `PregReplaceEModifier` sniff could misidentify the regex delimiter when the regex to be examined was concatenated together from various text strings taken from a compound parameter leading to false positives. [#300]
- :white_check_mark: Compatibility with PHPCS 2.7.x. Deal with changed behaviour of the upstream PHP tokenizer and utility function(s). [#313], [#323], [#326], [#340]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#68-comment]: https://github.com/PHPCompatibility/PHPCompatibility/issues/68#issuecomment-231366445
[#300]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/300
[#302]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/302
[#303]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/303
[#304]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/304
[#305]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/305
[#307]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/307
[#308]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/308
[#309]:        https://github.com/PHPCompatibility/PHPCompatibility/issues/309
[#310]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/310
[#312]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/312
[#313]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/313
[#314]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/314
[#315]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/315
[#316]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/316
[#317]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/317
[#318]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/318
[#319]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/319
[#320]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/320
[#322]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/322
[#323]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/323
[#324]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/324
[#325]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/325
[#326]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/326
[#327]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/327
[#340]:        https://github.com/PHPCompatibility/PHPCompatibility/pull/340


## [7.1.1] - 2016-12-14

See all related issues and PRs in the [7.1.1 milestone].

### Added
- :star: `ForbiddenNamesAsDeclared` sniff: detection of the PHP 7.1 `iterable` and `void` reserved keywords when used to name classes, interfaces or traits. [#298]

### Fixed
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would incorrectly throw an error if the `clone` keyword was used with parenthesis. [#299]. Fixes [#284]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#284]: https://github.com/PHPCompatibility/PHPCompatibility/issues/284
[#298]: https://github.com/PHPCompatibility/PHPCompatibility/pull/298
[#299]: https://github.com/PHPCompatibility/PHPCompatibility/pull/299


## [7.1.0] - 2016-12-14

See all related issues and PRs in the [7.1.0 milestone].

### Added
- :star: New `stringToErrorCode()`, `arrayKeysToLowercase()` and `addMessage()` utility methods to the `PHPCompatibility_Sniff` class. [#291].

### Changed
- :pushpin: All sniff error messages now have modular error codes allowing for selectively disabling individual checks - and even selectively disabling individual sniff for specific files - without disabling the complete sniff. [#291]
- :pencil2: Minor changes to some of the error message texts for consistency across sniffs. [#291]
- :recycle: Refactored the complex version sniffs to reduce code duplication. [#291]
- :recycle: Miscellaneous other refactoring for improved performance and sniff accuracy. [#291]
- :umbrella: The unit tests for the `RemovedExtensions` sniff now verify that the correct alternative extension is being suggested. [#291]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#291]: https://github.com/PHPCompatibility/PHPCompatibility/pull/291


## [7.0.8] - 2016-10-31 - :ghost: Spooky! :jack_o_lantern:

See all related issues and PRs in the [7.0.8 milestone].

### Added
- :star2: New `ForbiddenNamesAsDeclared` sniff: detection of the [other reserved keywords][php-other-reserved-keywords] which are reserved as of PHP 7.0 (or higher). [#287]. Fixes [#115].
    These were previously sniffed for by the `ForbiddenNames` and `ForbiddenNamesAsInvokedFunctions` sniffs causing false positives as the rules for their reservation are different from the rules for "normal" [reserved keywords][php-reserved-keywords].
- :star: New `inUseScope()` utility method to the `PHPCompatibility_Sniff` class which handles PHPCS cross-version compatibility when determining the scope of a `use` statement. [#271].
- :umbrella: More unit tests for the `ForbiddenNames` sniff. [#271].

### Changed
- :pushpin: _Deprecated_ functionality should throw a `warning`. _Removed_ or otherwise unavailable functionality should throw an `error`. This distinction was previously not consistently applied everywhere. [#286]
    This change affects the following sniffs:
    * `DeprecatedPHP4StyleConstructors` will now throw a `warning` instead of an `error` for deprecated PHP4 style class constructors.
    * `ForbiddenCallTimePassByReference` will now throw a `warning` if the `testVersion` is `5.3` and an `error` if the `testVersion` if `5.4` or higher.
    * `MbstringReplaceEModifier` will now throw a `warning` instead of an `error` for usage of the deprecated `e` modifier.
    * `PregReplaceEModifier` will now throw a `warning` if the `testVersion` is `5.5` or `5.6` and an `error` if the `testVersion` if `7.0` or higher. Fixes [#290].
    * `TernaryOperators` will now throw an `error` when the `testVersion` < `5.3` and the middle part has been omitted.
    * `ValidIntegers` will now throw a `warning` when an invalid binary integer is detected.
- :pencil2: `DeprecatedFunctions` and `DeprecatedIniDirectives` sniffs: minor change in the sniff error message text. Use _"removed"_ rather than the ominous _"forbidden"_. [#285]
    Also updated relevant internal variable names and documentation to match.

### Fixed
- :bug: `ForbiddenNames` sniff would throw false positives for `use` statements which changed the visibility of methods in traits. [#271].
- :bug: `ForbiddenNames` sniff would not report reserved keywords when used in combination with `use function` or `use const`. [#271].
- :bug: `ForbiddenNames` sniff would potentially - unintentionally - skip over tokens, thereby - potentially - not reporting all errors. [#271].
- :wrench: Composer config: `prefer-stable` should be a root element of the json file. Fixes [#277].

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[php-reserved-keywords]:       https://www.php.net/reserved.keywords
[php-other-reserved-keywords]: https://www.php.net/reserved.other-reserved-words

[#115]: https://github.com/PHPCompatibility/PHPCompatibility/issues/115
[#271]: https://github.com/PHPCompatibility/PHPCompatibility/pull/271
[#277]: https://github.com/PHPCompatibility/PHPCompatibility/issues/277
[#285]: https://github.com/PHPCompatibility/PHPCompatibility/pull/285
[#286]: https://github.com/PHPCompatibility/PHPCompatibility/pull/286
[#287]: https://github.com/PHPCompatibility/PHPCompatibility/pull/287
[#290]: https://github.com/PHPCompatibility/PHPCompatibility/issues/290


## [7.0.7] - 2016-10-20

See all related issues and PRs in the [7.0.7 milestone].

### Added
- :star2: New `ForbiddenBreakContinueOutsideLoop` sniff: verify that `break`/`continue` is not used outside of a loop structure. This will cause fatal errors since PHP 7.0. [#278]. Fixes [#275]
- :star2: New `NewConstVisibility` sniff: detect visibility indicators for `class` and `interface` constants as introduced in PHP 7.1. [#280]. Fixes [#249]
- :star2: New `NewHashAlgorithms` sniff to check used hash algorithms against the PHP version in which they were introduced. [#242]
- :star2: New `NewMultiCatch` sniff: detect catch statements catching multiple Exceptions as introduced in PHP 7.1. [#281]. Fixes [#251]
- :star2: New `NewNullableTypes` sniff: detect nullable parameter and return type hints (only supported in PHPCS >= 2.3.4) as introduced in PHP 7.1. [#282]. Fixes [#247]
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.1 removed `session` ini directives. [#256]
- :star: `NewFunctions` sniff: recognize new `socket_export_stream()` function as introduced in PHP 7.0.7. [#264]
- :star: `NewFunctions` sniff: recognize new `curl_...()`, `is_iterable()`, `pcntl_async_signals()`, `session_create_id()`, `session_gc()` functions as introduced in PHP 7.1. [#273]
- :star: `NewFunctionParameters` sniff: recognize new OpenSSL function parameters as introduced in PHP 7.1. [#258]
- :star: `NewIniDirectives` sniff: recognize new `session` ini directives as introduced in PHP 7.1. [#259]
- :star: `NewScalarReturnTypeDeclarations` sniff: recognize PHP 7.1 `void` return type hint. [#250]
- :star: `NewScalarTypeDeclarations` sniff: recognize PHP 7.1 `iterable` type hint. [#255]
- :star: Recognize the PHP 7.1 deprecated `mcrypt` functionality in the `RemovedExtensions`, `DeprecatedFunctions` and `DeprecatedIniDirectives` sniffs. [#257]

### Changed
- :pushpin: `LongArrays` sniff used to only throw `warning`s. It will now throw `error`s for PHP versions in which the long superglobals have been removed. [#270]
- :pushpin: The `NewIniDirectives` sniff used to always throw an `warning`. Now it will throw an `error` when a new ini directive is used in combination with `ini_set()`. [#246].
- :pushpin: `RemovedHashAlgorithms` sniff: also recognize removed algorithms when used with the PHP 5.5+ `hash_pbkdf2()` function. [#240]
- :pushpin: Properly recognize nullable type hints in the `getMethodParameters()` utility method. [#282]
- :pencil2: `DeprecatedPHP4StyleConstructors` sniff: minor error message text fix. [#236]
- :pencil2: `NewIniDirectives` sniff: improved precision for the introduction version numbers being reported. [#246]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#238], [#244], [#240], [#276]
- :umbrella: Re-activate the unit tests for the `NewScalarReturnTypeDeclarations` sniff. [#250]

### Fixed
- :bug: The `DeprecatedPHP4StyleConstructors` sniff would not report errors when the case of the class name and the PHP4 constructor function name did not match. [#236]
- :bug: `LongArrays` sniff would report false positives for class properties shadowing removed PHP superglobals. [#270]. Fixes [#268].
- :bug: The `NewClasses` sniff would not report errors when the case of the class name used and "official" class name did not match. [#237]
- :bug: The `NewIniDirectives` sniff would report violations against the PHP version in which the ini directive was introduced. This should be the version below it. [#246]
- :bug: `PregReplaceEModifier` sniff would report false positives for compound regex parameters with different quote types. [#266]. Fixes [#265].
- :bug: `RemovedGlobalVariables` sniff would report false positives for lowercase/mixed cased variables shadowing superglobals. [#245].
- :bug: The `RemovedHashAlgorithms` sniff would not report errors when the case of the hash function name used and "official" class name did not match. [#240]
- :bug: The `ShortArray` sniff would report all violations on the line of the PHP open tag, not on the lines of the short array open/close tags. [#238]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#236]: https://github.com/PHPCompatibility/PHPCompatibility/pull/236
[#237]: https://github.com/PHPCompatibility/PHPCompatibility/pull/237
[#238]: https://github.com/PHPCompatibility/PHPCompatibility/pull/238
[#240]: https://github.com/PHPCompatibility/PHPCompatibility/pull/240
[#242]: https://github.com/PHPCompatibility/PHPCompatibility/pull/242
[#244]: https://github.com/PHPCompatibility/PHPCompatibility/pull/244
[#245]: https://github.com/PHPCompatibility/PHPCompatibility/pull/245
[#246]: https://github.com/PHPCompatibility/PHPCompatibility/pull/246
[#247]: https://github.com/PHPCompatibility/PHPCompatibility/issues/247
[#249]: https://github.com/PHPCompatibility/PHPCompatibility/issues/249
[#250]: https://github.com/PHPCompatibility/PHPCompatibility/pull/250
[#251]: https://github.com/PHPCompatibility/PHPCompatibility/issues/251
[#255]: https://github.com/PHPCompatibility/PHPCompatibility/pull/255
[#256]: https://github.com/PHPCompatibility/PHPCompatibility/pull/256
[#257]: https://github.com/PHPCompatibility/PHPCompatibility/pull/257
[#258]: https://github.com/PHPCompatibility/PHPCompatibility/pull/258
[#259]: https://github.com/PHPCompatibility/PHPCompatibility/pull/259
[#264]: https://github.com/PHPCompatibility/PHPCompatibility/pull/264
[#265]: https://github.com/PHPCompatibility/PHPCompatibility/issues/265
[#266]: https://github.com/PHPCompatibility/PHPCompatibility/pull/266
[#268]: https://github.com/PHPCompatibility/PHPCompatibility/issues/268
[#270]: https://github.com/PHPCompatibility/PHPCompatibility/pull/270
[#273]: https://github.com/PHPCompatibility/PHPCompatibility/pull/273
[#275]: https://github.com/PHPCompatibility/PHPCompatibility/issues/275
[#276]: https://github.com/PHPCompatibility/PHPCompatibility/pull/276
[#278]: https://github.com/PHPCompatibility/PHPCompatibility/pull/278
[#280]: https://github.com/PHPCompatibility/PHPCompatibility/pull/280
[#281]: https://github.com/PHPCompatibility/PHPCompatibility/pull/281
[#282]: https://github.com/PHPCompatibility/PHPCompatibility/pull/282


## [7.0.6] - 2016-09-23

See all related issues and PRs in the [7.0.6 milestone].

### Added
- :star: New `stripQuotes()` utility method in the `PHPCompatibility_Sniff` base class to strip quotes which surround text strings in a consistent manner. [#224]
- :books: Readme: Add _PHP Version Support_ section. [#225]

### Changed
- :pushpin: The `ForbiddenCallTimePassByReference` sniff will now also report the deprecation as of PHP 5.3, not just its removal as of PHP 5.4. [#203]
- :pushpin: The `NewFunctionArrayDereferencing` sniff will now also check _method_ calls for array dereferencing, not just function calls. [#229]. Fixes [#227].
- :pencil2: The `NewExecutionDirectives` sniff will now throw `warning`s instead of `error`s for invalid values encountered in execution directives. [#223]
- :pencil2: Minor miscellaneous fixes. [#231]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#219], [#203]
- :recycle: Defer to upstream `findImplementedInterfaceNames()` utility method when it exists. [#222]
- :wrench: Exclude the test files from analysis by Scrutinizer CI. [#230]

### Removed
- :no_entry_sign: Some redundant code. [#232]

### Fixed
- :bug: The `EmptyNonVariable` sniff would throw false positives for variable variables and for array access with a (partially) variable array index. [#212]. Fixes [#210].
- :bug: The `NewFunctionArrayDereferencing` sniff would throw false positives for lines of code containing both a function call as well as square brackets, even when they were unrelated. [#228]. Fixes [#226].
- :bug: `ParameterShadowSuperGlobals` sniff would report false positives for lowercase/mixed cased variables shadowing superglobals. [#218]. Fixes [#214].
- :bug: The `determineNamespace()` utility method now accounts properly for namespaces within scoped `declare()` statements. [#221]
- :books: Readme: Logo alignment in the Credits section. [#233]

### Credits
Thanks go out to [Jason Stallings], [Juliette Reinders Folmer] and [Mark Clements] for their contributions to this version. :clap:

[#203]: https://github.com/PHPCompatibility/PHPCompatibility/pull/203
[#210]: https://github.com/PHPCompatibility/PHPCompatibility/issues/210
[#212]: https://github.com/PHPCompatibility/PHPCompatibility/pull/212
[#214]: https://github.com/PHPCompatibility/PHPCompatibility/issues/214
[#218]: https://github.com/PHPCompatibility/PHPCompatibility/pull/218
[#219]: https://github.com/PHPCompatibility/PHPCompatibility/pull/219
[#221]: https://github.com/PHPCompatibility/PHPCompatibility/pull/221
[#222]: https://github.com/PHPCompatibility/PHPCompatibility/pull/222
[#223]: https://github.com/PHPCompatibility/PHPCompatibility/pull/223
[#224]: https://github.com/PHPCompatibility/PHPCompatibility/pull/224
[#225]: https://github.com/PHPCompatibility/PHPCompatibility/pull/225
[#226]: https://github.com/PHPCompatibility/PHPCompatibility/issues/226
[#227]: https://github.com/PHPCompatibility/PHPCompatibility/issues/227
[#228]: https://github.com/PHPCompatibility/PHPCompatibility/pull/228
[#229]: https://github.com/PHPCompatibility/PHPCompatibility/pull/229
[#230]: https://github.com/PHPCompatibility/PHPCompatibility/pull/230
[#231]: https://github.com/PHPCompatibility/PHPCompatibility/pull/231
[#232]: https://github.com/PHPCompatibility/PHPCompatibility/pull/232
[#233]: https://github.com/PHPCompatibility/PHPCompatibility/pull/233


## [7.0.5] - 2016-09-08

See all related issues and PRs in the [7.0.5 milestone].

### Added
- :star2: New `MbstringReplaceEModifier` sniff to detect the use of the PHP 7.1 deprecated `e` modifier in Mbstring regex functions. [#202]
- :star: The `ForbiddenBreakContinueVariableArguments` sniff will now also report on `break 0`/`continue 0` which is not allowed since PHP 5.4. [#209]
- :star: New `getFunctionCallParameters()`, `getFunctionCallParameter()` utility methods in the `PHPCompatibility_Sniff` base class. [#170]
- :star: New `tokenHasScope()` utility method in the `PHPCompatibility_Sniff` base class. [#189]
- :umbrella: Unit test for `goto` and `callable` detection and some other miscellanous extra unit tests for the `NewKeywords` sniff. [#189]
- :books: Readme: Information for sniff developers about running unit tests for _other_ sniff libraries using the PHPCS native test framework without running into conflicts with the PHPCompatibility specific unit test framework. [#217]

### Changed
- :pushpin: The `ForbiddenNames` sniff will now also check interface declarations for usage of reserved keywords. [#200]
- :pushpin: `PregReplaceEModifier` sniff: improved handling of regexes build up of a combination of variables, function calls and/or text strings. [#201]
- :rewind: The `NewKeywords` sniff will now also correctly recognize new keywords when used in combination with older PHPCS versions and/or run on older PHP versions. [#189]
- :pencil2: `PregReplaceEModifier` sniff: minor improvement to the error message text. [#201]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#170], [#188], [#189], [#199], [#200], [#201], [#208]
- :wrench: The unit tests for the utility methods have been moved to their own subdirectory within `Tests`. [#215]
- :green_heart: The sniffs are now also tested against PHP 7.1 for consistent results. [#216]

### Removed
- :no_entry_sign: Some redundant code. [26d0b6] and [841616]

### Fixed
- :bug: `ConstantArraysUsingDefine` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#199]
- :bug: The `DeprecatedIniDirectives` and `NewIniDirectives` sniffs could potentially trigger on the ini value instead of the ini directive name. [#170]
- :bug: `ForbiddenNames` sniff: Reserved keywords when used as the name of a constant declared using `define()` would always be reported independently of the `testVersion` set. [#200]
- :bug: `PregReplaceEModifier` sniff would not report errors when the function name used was not in lowercase. [#201]
- :bug: `TernaryOperators` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#188]
- :bug: The `getFQClassNameFromNewToken()` and `getFQClassNameFromDoubleColonToken()` utility methods would get confused when the class name was a variable instead of being hard-coded, resulting in a PHP warning being thown. [#206]. Fixes [#205].
- :bug: The `getFunctionCallParameters()` utility method would incorrectly identify an extra parameter if the last parameter passed to a function would have an - unnecessary - comma after it. The `getFunctionCallParameters()` utility method also did not handle parameters passed as short arrays correctly. [#213]. Fixes [#211].
- :umbrella: Unit tests for the `NewFunctionArrayDereferencing` sniff were not being run due to a naming error. [#208]
- :books: Readme: Information about setting the `testVersion` from a custom ruleset was incorrect. [#204]
- :wrench: Path to PHPCS in the unit tests breaking for non-Composer installs. [#198]

### Credits
Thanks go out to [Juliette Reinders Folmer] and [Yoshiaki Yoshida] for their contributions to this version. :clap:

[26d0b6]: https://github.com/PHPCompatibility/PHPCompatibility/commit/26d0b6cf0921f75d93a4faaf09c390f386dde9ff
[841616]: https://github.com/PHPCompatibility/PHPCompatibility/commit/8416162ea81f4067226324f5948f4a50f7958a9b

[#170]: https://github.com/PHPCompatibility/PHPCompatibility/pull/170
[#188]: https://github.com/PHPCompatibility/PHPCompatibility/pull/188
[#189]: https://github.com/PHPCompatibility/PHPCompatibility/pull/189
[#198]: https://github.com/PHPCompatibility/PHPCompatibility/pull/198
[#199]: https://github.com/PHPCompatibility/PHPCompatibility/pull/199
[#200]: https://github.com/PHPCompatibility/PHPCompatibility/pull/200
[#201]: https://github.com/PHPCompatibility/PHPCompatibility/pull/201
[#202]: https://github.com/PHPCompatibility/PHPCompatibility/pull/202
[#204]: https://github.com/PHPCompatibility/PHPCompatibility/pull/204
[#205]: https://github.com/PHPCompatibility/PHPCompatibility/issues/205
[#206]: https://github.com/PHPCompatibility/PHPCompatibility/pull/206
[#208]: https://github.com/PHPCompatibility/PHPCompatibility/pull/208
[#209]: https://github.com/PHPCompatibility/PHPCompatibility/pull/209
[#211]: https://github.com/PHPCompatibility/PHPCompatibility/issues/211
[#213]: https://github.com/PHPCompatibility/PHPCompatibility/pull/213
[#215]: https://github.com/PHPCompatibility/PHPCompatibility/pull/215
[#216]: https://github.com/PHPCompatibility/PHPCompatibility/pull/216
[#217]: https://github.com/PHPCompatibility/PHPCompatibility/pull/217


## [7.0.4] - 2016-08-20

See all related issues and PRs in the [7.0.4 milestone].

### Added
- :star2: New `EmptyNonVariable` sniff: detection of empty being used on non-variables for PHP < 5.5. [#187]
- :star2: New `NewMagicMethods` sniff: detection of declaration of magic methods before the method became "magic". Includes a check for the changed behaviour for the `__toString()` magic method in PHP 5.2. [#176]. Fixes [#64].
- :star2: New `RemovedAlternativePHPTags` sniff: detection of ASP and script open tags for which support was removed in PHP 7.0. [#184], [#193]. Fixes [#127].
- :star: `NonStaticMagicMethods` sniff: detection of the `__callStatic()`, `__sleep()`, `__toString()` and `__set_state()` magic methods.
- :green_heart: Lint all non-test case files for syntax errors during the build testing by Travis. [#192]

### Changed
- :pushpin: `NonStaticMagicMethods` sniff: will now also sniff `trait`s for magic methods. [#174]
- :pushpin: `NonStaticMagicMethods` sniff: will now also check for magic methods which should be declared as `static`. [#174]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#178], [#179], [#174], [#171]
- :recycle: The unit test suite now internally caches PHPCS run results in combination with a set `testVersion` to speed up the running of the unit tests. These are now ~3 x faster. [#148]
- :books: Readme: Minor clarification of the minimum requirements.
- :books: Readme: Advise to use the latest stable version of this repository.
- :wrench: The unit tests can now be run with PHPCS installed in an arbitrary location by passing the location through an environment option. [#191].
- :wrench: Improved coveralls configuration and compatibility. [#194]
- :green_heart: The sniffs are now also tested against PHP 5.2 for consistent results. Except for namespace, trait and group use related errors, most sniffs work as intended on PHP 5.2. [#196]

### Fixed
- :bug: The `ForbiddenBreakContinueVariableArguments` sniff would not report on `break`/`continue` with a closure as an argument. [#171]
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would not report on reserved keywords which were not lowercase. [#186]
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would not report on the `goto` and `namespace` keywords when run on PHP 5.2. [#193]
- :bug: `NewAnonymousClasses` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#195]
- :bug: `NewGroupUseDeclarations` sniff: the version check logic was reversed causing the error not to be reported in certain circumstances. [#190]
- :bug: The `NonStaticMagicMethods` sniff would not report on magic methods when the function name as declared was not in the same case as used in the PHP manual. [#174]
- :wrench: The unit tests would exit with `0` if PHPCS could not be found. [#191]
- :green_heart: The PHPCompatibility library itself was not fully compatible with PHP 5.2. [#193]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#64]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/64
[#127]: https://github.com/PHPCompatibility/PHPCompatibility/issues/127
[#148]: https://github.com/PHPCompatibility/PHPCompatibility/pull/148
[#171]: https://github.com/PHPCompatibility/PHPCompatibility/pull/171
[#174]: https://github.com/PHPCompatibility/PHPCompatibility/pull/174
[#176]: https://github.com/PHPCompatibility/PHPCompatibility/pull/176
[#178]: https://github.com/PHPCompatibility/PHPCompatibility/pull/178
[#179]: https://github.com/PHPCompatibility/PHPCompatibility/pull/179
[#184]: https://github.com/PHPCompatibility/PHPCompatibility/pull/184
[#186]: https://github.com/PHPCompatibility/PHPCompatibility/pull/186
[#187]: https://github.com/PHPCompatibility/PHPCompatibility/pull/187
[#190]: https://github.com/PHPCompatibility/PHPCompatibility/pull/190
[#191]: https://github.com/PHPCompatibility/PHPCompatibility/pull/191
[#192]: https://github.com/PHPCompatibility/PHPCompatibility/pull/192
[#193]: https://github.com/PHPCompatibility/PHPCompatibility/pull/193
[#194]: https://github.com/PHPCompatibility/PHPCompatibility/pull/194
[#195]: https://github.com/PHPCompatibility/PHPCompatibility/pull/195
[#196]: https://github.com/PHPCompatibility/PHPCompatibility/pull/196


## [7.0.3] - 2016-08-18

See all related issues and PRs in the [7.0.3 milestone].

### Added
- :star2: New `InternalInterfaces` sniff: detection of internal PHP interfaces being which should not be implemented by user land classes. [#144]
- :star2: New `LateStaticBinding` sniff: detection of PHP 5.3 late static binding. [#177]
- :star2: New `NewExecutionDirectives` sniff: verify execution directives set with `declare()`. [#169]
- :star2: New `NewInterfaces` sniff: detection of the use of newly introduced PHP native interfaces. This sniff will also detect unsupported methods when a class implements the `Serializable` interface. [#144]
- :star2: New `RequiredOptionalFunctionParameters` sniff: detection of missing function parameters which were required in earlier PHP versions only to become optional in later versions. [#165]
- :star2: New `ValidIntegers` sniff: detection of binary integers for PHP < 5.4, detection of hexademical numeric strings for which recognition as hex integers was removed in PHP 7.0, detection of invalid binary and octal integers. [#160]. Fixes [#55].
- :star: `DeprecatedExtensions` sniff: detect removal of the `ereg` extension in PHP 7. [#149]
- :star: `DeprecatedFunctions` sniff: detection of the PHP 5.0.5 deprecated `php_check_syntax()` and PHP 5.4 deprecated `mysqli_get_cache_stats()` functions. [#155].
- :star: `DeprecatedFunctions` sniff: detect deprecation of a number of the `mysqli` functions in PHP 5.3. [#149]
- :star: `DeprecatedFunctions` sniff: detect removal of the `call_user_method()`, `ldap_sort()`, `ereg_*()` and `mysql_*()` functions in PHP 7.0. [#149]
- :star: `DeprecatedIniDirectives` sniff: detection of a _lot_ more deprecated/removed ini directives. [#146]
- :star: `NewFunctionParameters` sniff: detection of a _lot_ more new function parameters. [#164]
- :star: `NewFunctions` sniff: detection of numerous extra new functions. [#161]
- :star: `NewIniDirectives` sniff: detection of a _lot_ more new ini directives. [#146]
- :star: `NewLanguageConstructs` sniff: detection of the PHP 5.6 ellipsis `...` construct. [#175]
- :star: `NewScalarTypeDeclarations` sniff: detection of PHP 5.1 `array` and PHP 5.4 `callable` type hints. [#168]
- :star: `RemovedFunctionParameters` sniff: detection of a few extra removed function parameters. [#163]
- :star: Detection of functions and methods with a double underscore prefix as these are reserved by PHP for future use. The existing upstream `Generic.NamingConventions.CamelCapsFunctionName` sniff is re-used for this with some customization. [#173]
- :star: New `getFQClassNameFromNewToken()`, `getFQExtendedClassName()`, `getFQClassNameFromDoubleColonToken()`, `getFQName()`, `isNamespaced()`, `determineNamespace()` and `getDeclaredNamespaceName()` utility methods in the `PHPCompatibility_Sniff` base class for namespace determination. [#162]
- :recycle: New `inClassScope()` utility method in the `PHPCompatibility_Sniff` base class. [#168]
- :recycle: New `doesFunctionCallHaveParameters()` and `getFunctionCallParameterCount()` utility methods in the `PHPCompatibility_Sniff` base class. [#153]
- :umbrella: Unit test for `__halt_compiler()` detection by the `NewKeywords` sniff.
- :umbrella: Unit tests for the `NewFunctions` sniff. [#161]
- :umbrella: Unit tests for the `ParameterShadowSuperGlobals` sniff. [#180]
- :wrench: Minimal config for Scrutinizer CI. [#145].

### Changed
- :pushpin: The `DeprecatedIniDirectives` and the `NewIniDirectives` sniffs will now indicate an alternative ini directive in case the directive has been renamed. [#146]
- :pushpin: The `NewClasses` sniff will now also report on new classes being extended by child classes. [#140].
- :pushpin: The `NewClasses` sniff will now also report on static use of new classes. [#162].
- :pushpin: The `NewScalarTypeDeclarations` sniff will now throw an error on use of type hints pre-PHP 5.0. [#168]
- :pushpin: The `NewScalarTypeDeclarations` sniff will now verify type hints used against typical mistakes. [#168]
- :pushpin: The `ParameterShadowSuperGlobals` sniff will now do a case-insensitive variable name compare. [#180]
- :pushpin: The `RemovedFunctionParameters` sniff will now also report `warning`s on deprecation of function parameters. [#163]
- :twisted_rightwards_arrows: The check for `JsonSerializable` has been moved from the `NewClasses` sniff to the `NewInterfaces` sniff. [#162]
- :rewind: The `NewLanguageConstructs` sniff will now also recognize new language constructs when used in combination with PHPCS 1.5.x. [#175]
- :pencil2: `NewFunctionParameters` sniff: use correct name for the new parameter for the `dirname()` function. [#164]
- :pencil2: `NewScalarTypeDeclarations` sniff: minor change in the sniff error message text. [#168]
- :pencil2: `RemovedFunctionParameters` sniff: minor change in the sniff error message text. [#163]
- :pencil2: The `ParameterShadowSuperGlobals` sniff now extends the `PHPCompatibility_Sniff` class. [#180]
- :recycle: Various (minor) refactoring for improved performance and sniff accuracy. [#181], [#182], [#166], [#167], [#172], [#180], [#146], [#138]
- :recycle: Various refactoring to remove code duplication in the unit tests and add proper test skip notifications where relevant. [#139], [#149]

### Fixed
- :bug: The `DeprecatedFunctions` sniff was reporting an incorrect deprecation/removal version number for a few functions. [#149]
- :bug: The `DeprecatedIniDirectives` sniff was in select cases reporting deprecation of an ini directive prior to removal, while the ini directive was never deprecated prior to its removal. [#146]
- :bug: The `DeprecatedPHP4StyleConstructors` sniff would cause false positives for methods with the same name as the class in namespaced classes. [#167]
- :bug: The `ForbiddenEmptyListAssignment` sniff did not report errors when there were only comments or parentheses between the list parentheses. [#166]
- :bug: The `ForbiddenEmptyListAssignment` sniff will no longer cause false positives during live coding. [#166]
- :bug: The `NewClasses` sniff would potentially misidentify namespaced classes as PHP native classes. [#162]
- :bug: The `NewFunctions` sniff would fail to identify called functions when the function call was not lowercase. [#161]
- :bug: The `NewFunctions` sniff would potentially misidentify namespaced userland functions as new functions. [#161]
- :bug: The `NewIniDirectives` sniff was reporting an incorrect introduction version number for a few ini directives. [#146]
- :bug: `NewKeywords` sniff: the use of the `const` keyword should only be reported when used outside of a class for PHP < 5.3. [#147]. Fixes [#129].
- :bug: The `RemovedExtensions` sniff was incorrectly reporting a number of extensions as being removed in PHP 5.3 while they were actually removed in PHP 5.1. [#156]
- :bug: :recycle: The `NewFunctionParameters` and `RemovedFunctionParameters` now use the new `doesFunctionCallHaveParameters()` and `getFunctionCallParameterCount()` utility methods for improved accuracy in identifying function parameters. This fixes several false positives. [#153] Fixes [#120], [#151], [#152].
- :bug: A number of sniffs would return `false` if the examined construct was not found. This could potentially cause race conditions/infinite sniff loops. [#138]
- :wrench: The unit tests would fail to run when used in combination with a PEAR install of PHPCS. [#157].
- :green_heart: Unit tests failing against PHPCS 2.6.1. [#158]
    The unit tests _will_ still fail against PHPCS 2.6.2 due to a bug in PHPCS itself. This bug does not affect the running of the sniffs outside of a unit test context.

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#55]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/55
[#120]: https://github.com/PHPCompatibility/PHPCompatibility/issues/120
[#129]: https://github.com/PHPCompatibility/PHPCompatibility/issues/129
[#138]: https://github.com/PHPCompatibility/PHPCompatibility/pull/138
[#139]: https://github.com/PHPCompatibility/PHPCompatibility/pull/139
[#140]: https://github.com/PHPCompatibility/PHPCompatibility/pull/140
[#144]: https://github.com/PHPCompatibility/PHPCompatibility/pull/144
[#145]: https://github.com/PHPCompatibility/PHPCompatibility/pull/145
[#146]: https://github.com/PHPCompatibility/PHPCompatibility/pull/146
[#147]: https://github.com/PHPCompatibility/PHPCompatibility/pull/147
[#149]: https://github.com/PHPCompatibility/PHPCompatibility/pull/149
[#151]: https://github.com/PHPCompatibility/PHPCompatibility/issues/151
[#152]: https://github.com/PHPCompatibility/PHPCompatibility/issues/152
[#153]: https://github.com/PHPCompatibility/PHPCompatibility/pull/153
[#155]: https://github.com/PHPCompatibility/PHPCompatibility/pull/155
[#156]: https://github.com/PHPCompatibility/PHPCompatibility/pull/156
[#157]: https://github.com/PHPCompatibility/PHPCompatibility/pull/157.
[#158]: https://github.com/PHPCompatibility/PHPCompatibility/pull/158
[#160]: https://github.com/PHPCompatibility/PHPCompatibility/pull/160
[#161]: https://github.com/PHPCompatibility/PHPCompatibility/pull/161
[#162]: https://github.com/PHPCompatibility/PHPCompatibility/pull/162
[#163]: https://github.com/PHPCompatibility/PHPCompatibility/pull/163
[#164]: https://github.com/PHPCompatibility/PHPCompatibility/pull/164
[#165]: https://github.com/PHPCompatibility/PHPCompatibility/pull/165
[#166]: https://github.com/PHPCompatibility/PHPCompatibility/pull/166
[#167]: https://github.com/PHPCompatibility/PHPCompatibility/pull/167
[#168]: https://github.com/PHPCompatibility/PHPCompatibility/pull/168
[#169]: https://github.com/PHPCompatibility/PHPCompatibility/pull/169
[#172]: https://github.com/PHPCompatibility/PHPCompatibility/pull/172
[#173]: https://github.com/PHPCompatibility/PHPCompatibility/pull/173
[#175]: https://github.com/PHPCompatibility/PHPCompatibility/pull/175
[#177]: https://github.com/PHPCompatibility/PHPCompatibility/pull/177
[#180]: https://github.com/PHPCompatibility/PHPCompatibility/pull/180
[#181]: https://github.com/PHPCompatibility/PHPCompatibility/pull/181
[#182]: https://github.com/PHPCompatibility/PHPCompatibility/pull/182


## [7.0.2] - 2016-08-03

See all related issues and PRs in the [7.0.2 milestone].

### Added
- :star: `RemovedExtensions` sniff: ability to whitelist userland functions for which the function prefix overlaps with a prefix of a deprecated/removed extension. [#130]. Fixes [#123].
    To use this feature, add the `functionWhitelist` property in your custom ruleset. For more information, see the [README][README-options].

### Changed
- :pencil2: A number of sniffs contained `public` class properties. Within PHPCS, `public` properties can be overruled via a custom ruleset. This was not the intention, so the visibility of these properties has been changed to `protected`. [#135]
- :wrench: Composer config: Stable packages are preferred over unstable/dev.
- :pencil2: Ruleset name. [#134]

### Credits
Thanks go out to [Juliette Reinders Folmer] for her contributions to this version. :clap:

[#123]: https://github.com/PHPCompatibility/PHPCompatibility/issues/123
[#130]: https://github.com/PHPCompatibility/PHPCompatibility/pull/130
[#134]: https://github.com/PHPCompatibility/PHPCompatibility/pull/134
[#135]: https://github.com/PHPCompatibility/PHPCompatibility/pull/135


## [7.0.1] - 2016-08-02

See all related issues and PRs in the [7.0.1 milestone].

### Changed
- :pushpin: The `DeprecatedIniDirectives` sniff used to throw an `error` when a deprecated ini directive was used in combination with `ini_get()`. It will now throw a `warning` instead. [#122] Fixes [#119].
    Usage of deprecated ini directives in combination with `ini_set()` will still throw an `error`.
- :pushpin: The `PregReplaceEModifier` sniff now also detects the `e` modifier when used with the `preg_filter()` function. While this is undocumented, the `e` modifier was supported by the `preg_filter()` function as well. [#128]
- :pencil2: The `RemovedExtensions` sniff contained superfluous deprecation information in the error message. [#131]

### Removed
- :wrench: Duplicate builds from the Travis CI build matrix. [#132]

### Fixed
- :bug: The `ForbiddenNames` sniff did not allow for the PHP 5.6 `use function ...` and `use const ...` syntax. [#126] Fixes [#124].
- :bug: The `NewClasses` sniff failed to detect new classes when the class was instantiated without parenthesis, i.e. `new NewClass;`. [#121]
- :bug: The `PregReplaceEModifier` sniff failed to detect the `e` modifier when using bracket delimiters for the regex other than the `{}` brackets. [#128]
- :green_heart: Unit tests failing against PHPCS 2.6.1.

### Credits
Thanks go out to [Jason Stallings], [Juliette Reinders Folmer] and [Ryan Neufeld] for their contributions to this version. :clap:

[#119]: https://github.com/PHPCompatibility/PHPCompatibility/issues/119
[#121]: https://github.com/PHPCompatibility/PHPCompatibility/pull/121
[#122]: https://github.com/PHPCompatibility/PHPCompatibility/pull/122
[#124]: https://github.com/PHPCompatibility/PHPCompatibility/issues/124
[#126]: https://github.com/PHPCompatibility/PHPCompatibility/pull/126
[#128]: https://github.com/PHPCompatibility/PHPCompatibility/pull/128
[#131]: https://github.com/PHPCompatibility/PHPCompatibility/pull/131
[#132]: https://github.com/PHPCompatibility/PHPCompatibility/pull/132


## [7.0] - 2016-07-02

See all related issues and PRs in the [7.0 milestone].

### Added
- :zap: Ability to specify a range of PHP versions against which to test your code base for compatibility, i.e. `--runtime-set testVersion 5.0-5.4` will now test your code for compatibility with PHP 5.0 up to PHP 5.4. [#99]
- :star2: New `NewFunctionArrayDereferencing` sniff to detect function array dereferencing as introduced in PHP 5.4. Fixes [#52].
- :star2: New `ShortArray` sniff to detect short array syntax as introduced in PHP 5.4. [#97]. Fixes [#47].
- :star2: New `TernaryOperators` sniff to detect ternaries without the middle part (`elvis` operator) as introduced in PHP 5.3. [#101], [#103]. Fixes [#49].
- :star2: New `ConstantArraysUsingDefine` sniff to detect constants declared using `define()` being assigned an `array` value which was not allowed prior to PHP 7.0. [#110]
- :star2: New `DeprecatedPHP4StyleConstructors` sniff to detect PHP 4 style class constructor methods which are deprecated as of PHP 7. [#109].
- :star2: New `ForbiddenEmptyListAssignment` sniff to detect empty list() assignments which have been removed in PHP 7.0. [#110]
- :star2: New `ForbiddenFunctionParametersWithSameName` sniff to detect functions declared with multiple same-named parameters which is no longer accepted since PHP 7.0. [#110]
- :star2: New `ForbiddenGlobalVariableVariable` sniff to detect variable variables being made `global` which is not allowed since PHP 7.0. [#110]
- :star2: New `ForbiddenNegativeBitshift` sniff to detect bitwise shifts by negative number which will throw an ArithmeticError in PHP 7.0. [#110]
- :star2: New `ForbiddenSwitchWithMultipleDefaultBlocks` sniff to detect switch statements with multiple default blocks which is not allowed since PHP 7.0. [#110]
- :star2: New `NewAnonymousClasses` sniff to detect anonymous classes as introduced in PHP 7.0. [#110]
- :star2: New `NewClosure` sniff to detect anonymous functions as introduced in PHP 5.3. Fixes [#35]
- :star2: New `NewFunctionParameters` sniff to detect use of new parameters in build-in PHP functions. Initially only sniffing for the new PHP 7.0 function parameters and the new PHP 5.3+ `before_needle` parameter for the `strstr()` function. [#110], [#112]. Fixes [#27].
- :star2: New `NewGroupUseDeclarations` sniff to detect group use declarations as introduced in PHP 7.0. [#110]
- :star2: New `NewScalarReturnTypeDeclarations` sniff to detect scalar return type hints as introduced in PHP 7.0. [#110]
- :star2: New `NewScalarTypeDeclarations` sniff to detect scalar function parameter type hints as introduced in PHP 7.0. [#110]
- :star2: New `RemovedFunctionParameters` sniff to detect use of removed parameters in build-in PHP functions. Initially only sniffing for the function parameters removed in PHP 7.0. [#110]
- :star2: New `RemovedGlobalVariables` sniff to detect the PHP 7.0 removed `$HTTP_RAW_POST_DATA` superglobal. [#110]
- :star: `DeprecatedFunctions` sniff: detection of the PHP 5.4 deprecated OCI8 functions. [#93]
- :star: `ForbiddenNamesAsInvokedFunctions` sniff: recognize PHP 5.5 `finally` as a reserved keywords when invoked as a function. [#110]
- :star: `NewKeywords` sniff: detection of the use of the PHP 5.1+ `__halt_compiler` keyword. Fixes [#50].
- :star: `NewKeywords` sniff: detection of the PHP 5.3+ `nowdoc` syntax. Fixes [#48].
- :star: `NewKeywords` sniff: detection of the use of the `const` keyword outside of a class for PHP < 5.3. Fixes [#50].
- :star: `DeprecatedFunctions` sniff: recognize PHP 7.0 deprecated and removed functions. [#110]
- :star: `DeprecatedIniDirectives` sniff: recognize PHP 7.0 removed ini directives. [#110]
- :star: `ForbiddenNamesAsInvokedFunctions` sniff: recognize new PHP 7.0 reserved keywords when invoked as functions. [#110]
- :star: `ForbiddenNames` sniff: recognize new PHP 7.0 reserved keywords. [#110]
- :star: `NewFunctions` sniff: recognize new functions as introduced in PHP 7.0. [#110]
- :star: `NewLanguageConstructs` sniff: recognize new PHP 7.0 `<=>` "spaceship" and `??` null coalescing operators. [#110]
- :star: `RemovedExtensions` sniff: recognize PHP 7.0 removed `ereg`, `mssql`, `mysql` and `sybase_ct` extensions. [#110]
- :umbrella: Additional unit tests for the `NewLanguageConstructs` sniff. [#110]
- :books: Readme: New section containing information about the use of the `testVersion` config variable.
- :books: Readme: Sponsor credits.

### Changed
- :pushpin: The `DeprecatedIniDirectives` sniff used to always throw an `warning`. Now it will throw an `error` when a removed ini directive is used. [#110]
- :pushpin: The `DeprecatedNewReference` sniff will now throw an error when the `testVersion` includes PHP 7.0 or higher. [#110]
- :pushpin: The `ForbiddenNames` sniff now supports detection of reserved keywords when used in combination with PHP 7 anonymous classes. [#108], [#110].
- :pushpin: The `PregReplaceEModifier` sniff will now throw an error when the `testVersion` includes PHP 7.0 or higher. [#110]
- :pencil2: `NewKeywords` sniff: clarified the error message text for the `use` keyword. Fixes [#46].
- :recycle: Minor refactor of the `testVersion` related utility functions. [#98]
- :wrench: Add autoload to the `composer.json` file. [#96] Fixes [#67].
- :wrench: Minor other updates to the `composer.json` file. [#75]
- :wrench: Improved creation of the code coverage reports needed by coveralls via Travis.
- :green_heart: The sniffs are now also tested against PHP 7.0 for consistent results.

### Fixed
- :bug: The `ForbiddenCallTimePassByReference` sniff was throwing `Undefined index` notices when used in combination with PHPCS 2.2.0. [#100]. Fixes [#42].
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff would incorrectly throw an error if the `throw` keyword was used with parenthesis. Fixes [#118].
- :bug: The `PregReplaceEModifier` sniff incorrectly identified `e`'s in the pattern as the `e` modifier when using `{}` bracket delimiters for the regex. [#94]
- :bug: The `RemovedExtensions` sniff was throwing an `error` instead of a `warning` for deprecated, but not (yet) removed extensions. Fixes [#62].

### Credits
Thanks go out to AlexMiroshnikov, [Chris Abernethy], [dgudgeon], [djaenecke], [Eugene Maslovich], [Ken Guest], Koen Eelen, [Komarov Alexey], [Mark Clements] and [Remko van Bezooijen] for their contributions to this version. :clap:

[#27]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/27
[#35]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/35
[#42]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/42
[#46]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/46
[#47]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/47
[#48]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/48
[#49]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/49
[#50]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/50
[#52]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/52
[#62]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/62
[#67]:  https://github.com/PHPCompatibility/PHPCompatibility/issues/67
[#75]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/75
[#93]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/93
[#94]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/94
[#96]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/96
[#97]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/97
[#98]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/98
[#99]:  https://github.com/PHPCompatibility/PHPCompatibility/pull/99
[#100]: https://github.com/PHPCompatibility/PHPCompatibility/pull/100
[#101]: https://github.com/PHPCompatibility/PHPCompatibility/pull/101
[#103]: https://github.com/PHPCompatibility/PHPCompatibility/pull/103
[#108]: https://github.com/PHPCompatibility/PHPCompatibility/pull/108
[#109]: https://github.com/PHPCompatibility/PHPCompatibility/pull/109
[#110]: https://github.com/PHPCompatibility/PHPCompatibility/pull/110
[#112]: https://github.com/PHPCompatibility/PHPCompatibility/pull/112
[#118]: https://github.com/PHPCompatibility/PHPCompatibility/issues/118


## [5.6] - 2015-09-14

See all related issues and PRs in the [5.6 milestone].

### Added
- :star2: New: `NewLanguageConstructs` sniff. The initial version of this sniff checks for the PHP 5.6 `**` power operator and the `**=` power assignment operator. [#87]. Fixes [#60].
- :star2: New: `ParameterShadowSuperGlobals` sniff which covers the PHP 5.4 change _Parameter names that shadow super globals now cause a fatal error.`_. [#74]
- :star2: New: `PregReplaceEModifier` sniff which detects usage of the `e` modifier in literal regular expressions used with `preg_replace()`. The `e` modifier will not (yet) be detected when the regex passed is a variable or constant. [#81], [#84]. Fixes [#71], [#83].
- :star: `DeprecatedIniDirectives` sniff: PHP 5.6 deprecated ini directives.
- :star: `NewKeywords` sniff: detection of the `goto` keyword introduced in PHP 5.3 and the `callable` keyword introduced in PHP 5.4. [#57]
- :recycle: `PHPCompatibility_Sniff` base class initially containing the `supportsAbove()` and `supportsBelow()` utility methods. (Nearly) All sniffs now extend this base class and use these methods to determine whether or not violations should be reported for a set `testVersion`. [#77]
- :books: Readme: Composer installation instructions. [#32], [#61]
- :wrench: `.gitignore` to ignore vendor and IDE related directories. [#78]
- :green_heart: Code coverage checking via coveralls.

### Changed
- :twisted_rightwards_arrows: The check for the `\` namespace separator has been moved from the `NewKeywords` sniff to the `NewLanguageConstructs` sniff. [#88]
- :pencil2: `DeprecatedIniDirectives` sniff: minor change in the sniff error message text.
- :pencil2: `DeprecatedFunctions` sniff: minor change in the sniff error message text.
- :wrench: Minor updates to the `composer.json` file. [#31], [#34], [#70]
- :wrench: Tests: The unit tests can now be run without configuration.
- :wrench: Tests: Skipped unit tests will now be annotated as such. [#85]
- :green_heart: The sniffs are now also tested against PHP 5.6 for consistent results.
- :green_heart: The sniffs are now also tested against PHPCS 2.0+.
- :green_heart: The sniffs are now tested using the new container-based infrastructure in Travis CI. [#37]

### Fixed
- :bug: The `ForbiddenCallTimePassByReference` sniff was throwing false positives when a bitwise and `&` was used in combination with class constants and class properties within function calls. [#44]. Partially fixes [#39].
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff was throwing false positives in certain cases when a comment separated a `try` from the `catch` block. [#29]
- :bug: The `ForbiddenNamesAsInvokedFunctions` sniff was incorrectly reporting `instanceof` as being introduced in PHP 5.4 while it has been around since PHP 5.0. [#80]
- :white_check_mark: Compatibility with PHPCS 2.0 - 2.3. [#63], [#65]

### Credits
Thanks go out to Daniel Jänecke, [Declan Kelly], [Dominic], [Jaap van Otterdijk], [Marin Crnkovic], [Mark Clements], [Nick Pack], [Oliver Klee], [Rowan Collins] and [Sam Van der Borght] for their contributions to this version. :clap:

[#29]: https://github.com/PHPCompatibility/PHPCompatibility/pull/29
[#31]: https://github.com/PHPCompatibility/PHPCompatibility/pull/31
[#32]: https://github.com/PHPCompatibility/PHPCompatibility/pull/32
[#34]: https://github.com/PHPCompatibility/PHPCompatibility/pull/34
[#37]: https://github.com/PHPCompatibility/PHPCompatibility/pull/37
[#39]: https://github.com/PHPCompatibility/PHPCompatibility/issues/39
[#44]: https://github.com/PHPCompatibility/PHPCompatibility/pull/44
[#57]: https://github.com/PHPCompatibility/PHPCompatibility/pull/57
[#60]: https://github.com/PHPCompatibility/PHPCompatibility/issues/60
[#61]: https://github.com/PHPCompatibility/PHPCompatibility/pull/61
[#63]: https://github.com/PHPCompatibility/PHPCompatibility/pull/63
[#65]: https://github.com/PHPCompatibility/PHPCompatibility/pull/65
[#70]: https://github.com/PHPCompatibility/PHPCompatibility/pull/70
[#71]: https://github.com/PHPCompatibility/PHPCompatibility/issues/71
[#74]: https://github.com/PHPCompatibility/PHPCompatibility/pull/74
[#77]: https://github.com/PHPCompatibility/PHPCompatibility/pull/77
[#78]: https://github.com/PHPCompatibility/PHPCompatibility/pull/78
[#80]: https://github.com/PHPCompatibility/PHPCompatibility/pull/80
[#81]: https://github.com/PHPCompatibility/PHPCompatibility/pull/81
[#83]: https://github.com/PHPCompatibility/PHPCompatibility/issues/83
[#84]: https://github.com/PHPCompatibility/PHPCompatibility/pull/84
[#85]: https://github.com/PHPCompatibility/PHPCompatibility/pull/85
[#87]: https://github.com/PHPCompatibility/PHPCompatibility/pull/87
[#88]: https://github.com/PHPCompatibility/PHPCompatibility/pull/88


## 5.5 - 2014-04-04

First tagged release.

See all related issues and PRs in the [5.5 milestone].


[README-options]:                     https://github.com/PHPCompatibility/PHPCompatibility#phpcompatibility-specific-options
[README-using-the-sniffs]:            https://github.com/PHPCompatibility/PHPCompatibility#using-the-compatibility-sniffs
[README-framework-rulesets]:          https://github.com/PHPCompatibility/PHPCompatibility#using-a-frameworkcms-specific-ruleset
[CONTRIBUTING-framework-rulesets]:    https://github.com/PHPCompatibility/PHPCompatibility/blob/master/.github/CONTRIBUTING.md#frameworkcms-specific-rulesets
[gh-phpcompat-all]:                   https://github.com/PHPCompatibility/PHPCompatibilityAll
[gh-phpcompat-joomla]:                https://github.com/PHPCompatibility/PHPCompatibilityJoomla
[gh-phpcompat-paragonie]:             https://github.com/PHPCompatibility/PHPCompatibilityParagonie
[gh-phpcompat-passwordcompat]:        https://github.com/PHPCompatibility/PHPCompatibilityPasswordCompat
[gh-phpcompat-symfony]:               https://github.com/PHPCompatibility/PHPCompatibilitySymfony
[gh-phpcompat-wp]:                    https://github.com/PHPCompatibility/PHPCompatibilityWP
[packagist-phpcompat-all]:            https://packagist.org/packages/phpcompatibility/phpcompatibility-all
[packagist-phpcompat-joomla]:         https://packagist.org/packages/phpcompatibility/phpcompatibility-joomla
[packagist-phpcompat-paragonie]:      https://packagist.org/packages/phpcompatibility/phpcompatibility-paragonie
[packagist-phpcompat-passwordcompat]: https://packagist.org/packages/phpcompatibility/phpcompatibility-passwordcompat
[packagist-phpcompat-symfony]:        https://packagist.org/packages/phpcompatibility/phpcompatibility-symfony
[packagist-phpcompat-wp]:             https://packagist.org/packages/phpcompatibility/phpcompatibility-wp
[phpcsutils]:                         https://phpcsutils.com/


[Unreleased]:    https://github.com/PHPCompatibility/PHPCompatibility/compare/master...HEAD
[10.0.0-alpha2]: https://github.com/PHPCompatibility/PHPCompatibility/compare/10.0.0-alpha1...10.0.0-alpha2
[10.0.0-alpha1]: https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.5...10.0.0-alpha1
[9.3.5]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.4...9.3.5
[9.3.4]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.3...9.3.4
[9.3.3]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.2...9.3.3
[9.3.2]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.1...9.3.2
[9.3.1]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.3.0...9.3.1
[9.3.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.2.0...9.3.0
[9.2.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.1.1...9.2.0
[9.1.1]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.1.0...9.1.1
[9.1.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/9.0.0...9.1.0
[9.0.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/8.2.0...9.0.0
[8.2.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/8.1.0...8.2.0
[8.1.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/8.0.1...8.1.0
[8.0.1]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/8.0.0...8.0.1
[8.0.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.5...8.0.0
[7.1.5]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.4...7.1.5
[7.1.4]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.3...7.1.4
[7.1.3]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.2...7.1.3
[7.1.2]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.1...7.1.2
[7.1.1]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.1.0...7.1.1
[7.1.0]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.8...7.1.0
[7.0.8]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.7...7.0.8
[7.0.7]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.6...7.0.7
[7.0.6]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.5...7.0.6
[7.0.5]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.4...7.0.5
[7.0.4]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.3...7.0.4
[7.0.3]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.2...7.0.3
[7.0.2]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0.1...7.0.2
[7.0.1]:         https://github.com/PHPCompatibility/PHPCompatibility/compare/7.0...7.0.1
[7.0]:           https://github.com/PHPCompatibility/PHPCompatibility/compare/5.6...7.0
[5.6]:           https://github.com/PHPCompatibility/PHPCompatibility/compare/5.5...5.6

[10.0.0-alpha2 milestone]: https://github.com/PHPCompatibility/PHPCompatibility/milestone/35
[10.0.0-alpha1 milestone]: https://github.com/PHPCompatibility/PHPCompatibility/milestone/26
[9.3.5 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/34
[9.3.4 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/33
[9.3.3 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/32
[9.3.2 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/31
[9.3.1 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/30
[9.3.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/29
[9.2.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/28
[9.1.1 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/27
[9.1.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/25
[9.0.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/24
[8.2.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/22
[8.1.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/21
[8.0.1 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/20
[8.0.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/19
[7.1.5 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/17
[7.1.4 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/15
[7.1.3 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/14
[7.1.2 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/13
[7.1.1 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/12
[7.1.0 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/11
[7.0.8 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/10
[7.0.7 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/9
[7.0.6 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/8
[7.0.5 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/7
[7.0.4 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/6
[7.0.3 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/5
[7.0.2 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/4
[7.0.1 milestone]:         https://github.com/PHPCompatibility/PHPCompatibility/milestone/3
[7.0 milestone]:           https://github.com/PHPCompatibility/PHPCompatibility/milestone/2
[5.6 milestone]:           https://github.com/PHPCompatibility/PHPCompatibility/milestone/1
[5.5 milestone]:           https://github.com/PHPCompatibility/PHPCompatibility/milestone/16

[Anna Filina]:              https://github.com/afilina
[Arthur Edamov]:            https://github.com/edamov
[bebehr]:                   https://github.com/bebehr
[Chris Abernethy]:          https://github.com/cabernet-zerve
[Dan Wallis]:               https://github.com/fredden
[Daniel Fahlke]:            https://github.com/Flyingmana
[Declan Kelly]:             https://github.com/declank
[dgudgeon]:                 https://github.com/dgudgeon
[Diede Exterkate]:          https://github.com/diedexx
[djaenecke]:                https://github.com/djaenecke
[Dominic]:                  https://github.com/dol
[Eloy Lafuente]:            https://github.com/stronk7
[Eugene Maslovich]:         https://github.com/ehpc
[Gary Jones]:               https://github.com/GaryJones
[Go Kudo]:                  https://github.com/zeriyoshi
[Hugo van Kemenade]:        https://github.com/hugovk
[Jaap van Otterdijk]:       https://github.com/jaapio
[Jason Stallings]:          https://github.com/octalmage
[Jonathan Champ]:           https://github.com/jrchamp
[Jonathan Van Belle]:       https://github.com/Grummfy
[Juliette Reinders Folmer]: https://github.com/jrfnl
[Ken Guest]:                https://github.com/kenguest
[Kevin Porras]:             https://github.com/kporras07
[Komarov Alexey]:           https://github.com/erdraug
[magikstm]:                 https://github.com/magikstm
[Marin Crnkovic]:           https://github.com/anorgan
[Mark Clements]:            https://github.com/MarkMaldaba
[Matthew Turland]:          https://github.com/elazar
[Michael Babker]:           https://github.com/mbabker
[Nick Pack]:                https://github.com/nickpack
[Nikhil]:                   https://github.com/Nikschavan
[Oliver Klee]:              https://github.com/oliverklee
[Remko van Bezooijen]:      https://github.com/emkookmer
[Rowan Collins]:            https://github.com/IMSoP
[Ryan Neufeld]:             https://github.com/ryanneufeld
[Sam Van der Borght]:       https://github.com/samvdb
[Sebastian Knott]:          https://github.com/rdss-sknott
[Sergii Bondarenko]:        https://github.com/BR0kEN-
[Shota Okunaka]:            https://github.com/okkun-sh
[Steve Grunwell]:           https://github.com/stevegrunwell
[Tadas Juozapaitis]:        https://github.com/kasp3r
[Tim Millwood]:             https://github.com/timmillwood
[William Entriken]:         https://github.com/fulldecent
[Yılmaz]:                   https://github.com/edigu
[Yoshiaki Yoshida]:         https://github.com/kakakakakku
