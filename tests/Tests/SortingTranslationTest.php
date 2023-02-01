<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Console\SortingTranslation;

class SortingTranslationTest extends TestCase
{
    public function setUp(): void
    {
        $this->sortingTranslationMock = $this->getMockBuilder(SortingTranslation::class)
            ->setMethods([
                'configure',
                'execute',
                'isComment',
                'processBooking',
                'processData',
                'prepareData',
                "sort",
                "isSortSuccess"
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inputInterfaceMock = $this->getMockBuilder(InputInterface::class)
            ->setMethods(['setArgument', 'getArgument'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->outputInterfaceMock = $this->getMockBuilder(OutputInterface::class)
            ->setMethods(['writeln'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        
    }

    public function testConfigure()
    {
        $this->inputInterfaceMock->setArgument('input_file_path', 'input_file_path');
        $this->inputInterfaceMock->setArgument('command', 'sort');
        $this->assertNull($this->sortingTranslationMock->configure());
    }

    public function testExecute()
    {
        $this->inputInterfaceMock->setArgument('input_file_path', 'translate.properties');
        $this->inputInterfaceMock->setArgument('command', 'sort');
        $this->inputInterfaceMock->expects($this->any())
            ->method('getArgument')
            ->with('input_file_path')
            ->willReturn("sort.properties");
        $this->inputInterfaceMock->expects($this->any())
            ->method('getArgument')
            ->with('command')
            ->willReturn("sort");
        $result = $this->sortingTranslationMock->execute(
            $this->inputInterfaceMock, 
            $this->outputInterfaceMock
        );
        $this->assertEquals($result, false);
    }

    public function testProcessData()
    {
        $this->inputInterfaceMock->expects($this->any())
            ->method('getArgument')
            ->with("input_file_path")
            ->willReturn("test");
        $result = $this->sortingTranslationMock->processData(
            $this->inputInterfaceMock, 
            $this->outputInterfaceMock
        );
        $this->assertEquals($result, false);
    }

    public function testIsComment()
    {
        $this->sortingTranslationMock->expects($this->once())
            ->method('isComment')
            ->with("# test")
            ->willReturn(true);
        $result = $this->sortingTranslationMock->isComment("# test");
        $this->assertEquals($result, true);
    }

    public function testPrepareData()
    {
        $translatedData = ["test" => "# abcd"];
        $this->sortingTranslationMock->expects($this->once())
            ->method('prepareData')
            ->with(
                $this->inputInterfaceMock, 
                $this->outputInterfaceMock,
                $translatedData
            )
            ->willReturn(1);
        $result = $this->sortingTranslationMock->prepareData(
            $this->inputInterfaceMock, 
            $this->outputInterfaceMock,
            $translatedData
        );
        $this->assertEquals($result, true);
    }

    public function testSort()
    {
        $translatedData = ["test" => "# abcd"];
        $this->sortingTranslationMock->expects($this->once())
            ->method('sort')
            ->with(
                $this->inputInterfaceMock, 
                $this->outputInterfaceMock,
                $translatedData
            )
            ->willReturn(1);
        $result = $this->sortingTranslationMock->sort(
            $this->inputInterfaceMock, 
            $this->outputInterfaceMock,
            $translatedData
        );
        $this->assertEquals($result, true);
    }


    public function testIsSortSuccess()
    {
        $this->sortingTranslationMock->expects($this->once())
            ->method('isSortSuccess')
            ->with(
                'sort.properties',
                $this->outputInterfaceMock
            )
            ->willReturn(1);
        $result = $this->sortingTranslationMock->isSortSuccess(
            'sort.properties',
            $this->outputInterfaceMock
        );
        $this->assertEquals($result, true);
    }
}