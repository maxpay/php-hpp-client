<?php

namespace Maxpay\Lib\Model;

/**
 * Class PopupButton
 * @package Maxpay\Lib\Model
 */
class PopupButton extends BaseButton
{
    /** @var string */
    private $codeStart = "<div><form class='pspPaymentForm'><script class='pspScript' ";

    /** @var string */
    private $codeEnd = "></script></form></div>";

    /** @var string */
    private $baseHost;

    public function __construct(string $baseHost)
    {
        $this->baseHost = $baseHost;
        $this->pushValue('type', 'popup');
        $this->pushValue('iframesrc', $this->baseHost . 'hpp');
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

        $this->buttonCode = $this->codeStart . $body . $this->codeEnd;
    }
}
