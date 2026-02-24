<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

class FrameButton extends BaseButton
{
    private string $codeStart = "<div><script class='pspScript' ";

    private string $codeEnd = "></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-#sign'>"
        . "</iframe></div>";

    private string $baseHost;

    public function __construct(string $height, string $width, string $baseHost)
    {
        $this->baseHost = $baseHost;
        $this->pushValue('type', 'integrated');
        $this->pushValue('iframesrc', $this->baseHost . 'hpp');
        $this->pushValue('height', $height);
        $this->pushValue('width', $width);
    }

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
