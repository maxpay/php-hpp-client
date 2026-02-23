<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

use Maxpay\Lib\Util\SignatureHelper;
use Maxpay\Lib\Util\StringHelper;

abstract class BaseButton implements RenderableInterface
{
    protected string $builderScriptName = 'paymentPage';

    /** @var string[] */
    protected array $fieldList = [];

    /** @var string[] */
    private array $unsafeFieldList = [];

    protected string $buttonCode = '';

    private string $key = '';

    /**
     * @param string $name
     * @param mixed $value
     */
    public function pushValue(string $name, $value): void
    {
        $this->unsafeFieldList[$name] = $value;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

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

    abstract public function build(): void;

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

    public function display(): void
    {
        echo $this->asString();
    }

    public function __toString(): string
    {
        return $this->asString();
    }
}
