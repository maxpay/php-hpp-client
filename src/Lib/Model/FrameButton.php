<?php

namespace Maxpay\Lib\Model;

/**
 * Class FrameButton
 * @package Maxpay\Lib\Model
 */
class FrameButton extends BaseButton
{
    /** @var string */
    private $codeStart = "<div><script class='maxpayScript' ";

    /** @var string */
    private $codeEnd = "></script><form class='maxpayPaymentForm'></form><iframe id='maxpay-hpp-#sign'></iframe></div>";

    /** @var string */
    private $baseHost;

    /**
     * @param string $height
     * @param string $width
     * @param string $baseHost
     */
    public function __construct($height, $width, $baseHost)
    {
        $this->baseHost = $baseHost;
        $this->pushValue('type', 'integrated');
        $this->pushValue('iframesrc', $this->baseHost . 'hpp');
        $this->pushValue('height', $height);
        $this->pushValue('width', $width);
    }

    /** @return string */
    public function build()
    {
        $body = "src='". $this->baseHost ."client.js' ";
        foreach ($this->fieldList as $key => $value) {
            $body  .= "data-" . $key . "='" . $value . "' ";
        }

        $this->buttonCode = $this->codeStart .
            $body .
            str_replace("#sign", $this->fieldList['signature'], $this->codeEnd);
    }
}
