<?php

namespace spec\LumenAd\Component\SpreadsheetParser\Xlsx;

use PhpSpec\ObjectBehavior;

class RowBuilderFactorySpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith('spec\LumenAd\Component\SpreadsheetParser\Xlsx\StubRowBuilder');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\Xlsx\RowBuilderFactory');
    }

    public function it_creates_row_builders()
    {
        $this->create()->shouldHaveType('spec\LumenAd\Component\SpreadsheetParser\Xlsx\StubRowBuilder');
    }
}

class StubRowBuilder
{

}
