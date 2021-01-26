<?php

namespace spec\LumenAd\Component\SpreadsheetParser\Xlsx;

use PhpSpec\ObjectBehavior;

class RelationshipsLoaderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('spec\LumenAd\Component\SpreadsheetParser\Xlsx\StubRelationships');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\Xlsx\RelationshipsLoader');
    }

    public function it_loads_relationships()
    {
        $this->open('path')->getPath()->shouldReturn('path');
    }
}

class StubRelationships
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
