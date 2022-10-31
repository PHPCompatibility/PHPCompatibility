<?php

// OK.
echo idn_to_ascii('täst.de', idna_info: $idna_info, variant: $variant);
echo idn_to_ascii( $domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
echo idn_to_utf8('xn--tst-qla.de', $options, $variant, $idna_info);
echo idn_to_utf8( $domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_2003 );

// Cross-version changed default.
echo idn_to_ascii('täst.de');
echo idn_to_ascii( $domain, IDNA_DEFAULT);
echo IDN_to_utf8('xn--tst-qla.de');
echo idn_to_utf8( $domain, IDNA_DEFAULT);
echo idn_to_ascii(idna_info: $idna_info, domain: 'täst.de');
echo idn_to_utf8( flags: IDNA_DEFAULT, domain: $domain);
