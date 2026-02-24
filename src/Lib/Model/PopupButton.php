<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

class PopupButton extends BaseButton
{
    private string $codeStart = "<div><form class='pspPaymentForm'><script class='pspScript' ";

    private string $codeEnd = "></script></form></div>";

    private string $baseHost;

    public function __construct(string $baseHost)
    {
        $this->baseHost = $baseHost;
        $this->pushValue('type', 'popup');
        $this->pushValue('iframesrc', $this->baseHost . 'hpp');
    }

    public function build(): void
    {
        $body = "src='" . $this->baseHost . $this->builderScriptName . ".js' ";
        foreach ($this->fieldList as $key => $value) {
            $body .= "data-" . $key . "='" . $value . "' ";
        }

        $this->buttonCode = $this->codeStart . $body . $this->codeEnd;
    }
}
