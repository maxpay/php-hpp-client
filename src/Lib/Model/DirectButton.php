<?php

namespace Maxpay\Lib\Model;

/**
 * Class DirectButton
 * @package Maxpay\Lib\Model
 */
class DirectButton extends BaseButton
{
    /** @var string */
    private $codeStart = "<form action='#action' class='redirect_form' method='post'>";

    /** @var string */
    private $codeEnd = "<button type='submit'>Pay</button></form>";

    /** @var string */
    private $baseHost;

    /**
     * @param string $baseHost
     */
    public function __construct(string $baseHost)
    {
        $this->baseHost = $baseHost;
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $body = "";
        foreach ($this->fieldList as $k => $v) {
            $body .= "<input type='hidden' name='{$k}' value='{$v}'>";
        }

        $this->buttonCode = str_replace("#action", $this->baseHost . 'hpp', $this->codeStart) .
            $body .
            $this->codeEnd;
    }
}
