<?php

namespace spec\LumenAd\Component\SpreadsheetParser\Xlsx;

use PhpSpec\ObjectBehavior;
use LumenAd\Component\SpreadsheetParser\Xlsx\Archive;
use LumenAd\Component\SpreadsheetParser\Xlsx\Relationships;
use LumenAd\Component\SpreadsheetParser\Xlsx\RelationshipsLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\RowIterator;
use LumenAd\Component\SpreadsheetParser\Xlsx\RowIteratorFactory;
use LumenAd\Component\SpreadsheetParser\Xlsx\SharedStrings;
use LumenAd\Component\SpreadsheetParser\Xlsx\SharedStringsLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\Styles;
use LumenAd\Component\SpreadsheetParser\Xlsx\StylesLoader;
use LumenAd\Component\SpreadsheetParser\Xlsx\ValueTransformer;
use LumenAd\Component\SpreadsheetParser\Xlsx\ValueTransformerFactory;
use LumenAd\Component\SpreadsheetParser\Xlsx\Spreadsheet;
use LumenAd\Component\SpreadsheetParser\Xlsx\WorksheetListReader;
use Prophecy\Argument;
use Prophecy\Exception\Prediction\UnexpectedCallsException;

class SpreadsheetSpec extends ObjectBehavior
{
    public function let(
        RelationshipsLoader $relationshipsLoader,
        SharedStringsLoader $sharedStringsLoader,
        StylesLoader $stylesLoader,
        WorksheetListReader $worksheetListReader,
        ValueTransformerFactory $valueTransformerFactory,
        RowIteratorFactory $rowIteratorFactory,
        Archive $archive,
        Relationships $relationships,
        SharedStrings $sharedStrings,
        ValueTransformer $valueTransformer,
        Styles $styles
    ) {
        $this->beConstructedWith(
            $archive,
            $relationshipsLoader,
            $sharedStringsLoader,
            $stylesLoader,
            $worksheetListReader,
            $valueTransformerFactory,
            $rowIteratorFactory
        );
        $archive->extract(Argument::type('string'))->will(
            function ($args) {
                return sprintf('temp_%s', $args[0]);
            }
        );

        $beCalledAtMostOnce = function ($calls, $object, $method) {
            if (count($calls) > 1) {
                throw new UnexpectedCallsException(
                    'Method should be called at most once',
                    $method,
                    $calls
                );
            }
        };
        $relationshipsLoader->open('temp_' . Spreadsheet::RELATIONSHIPS_PATH)
            ->should($beCalledAtMostOnce)
            ->willReturn($relationships);

        $relationships->getSharedStringsPath()->willReturn('shared_strings');
        $relationships->getStylesPath()->willReturn('styles');

        $sharedStringsLoader->open('temp_shared_strings', $archive)
            ->should($beCalledAtMostOnce)
            ->willReturn($sharedStrings);

        $stylesLoader->open(('temp_styles'), $archive)->willReturn($styles);
        $valueTransformerFactory->create($sharedStrings, $styles)->willReturn($valueTransformer);

        $worksheetListReader->getWorksheetPaths($relationships, 'temp_' . Spreadsheet::WORKBOOK_PATH)
            ->should($beCalledAtMostOnce)
            ->willReturn(['sheet1' => 'path1', 'sheet2' => 'path2']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('LumenAd\Component\SpreadsheetParser\Xlsx\Spreadsheet');
    }

    public function it_returns_the_worksheet_list()
    {
        $this->getWorksheets()->shouldReturn(['sheet1', 'sheet2']);
    }

    public function it_creates_row_iterators(
        ValueTransformer $valueTransformer,
        RowIteratorFactory $rowIteratorFactory,
        RowIterator $rowIterator1,
        RowIterator $rowIterator2,
        Archive $archive
    ) {
        $rowIteratorFactory->create($valueTransformer, 'temp_path1', [], $archive)->willReturn($rowIterator1);
        $rowIteratorFactory->create($valueTransformer, 'temp_path2', [], $archive)->willReturn($rowIterator2);

        $this->createRowIterator(0)->shouldReturn($rowIterator1);
        $this->createRowIterator(1)->shouldReturn($rowIterator2);
    }

    public function it_finds_a_worksheet_index_by_name()
    {
        $this->getWorksheetIndex('sheet2')->shouldReturn(1);
    }

    public function it_returns_false_if_a_worksheet_does_not_exist()
    {
        $this->getWorksheetIndex('sheet3')->shouldReturn(false);
    }
}
