<?php

namespace Paska\Toolbox
{
    class Json
    {
        /**
                  * @var mixed array
                  */
        private $data;

        /**
                 * @param string $string - Json string expected.
                 * @param bool $assoc
                 *
                 * @return mixed
                 */
        public static function parseString($string, $assoc = true)
        {
            $self = new self($string);

            return $self->parse($assoc);
        }

        /**
                 * @param mixed $data
                 *
                 * @return string
                 */
        public static function encodePretty($data)
        {
            return (new self($data))->encode(JSON_PRETTY_PRINT);
        }

        /**
                 * @param mixed $data
                 */
        public function __construct($data)
        {
            $this->data = $data;
        }

        /**
                 * @return string
                 */
        public function __toString()
        {
            try {
                return (string)$this->encode();
            } catch (\Exception $e) {
                // NOTE: Exceptions must not be raised in __toString(),
                // but we should process exception from encode() and stop the script (by trigger_error)
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }

        /**
                 * @param bool $assoc
                 *
                 * @return mixed
                 *
                 * @throws \LogicException
                 * @throws \InvalidArgumentException
                 */
        public function parse($assoc = true)
        {
            if (!is_string($data = $this->data)) {
                throw new \InvalidArgumentException(
                    sprintf("Expecting string, '%s' given", (string) new Castable($data))
                );
            }

            $data = json_decode($data, $assoc);

            if (($error = json_last_error_msg()) && 'No error' != $error/*PHP 5.5 behavior !*/) {
                throw new \InvalidArgumentException(
                    sprintf("Failed to parse json string <json>%s</json>, error: '%s'", $this->data, $error)
                );
            }

            return $data;
        }

        /**
                 * @param int $options
                 *
                 * @return string
                 */
        public function encode($options = 0)
        {
            $json = json_encode($data = $this->data, $options);

            if (($error = json_last_error_msg()) && 'No error' != $error/*PHP 5.5 behavior !*/) {
                $msg = "Failed to encode json string from data '%s'; Error: '%s'";
                throw new \InvalidArgumentException(sprintf($msg, (string) new Castable($data), $error));
            }

            return $json;
        }
    }
}
