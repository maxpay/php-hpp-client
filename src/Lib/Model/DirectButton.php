<?php

declare(strict_types=1);

namespace Maxpay\Lib\Model;

class DirectButton extends BaseButton
{
    private string $codeStart = "<form action='#action' class='redirect_form' method='post'>";

    private string $codeEnd = "<button type='submit'>Pay</button></form>";

    private string $baseHost;

    public function __construct(string $baseHost)
    {
        $this->baseHost = $baseHost;
    }

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
