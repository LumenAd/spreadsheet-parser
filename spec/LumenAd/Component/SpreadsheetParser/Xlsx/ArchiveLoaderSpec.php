<?php

namespace spec\LumenAd\Component\SpreadsheetParser\Xlsx;

use PhpSpec\ObjectBehavior;

class ArchiveLoaderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('spec\LumenAd\Component\SpreadsheetParser\Xlsx\StubArchive');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\Xlsx\ArchiveLoader');
    }

    public function it_loads_files()
    {
        $this->open('path')->getPath()->shouldReturn('path');
    }
}

class StubArchive
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
