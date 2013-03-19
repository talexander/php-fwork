<?php
/**
 * User: tarakanov
 */

namespace App\Validator;


class StringLength extends Base {

    const INVALID   = 'stringLengthInvalid';
    const TOO_SHORT = 'stringLengthTooShort';
    const TOO_LONG  = 'stringLengthTooLong';

    /**
     * @var array
     */
    protected $msgTpls = array(
        self::INVALID   => "Invalid type given. String expected",
        self::TOO_SHORT => "The input is less than %min% characters long",
        self::TOO_LONG  => "The input is more than %max% characters long",
    );

    /**
     * @var array
     */
    protected $msgVars = array(
        'min' => array('options' => 'min'),
        'max' => array('options' => 'max'),
    );

    protected $options = array(
        'min'      => 0, // Minimum length
        'max'      => null, // Maximum length, null if there is no length limitation
        'encoding' => null, // Encoding to use
    );

    /**
     * Returns the min option
     *
     * @return integer
     */
    public function getMin() {
        return $this->options['min'];
    }

    /**
     * Sets the min option
     *
     * @param  integer $min
     * @throws Exception\InvalidArgument
     * @return StringLength Provides a fluent interface
     */
    public function setMin($min) {
        if (null !== $this->getMax() && $min > $this->getMax()) {
            throw new \App\Exception\InvalidArgument("The minimum must be less than or equal to the maximum length, but $min >"
                . " " . $this->getMax());
        }

        $this->options['min'] = max(0, (integer) $min);
        return $this;
    }

    /**
     * Returns the max option
     *
     * @return integer|null
     */
    public function getMax() {
        return $this->options['max'];
    }

    /**
     * Sets the max option
     *
     * @param  integer|null $max
     * @throws Exception\InvalidArgumentException
     * @return StringLength Provides a fluent interface
     */
    public function setMax($max)
    {
        if (null === $max) {
            $this->options['max'] = null;
        } elseif ($max < $this->getMin()) {
            throw new Exception\InvalidArgumentException("The maximum must be greater than or equal to the minimum length, but "
                . "$max < " . $this->getMin());
        } else {
            $this->options['max'] = (integer) $max;
        }

        return $this;
    }

    /**
     * Returns the actual encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->options['encoding'];
    }

    /**
     * Sets a new encoding to use
     *
     * @param string $encoding
     * @return StringLength
     * @throws Exception\InvalidArgumentException
     */
    public function setEncoding($encoding = null)
    {
        if ($encoding !== null) {
            $orig   = iconv_get_encoding('internal_encoding');
            $result = iconv_set_encoding('internal_encoding', $encoding);
            if (!$result) {
                throw new Exception\InvalidArgumentException('Given encoding not supported on this OS!');
            }

            iconv_set_encoding('internal_encoding', $orig);
        }

        $this->options['encoding'] = $encoding;
        return $this;
    }

    /**
     * Returns true if and only if the string length of $value is at least the min option and
     * no greater than the max option (when the max option is not null).
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);
        if ($this->getEncoding() !== null) {
            $length = iconv_strlen($value, $this->getEncoding());
        } else {
            $length = iconv_strlen($value);
        }

        if ($length < $this->getMin()) {
            $this->error(self::TOO_SHORT);
        }

        if (null !== $this->getMax() && $this->getMax() < $length) {
            $this->error(self::TOO_LONG);
        }

        if (count($this->getMessages())) {
            return false;
        } else {
            return true;
        }
    }

}