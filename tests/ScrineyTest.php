<?php

declare(strict_types=1);

namespace Tests;

use Maxpay\Lib\Model\FixedProduct;
use Maxpay\Lib\Model\UserInfo;
use Maxpay\Scriney;
use PHPUnit\Framework\TestCase;

final class ScrineyTest extends TestCase
{
    private ?Scriney $scriney;

    public function setUp(): void
    {
        $this->scriney = new Scriney(
            'publicKey',
            'privateKey',
            null,
            'http://host.base.com/'
        );
    }

    public function tearDown(): void
    {
        $this->scriney = null;
    }

    public function testBuildSimplePopupButton(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-signature='b863b3d23c71d7f5ff4a846da7e71b769cea456d25a1e9e228faea52a2783f52' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')->buildPopup()->asString()
        );
    }

    public function testBuildSimpleFrameButton(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-signature='8de5cbaab0ae395f6f9705d12508abd1b82710290e116b7b291673b8fb268f17' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-8de5cbaab0ae395f6f9705d12508abd1b82710290e116b7b291673b8fb268f17'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')->buildFrame()->asString()
        );
    }

    public function testBuildSimpleDirectButton(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='signature' value='458f33be4111590e823d7fad0d88b18c1c06cfda094911dbb824bc9953add6d5'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')->buildDirectForm()->asString()
        );
    }

    public function testBuildPopupButtonWithPreselectedProduct(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-productpublicid='productIdInMportal' data-signature='0ea27ba82a3b16bad4b6d6cf762ca48b67ea555cbfb27e655bef562cdc6d9faf' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setProductId('productIdInMportal')
                ->buildPopup()
                ->asString()
        );
    }

    public function testBuildFrameButtonWithPreselectedProduct(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-productpublicid='productIdInMportal' data-signature='b98428731a5fe9d026d509b7f51c6d3e61ccbe2ce0a3a7a55da5e993b99c4030' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-b98428731a5fe9d026d509b7f51c6d3e61ccbe2ce0a3a7a55da5e993b99c4030'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setProductId('productIdInMportal')
                ->buildFrame()
                ->asString()
        );
    }

    public function testBuildDirectButtonWithPreselectedProduct(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='productpublicid' value='productIdInMportal'><input type='hidden' name='signature' value='d34597ce9a29cc89bd2272795221ee5d373ae4fdcd3eaaaea0aa387d47ddc166'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setProductId('productIdInMportal')
                ->buildDirectForm()
                ->asString()
        );
    }

    public function testBuildPopupButtonWithUserInfo(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-email='example&#x40;example.com' data-firstName='John' data-lastName='Anderson' data-country='USA' data-city='Los&#x20;angeles' data-address='2896&#x20;Providence&#x20;Lane' data-zip='90217' data-phone='6267746913' data-signature='5daa1840ddc70ea70eeb8be4e35dad6e864c9d592ba2dffb26f0980db3d2380f' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setUserInfo(
                    new UserInfo(
                        'example@example.com',
                        'John',
                        'Anderson',
                        'USA',
                        'Los angeles',
                        '90217',
                        '2896 Providence Lane',
                        '6267746913'
                    )
                )
                ->buildPopup()
                ->asString()
        );
    }

    public function testBuildFrameButtonWithUserInfo(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-email='example&#x40;example.com' data-firstName='John' data-lastName='Anderson' data-country='USA' data-city='Los&#x20;angeles' data-address='2896&#x20;Providence&#x20;Lane' data-zip='90217' data-phone='6267746913' data-signature='83ea66ebd6c17480d994270bdae9d1aa3c39e7290eadebc7a2528a3531d6d9ec' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-83ea66ebd6c17480d994270bdae9d1aa3c39e7290eadebc7a2528a3531d6d9ec'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setUserInfo(
                    new UserInfo(
                        'example@example.com',
                        'John',
                        'Anderson',
                        'USA',
                        'Los angeles',
                        '90217',
                        '2896 Providence Lane',
                        '6267746913'
                    )
                )
                ->buildFrame()
                ->asString()
        );
    }

    public function testBuildDirectButtonWithUserInfo(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='email' value='example&#x40;example.com'><input type='hidden' name='firstName' value='John'><input type='hidden' name='lastName' value='Anderson'><input type='hidden' name='country' value='USA'><input type='hidden' name='city' value='Los&#x20;angeles'><input type='hidden' name='address' value='2896&#x20;Providence&#x20;Lane'><input type='hidden' name='zip' value='90217'><input type='hidden' name='phone' value='6267746913'><input type='hidden' name='signature' value='f112e8775d88fc871c403b825d17cca4a7f1c7cc71c0482170d027e035fd4b78'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setUserInfo(
                    new UserInfo(
                        'example@example.com',
                        'John',
                        'Anderson',
                        'USA',
                        'Los angeles',
                        '90217',
                        '2896 Providence Lane',
                        '6267746913'
                    )
                )
                ->buildDirectForm()
                ->asString()
        );
    }

    public function testBuildPopupButtonWithCustomReturnUrls(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-success_url='https&#x3A;&#x2F;&#x2F;example.com&#x2F;success' data-decline_url='https&#x3A;&#x2F;&#x2F;example.com&#x2F;decline' data-backUrl='https&#x3A;&#x2F;&#x2F;example.com&#x2F;back' data-signature='6cddaa8eb4c2f3870e6b0705170286c002aaac93e56bdabc12215015d5504636' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setSuccessReturnUrl('https://example.com/success')
                ->setDeclineReturnUrl('https://example.com/decline')
                ->setBackUrl('https://example.com/back')
                ->buildPopup()
                ->asString()
        );
    }

    public function testBuildFrameButtonWithCustomReturnUrls(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-success_url='https&#x3A;&#x2F;&#x2F;example.com&#x2F;success' data-decline_url='https&#x3A;&#x2F;&#x2F;example.com&#x2F;decline' data-backUrl='https&#x3A;&#x2F;&#x2F;example.com&#x2F;back' data-signature='b3ee7208ef96cbe364b279db6a699366f0f20b6d42608e190bea8f73753c3180' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-b3ee7208ef96cbe364b279db6a699366f0f20b6d42608e190bea8f73753c3180'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setSuccessReturnUrl('https://example.com/success')
                ->setDeclineReturnUrl('https://example.com/decline')
                ->setBackUrl('https://example.com/back')
                ->buildFrame()
                ->asString()
        );
    }

    public function testBuildDirectButtonWithCustomReturnUrls(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='success_url' value='https&#x3A;&#x2F;&#x2F;example.com&#x2F;success'><input type='hidden' name='decline_url' value='https&#x3A;&#x2F;&#x2F;example.com&#x2F;decline'><input type='hidden' name='backUrl' value='https&#x3A;&#x2F;&#x2F;example.com&#x2F;back'><input type='hidden' name='signature' value='1fc47e49d13bca5fae4b28187e82aaed7d70682d1d7912b775dda39bda50fd5d'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setSuccessReturnUrl('https://example.com/success')
                ->setDeclineReturnUrl('https://example.com/decline')
                ->setBackUrl('https://example.com/back')
                ->buildDirectForm()
                ->asString()
        );
    }

    public function testBuildPopupButtonWithCustomParams(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-custom_param1='param&#x20;value&#x20;1' data-custom_param2='param&#x20;value&#x20;2' data-signature='d59c9907fe7c068213de399cfb9538fb45340d65802ff7d8c67e2d7322834df4' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomParams([
                    'custom_param1' => 'param value 1',
                    'custom_param2' => 'param value 2',
                ])
                ->buildPopup()
                ->asString()
        );
    }

    public function testBuildFrameButtonWithCustomParams(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-custom_param1='param&#x20;value&#x20;1' data-custom_param2='param&#x20;value&#x20;2' data-signature='8cb8fb823d49a032bfb3133166fa6ffac7842b3045ee791a1a704d47bbbcdde5' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-8cb8fb823d49a032bfb3133166fa6ffac7842b3045ee791a1a704d47bbbcdde5'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomParams([
                    'custom_param1' => 'param value 1',
                    'custom_param2' => 'param value 2',
                ])
                ->buildFrame()
                ->asString()
        );
    }

    public function testBuildDirectButtonWithCustomParams(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='custom_param1' value='param&#x20;value&#x20;1'><input type='hidden' name='custom_param2' value='param&#x20;value&#x20;2'><input type='hidden' name='signature' value='c23432d603b5fd3de7b125e83555031ae65cf52a038a0574d2e77784d94143a7'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomParams([
                    'custom_param1' => 'param value 1',
                    'custom_param2' => 'param value 2',
                ])
                ->buildDirectForm()
                ->asString()
        );
    }

    public function testBuildPopupButtonWithCustomProducts(): void
    {
        $expectedHtml = <<<HTML
<div><form class='pspPaymentForm'><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='popup' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-customproduct='&#x5B;&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId1&quot;,&quot;productName&quot;&#x3A;&quot;Garden&#x20;Table&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;198.98,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Table&#x20;&amp;&#x20;Set&#x20;of&#x20;2&#x20;Chairs&quot;&#x7D;,&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId2&quot;,&quot;productName&quot;&#x3A;&quot;Chair&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;110.5,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Rocking&#x20;Chair&quot;&#x7D;&#x5D;' data-signature='8c2ff324d762774704f31c46b92c5bcc519fad6b4821b004a1ee678d926419dd' ></script></form></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomProducts([
                    new FixedProduct(
                        'myProducId1',
                        'Garden Table',
                        198.98,
                        'USD',
                        null,
                        null,
                        'Magic Garden Table & Set of 2 Chairs'
                    ),
                    new FixedProduct(
                        'myProducId2',
                        'Chair',
                        110.50,
                        'USD',
                        null,
                        null,
                        'Magic Garden Rocking Chair'
                    ),
                ])
                ->buildPopup()
                ->asString()
        );
    }

    public function testBuildFrameButtonWithCustomProducts(): void
    {
        $expectedHtml = <<<HTML
<div><script class='pspScript' src='http://host.base.com/paymentPage.js' data-type='integrated' data-iframesrc='http&#x3A;&#x2F;&#x2F;host.base.com&#x2F;hpp' data-height='auto' data-width='auto' data-key='publicKey' data-buttontext='Pay' data-uniqueuserid='userId' data-displaybuybutton='true' data-customproduct='&#x5B;&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId1&quot;,&quot;productName&quot;&#x3A;&quot;Garden&#x20;Table&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;198.98,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Table&#x20;&amp;&#x20;Set&#x20;of&#x20;2&#x20;Chairs&quot;&#x7D;,&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId2&quot;,&quot;productName&quot;&#x3A;&quot;Chair&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;110.5,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Rocking&#x20;Chair&quot;&#x7D;&#x5D;' data-signature='e25d344f353dc808e7fa055ed38ba33f3cf84f2dce097868bb1cb6be6994d82b' ></script><form class='pspPaymentForm'></form><iframe id='psp-hpp-e25d344f353dc808e7fa055ed38ba33f3cf84f2dce097868bb1cb6be6994d82b'></iframe></div>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomProducts([
                    new FixedProduct(
                        'myProducId1',
                        'Garden Table',
                        198.98,
                        'USD',
                        null,
                        null,
                        'Magic Garden Table & Set of 2 Chairs'
                    ),
                    new FixedProduct(
                        'myProducId2',
                        'Chair',
                        110.50,
                        'USD',
                        null,
                        null,
                        'Magic Garden Rocking Chair'
                    ),
                ])
                ->buildFrame()
                ->asString()
        );
    }

    public function testBuildDirectButtonWithCustomProducts(): void
    {
        $expectedHtml = <<<HTML
<form action='http://host.base.com/hpp' class='redirect_form' method='post'><input type='hidden' name='key' value='publicKey'><input type='hidden' name='buttontext' value='Pay'><input type='hidden' name='uniqueuserid' value='userId'><input type='hidden' name='displaybuybutton' value='true'><input type='hidden' name='customproduct' value='&#x5B;&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId1&quot;,&quot;productName&quot;&#x3A;&quot;Garden&#x20;Table&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;198.98,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Table&#x20;&amp;&#x20;Set&#x20;of&#x20;2&#x20;Chairs&quot;&#x7D;,&#x7B;&quot;productType&quot;&#x3A;&quot;fixedProduct&quot;,&quot;productId&quot;&#x3A;&quot;myProducId2&quot;,&quot;productName&quot;&#x3A;&quot;Chair&quot;,&quot;currency&quot;&#x3A;&quot;USD&quot;,&quot;amount&quot;&#x3A;110.5,&quot;productDescription&quot;&#x3A;&quot;Magic&#x20;Garden&#x20;Rocking&#x20;Chair&quot;&#x7D;&#x5D;'><input type='hidden' name='signature' value='a2b6fa43a39630ad6f3db9d23fdebc503259b03fa145d2370c1380d8587e3e7c'><button type='submit'>Pay</button></form>
HTML;

        $this->assertSame(
            $expectedHtml,
            $this->scriney->buildButton('userId')
                ->setCustomProducts([
                    new FixedProduct(
                        'myProducId1',
                        'Garden Table',
                        198.98,
                        'USD',
                        null,
                        null,
                        'Magic Garden Table & Set of 2 Chairs'
                    ),
                    new FixedProduct(
                        'myProducId2',
                        'Chair',
                        110.50,
                        'USD',
                        null,
                        null,
                        'Magic Garden Rocking Chair'
                    ),
                ])
                ->buildDirectForm()
                ->asString()
        );
    }

    public function testValidateCallbackEmptySignature(): void
    {
        $this->assertFalse($this->scriney->validateCallback('', []));
    }

    public function testValidateCallbackInvalidSignature(): void
    {
        $headers = ['X-Signature' => '0000000000000000000000000000000000000000000000000000000000000000'];
        $this->assertFalse($this->scriney->validateCallback('foo=bar&bar=baz', $headers));
    }

    public function testValidateCallbackValidSignature(): void
    {
        $headers = ['X-Signature' => '111fcbb331aa8994d05621d3b6873dbb53a6f3966165cd5e091afbca8905da58'];
        $this->assertTrue($this->scriney->validateCallback('foo=bar&bar=baz', $headers));
    }
}
