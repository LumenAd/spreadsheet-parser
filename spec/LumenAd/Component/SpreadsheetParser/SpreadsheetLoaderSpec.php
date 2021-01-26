<?php

namespace spec\LumenAd\Component\SpreadsheetParser;

use LumenAd\Component\SpreadsheetParser\SpreadsheetInterface;
use LumenAd\Component\SpreadsheetParser\SpreadsheetLoaderInterface;
use PhpSpec\ObjectBehavior;

class SpreadsheetLoaderSpec extends ObjectBehavior
{
    public function let(
        SpreadsheetLoaderInterface $loader,
        SpreadsheetLoaderInterface $otherLoader,
        SpreadsheetInterface $spreadsheet
    )
    {
        $this->addLoader('extension', $loader)->addLoader('other_extension', $otherLoader);
        $loader->open('file.extension')->willReturn($spreadsheet);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\SpreadsheetLoader');
    }

    public function it_uses_the_loader_corresponding_to_the_file_extension(
        SpreadsheetInterface $spreadsheet
    )
    {
        $this->open('file.extension')->shouldReturn($spreadsheet);
    }

    public function it_throws_an_exception_if_no_loader_is_available()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringOpen('file');
    }
}
