<?php

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\StringHelper;

/**
 * Class BaseButton
 * @package Maxpay\Lib\Model
 */
abstract class BaseButton implements RenderableInterface
{
    /** @var string[] */
    protected $fieldList = [];

    /** @var string[] */
    private $unsafeFieldList = [];

    /** @var string */
    protected $buttonCode = '';

    /** @var string */
    private $key = '';

    /**
     * @param string $name
     * @param mixed $value
     */
    public function pushValue($name, $value)
    {
        $this->unsafeFieldList[$name] = $value;
    }

    /** @param string $key */
    public function setKey($key)
    {
        $this->key = $key;
    }

    private function setSignature()
    {
        $signatureHelper = new SignatureHelper();
        $this->pushValue(
            'signature',
            $signatureHelper->generate(
                $this->unsafeFieldList,
                $this->key,
                true
            )
        );
    }

    /** @return void */
    abstract public function build();

    /** @return string */
    public function asString()
    {
        $this->setSignature();
        $stringHelper = new StringHelper();
        foreach ($this->unsafeFieldList as $k => $v) {
            $this->fieldList[
                $stringHelper->encodeHtmlAttribute($k)
            ] = $stringHelper->encodeHtmlAttribute($v);
        }
        $this->build();
        return $this->buttonCode;
    }

    /** @return void */
    public function display()
    {
        echo $this->asString();
    }

    /** @return string */
    public function __toString()
    {
        return $this->asString();
    }
}
