<?php

/*
 * Not what we're looking for.
 */
class DoesNotImplementAnyInterface {}

class DoesNotImplementSerializable implements SomeInterface, SomeOtherInterface {}

class DoesNotImplementPHPNativeSerializable implements \My\Custom\Serializable {}

interface DoesNotExtendAnything {}

interface DoesNotExtendSerializable extends SomeInterface, SomeOtherInterface {}

/*
 * This is okay for PHP 7.4 and higher only (not the concern of this sniff).
 */
class OnlyMagicMethods {
    public function __serialize() {
        return $this->data;
    }
    public function __unserialize($data) {
        $this->data = $data;
    }
}

// Ignore. Whether the required methods are implemented cannot be determined as the class is abstract.
// Also: PHP itself does not regard abstract classes implementing Serializable as "Only Serializable".
abstract class AbstractImplementingSerializable implements Serializable {}


/*
 * This is okay cross-version.
 * These implementations can remove the Serializable implementation when the minimum PHP version is 7.4 or higher.
 */

// Includes testing with extends and multiple implemented interfaces.
class ImplementingSerializableANDMagicMethods extends ArrayIterator implements Iterator, Serializable, ArrayAccess { // Redundant warning with PHP 7.4+.
    public function serialize() {
        return serialize($this->data);
    }
    public function unserialize( $data) {
        $this->data = unserialize($data);
    }
    public function __serialize() {
        return $this->data;
    }
    public function __unserialize($data) {
        $this->data = $data;
    }
}

$anon = new class() extends ArrayIterator implements Iterator, \Serializable, ArrayAccess { // Redundant warning with PHP 7.4+.
    public function serialize() {}
    public function unserialize( $data) {}
    public function __serialize() {}
    public function __unserialize($data) {}
};

class ImplementingSerializableANDMagicMethodsDifferentOrder implements SERIALIZABLE { // Redundant warning with PHP 7.4+.
    public function __Serialize() {}
    public function __UnSerialize($data) {}
    public function Serialize() {}
    public function Unserialize( $data) {}
}

// Parse error, class should be abstract, but that's not our concern.
class HandleAbstractMagicMethods implements Serializable { // Redundant warning with PHP 7.4+.
    abstract public function __serialize();
    abstract public function __unserialize($data);
}

/*
 * Test warning that interface needs to be added to the list of interfaces to scan for,
 * as well as flagging redundant Serializable implementation
 */
interface SerializableExtendedInterface extends Iterator, Serializable, ArrayAccess { // Missing interface warning + redundant warning with PHP 7.4+.
    public function __serialize();
    public function __unserialize($data);
}


/*
 * PHP 8.1: Implementing Serializable without also implementing the magic methods is deprecated.
 */

// Includes testing with extends and multiple implemented interfaces.
class OnlySerializable extends ArrayIterator implements Iterator, Serializable, ArrayAccess { // Deprecation warning.
    public function serialize() {
        return serialize($this->data);
    }
    public function unserialize($data) {
        $this->data = unserialize($data);
    }
}

$anon = new class extends ArrayIterator implements Iterator, \Serializable, ArrayAccess { // Deprecation warning.
    public function serialize() {}
    public function unserialize($data) {}
};

class OnlySerializableOnlySerializeMagic implements serializable { // Deprecation warning.
    public function serialize() {}
    public function unserialize($data) {}
    abstract public function __Serialize();
}

class OnlySerializableOnlyUnserializeMagic implements \Serializable { // Deprecation warning.
    public function serialize() {}
    public function unserialize($data) {}
    public function __unSerialize();
}

// Simple test of the docblock skipping code.
class SkipOverDocblocks implements Serializable, ArrayAccess{ // Deprecation warning.
    /**
     * This
     * docblock
     * should
     * be
     * skipped
     * over.
     */
    public function serialize() {}

    /**
     * This
     * docblock
     * should
     * be
     * skipped over.
     */
    #[AttributeWhichCouldBeLong, ShouldBeSkippedOver( 10, self::CONST_VALUE)]
    public function unserialize( $data) {}
}


// Test handling of property to search for additional interfaces.
// @codingStandardsChangeSetting PHPCompatibility.Interfaces.RemovedSerializable serializableInterfaces SerializableInterface,ThisInterfaceIsInTheList
interface ThisInterfaceisinTheList extends Serializable {}

interface ThisInterfaceIsNOTInTheList extends ArrayAcces, \Serializable {} // Missing interface warning.

interface ThisInterfaceIsNOTInTheListAndExtendOneInTheList extends \ThisInterfaceisinTheList { // Missing interface warning via user provided, NO redundant warning.
    public function __serialize();
    public function __unserialize($data);
}

interface ThisInterfaceIsNOTInTheListAndExtendCollected extends \ThisInterfaceIsNOTInTheList {} // Missing interface warning via collected.

class OnlySerializableViaExtendedInterface extends ArrayIterator implements SerializableInterface { // Deprecation warning via user provided.
    public function serialize() {}
    public function unserialize( $data) {}
}

class OnlySerializableViaExtendedInterfaceCollected extends ArrayIterator implements Serializableextendedinterface { // Deprecation warning via collected.
    public function serialize() {}
    public function unserialize( $data) {}
}

$anon = new class extends ArrayIterator implements Iterator, \ThisInterfaceIsInTheList { // Deprecation warning via user provided.
    public function Serialize() {
        return serialize($this->data);
    }
    public function UnSerialize( $data) {
        $this->data = unserialize($data);
    }
};

// This should NOT generate the "redundant" warning as it is not the PHP native Serializable and the userland interface may do more.
$anon = new class() implements \ThisInterfaceIsInTheList {
    public function serialize() {}
    public function unserialize( $data) {}
    public function __serialize() {}
    public function __unserialize($data) {}
};

interface DoesNotExtend {}
interface ThisInterfaceIsNOTInTheList extends ArrayAcces, \Serializable, ThisInterfaceisinTheList {} // Missing interface warning (plural found).

// Reset property.
// @codingStandardsChangeSetting PHPCompatibility.Interfaces.RemovedSerializable serializableInterfaces

// Must be last test: testing class without scope closer.
class Something implements Serializable {
