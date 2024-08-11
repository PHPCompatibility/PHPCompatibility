<?php

// Not our target.
$obj->xml_set_default_handler($parser, '');
MyClass::xml_set_default_handler($parser, '');
My\Ns\xml_set_default_handler($parser, '');

// Ignore, required param(s) missing, but that's not our concern.
xml_set_notation_decl_handler();
xml_set_end_namespace_decl_handler($parser);
xml_set_external_entity_ref_handler(handlers: $handlerCallback, parser: $parser); // Typo in param name.

// OK, valid callbacks.
xml_set_character_data_handler($parser, 'callback_function');
xml_set_element_handler($parser, [$this, 'method'], array('Class', 'method'));
xml_set_start_namespace_decl_handler($parser, first_class_callable(...));
xml_set_notation_decl_handler($parser, '' . 'trim');
xml_set_notation_decl_handler($parser, 'trim' . '');

xml_set_object($parser, $object);
xml_set_default_handler($parser, 'callback_method_on_object'); // Well, not really, but that's not the concern of this sniff.

\xml_set_unparsed_entity_decl_handler(
    $parser,
    function(XMLParser $parser, string $data): void {
        do_something();
    }
);

// Valid unset.
xml_set_processing_instruction_handler($parser, null);

// Undetermined. Ignore.
xml_set_character_data_handler($parser, $invokableObject);
xml_set_default_handler($parser, CALLBACK_IN_CONSTANT);
xml_set_element_handler($parser, getCallBack(), $obj->callbackInProperty);

// Not OK - deprecated in PHP >= 8.4.
xml_set_character_data_handler($parser, '');
XML_Set_Default_Handler($parser, /*comment*/''           /*comment*/);
\xml_set_end_namespace_decl_handler(handler: '', parser: $parser);
xml_set_element_handler(
    $parser,
    '',
    '',
);

xml_set_notation_decl_handler($parser, <<<EOD
EOD
);
xml_set_processing_instruction_handler($parser, <<<'EOD'

EOD
);

// The below are of a slightly different nature.
// In these cases, PHP doesn't throw the deprecation notice for passing a non-callable (empty string),
// but actually interprets this as a callable string, then still throws the deprecation notice for
// the string not being a valid callable and then also throws a Fatal Error on the (non-)"callback"
// for the method lookup on an xml_set_object passed object failing.
// As this is exactly what the change in PHP 8.4 is about, IMO it is fine for the sniff to flag these,
// even though we do not do any tracing of whether a callable is actually callable.
// In these specific cases, we can still be sure that the resulting "callable" will never be a valid
// callable, so let's flag them.
xml_set_external_entity_ref_handler($parser, handler: '' . '  ');
xml_set_start_namespace_decl_handler($parser, "
");
xml_set_unparsed_entity_decl_handler($parser, '    ');
xml_set_start_namespace_decl_handler($parser, <<<'EOD'




EOD
);
