<?php

namespace Maxpay\Lib\Model;

/**
 * Class FrameButton
 * @package Maxpay\Lib\Model
 */
class FrameButton extends BaseButton
{
    /** @var string */
    private $codeStart = "<div><script class='pspScript' ";

    /** @var string */
    private $codeEnd = "></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-#sign'></iframe></div>";

    /** @var string */
    private $baseHost;

    /**
     * @param string $height
     * @param string $width
     * @param string $baseHost
     */
    public function __construct(string $height, string $width, string $baseHost)
    {
        $this->baseHost = $baseHost;
        $this->pushValue('type', 'integrated');
        $this->pushValue('iframesrc', $this->baseHost . 'hpp');
        $this->pushValue('height', $height);
        $this->pushValue('width', $width);
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $body = "src='" . $this->baseHost . $this->builderScriptName . ".js' ";
        foreach ($this->fieldList as $key => $value) {
            $body .= "data-" . $key . "='" . $value . "' ";
        }

        $this->buttonCode = $this->codeStart .
            $body .
            str_replace("#sign", $this->fieldList['signature'], $this->codeEnd);
    }
}
