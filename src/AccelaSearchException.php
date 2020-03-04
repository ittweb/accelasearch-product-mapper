<?php
/**
 * An exception in AccelaSearch.
 */
namespace Ittweb\AccelaSearch;

/**
 * An exception in AccelaSearch.
 */
class AccelaSearchException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $message Message
     * @param int $code Code
     * @param \Exception $previous Previous exception
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }


    /**
     * Returns a textual representation of this exception.
     *
     * @return string Textual representation
     */
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
