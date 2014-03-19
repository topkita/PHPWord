<?php
namespace PHPWord\Tests\Writer;

use PhpOffice\PhpWord\Writer\RTF;
use PHPWord;

/**
 * @package                     PHPWord\Tests
 * @coversDefaultClass          PhpOffice\PhpWord\Writer\RTF
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::construct
     */
    public function testConstruct()
    {
        $object = new RTF(new PHPWord);

        $this->assertInstanceOf('PhpOffice\\PHPWord', $object->getPHPWord());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\HashTable', $object->getDrawingHashTable());
    }

    /**
     * covers                    ::__construct
     * @expectedException        Exception
     * @expectedExceptionMessage No PHPWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new RTF();
        $object->getPHPWord();
    }

    /**
     * @covers ::save
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PHPWord();
        $section = $phpWord->createSection();
        $section->addText('Test');
        $writer = new RTF($phpWord);
        $writer->save('php://output');
    }

    /**
     * @covers                   ::save
     * @expectedException        Exception
     * @expectedExceptionMessage PHPWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new RTF();
        $writer->save();
    }

    /**
     * @covers ::save
     * @covers ::<private>
     */
    public function testSave()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $objectSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $file = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.rtf')
        );

        $phpWord = new PHPWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');
        $textrun->addTextBreak();
        $writer = new RTF($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }
}