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
    /** @var string */
    protected $builderScriptName = 'paymentPage';

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
    public function pushValue(string $name, $value): void
    {
        $this->unsafeFieldList[$name] = $value;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return void
     */
    private function setSignature(): void
    {
        $signatureHelper = new SignatureHelper();
        $this->pushValue(
            'signature',
            $signatureHelper->generateForArray(
                $this->unsafeFieldList,
                $this->key,
                true
            )
        );
    }

    /**
     * @return void
     */
    abstract public function build(): void;

    /**
     * @return string
     */
    public function asString(): string
    {
        $this->setSignature();
        $stringHelper = new StringHelper();
        foreach ($this->unsafeFieldList as $k => $v) {
            $this->fieldList[$stringHelper->encodeHtmlAttribute($k)] = $stringHelper->encodeHtmlAttribute($v);
        }
        $this->build();

        return $this->buttonCode;
    }

    /**
     * @return void
     */
    public function display(): void
    {
        echo $this->asString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }
}
