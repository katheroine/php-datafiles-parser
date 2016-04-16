<?php

/*
 * This file is part of the DataCoder package.
 *
 * (c) Katarzyna Krasińska <katheroine@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exorg\DataCoder;

/**
 * FileTest.
 * PHPUnit test class for File class.
 *
 * @package DataCoder
 * @author Katarzyna Krasińska <katheroine@gmail.com>
 * @copyright Copyright (c) 2015 Katarzyna Krasińska
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://github.com/ExOrg/php-data-coder
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Relative path of directory with file fixtures
     * used in tests.
     */
    const FILE_FIXTURES_RELATIVE_PATH = 'files';

    /**
     * Self-test for function buildFileFixturePath.
     */
    public function testSelfBuildFileFixturePath()
    {
        $expectedFileContent = 'Self test';

        $filePath = $this->buildFileFixturePath('self-test');

        $actualFileContent = file_get_contents($filePath);

        $this->assertEquals($expectedFileContent, $actualFileContent);
    }

    /**
     * Test if Exorg\DataCoder\File class
     * has been created.
     */
    public function testFileClassExists()
    {
        $this->assertTrue(
            class_exists('Exorg\DataCoder\File')
        );
    }

    /**
     * Test if constructor throws exception
     * when file path argument is null.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWhenFilePathIsNull()
    {
        $filePath = null;

        new File($filePath);
    }

    /**
     * Test if constructor throws exception
     * when file path argument is empty string.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWhenFilePathIsEmptyString()
    {
        $filePath = '';

        new File($filePath);
    }

    /**
     * Test if constructor creates object
     * when file defined by passed path
     * doesn't exist.
     */
    public function testConstructorWhenFileDoesNotExist()
    {
        $filePath = $this->buildFileFixturePath('nonexistent');

        $file = new File($filePath);

        $this->assertInstanceOf('Exorg\DataCoder\File', $file);
    }

    /**
     * Test if constructor creates object
     * when file defined by passed path exists.
     */
    public function testConstructorWhenFileExists()
    {
        $filePath = $this->buildFileFixturePath('file.ext');

        $file = new File($filePath);

        $this->assertInstanceOf('Exorg\DataCoder\File', $file);
    }

    /**
     * Test if getExtension function
     * has been defined.
     */
    public function testGetExtensionFunctionExists()
    {
        $this->assertTrue(
            method_exists(
                'Exorg\DataCoder\File',
                'getExtension'
            )
        );
    }

    /**
     * Test getExtension function
     * returns proper extension.
     *
     * @dataProvider filePathExtensionProvider
     */
    public function testGetExtensionFunction($filePath, $fileExtension)
    {
        $file = new File($filePath);

        $this->assertEquals($fileExtension, $file->getExtension());
    }

    /**
     * Test if getContent function
     * has been defined.
     */
    public function testGetContentFunctionExists()
    {
        $this->assertTrue(
            method_exists(
                'Exorg\DataCoder\File',
                'getContent'
            )
        );
    }

    /**
     * Test getContent function throws exception
     * when file doesn't exist.
     *
     * @expectedException \Exorg\DataCoder\NonexistentFileException
     */
    public function testGetContentFunctionWhenFileDoesNotExist()
    {
        $filePath = $this->buildFileFixturePath('nonexistent');

        $file = new File($filePath);

        $file->getContent();
    }

    /**
     * Test getContent function throws exception
     * when file is not writable.
     *
     * @expectedException \Exorg\DataCoder\NonexistentFileException
     */
    public function testGetContentFunctionWhenFileIsNotWritableExist()
    {
        $filePath = $this->buildFileFixturePath('unreadable');

        $file = new File($filePath);

        $file->getContent();
    }

    /**
     * Test getContent function
     * returns proper content.
     *
     * @dataProvider filePathContentProvider
     */
    public function testGetContentFunction($filePath, $fileContent)
    {
        $file = new File($filePath);

        $this->assertEquals($fileContent, $file->getContent());
    }

    /**
     * Provide file paths
     * and appropriate extension.
     *
     * @return array
     */
    public function filePathExtensionProvider()
    {
        return array(
            array('file', ''),
            array('file.ext', 'ext'),
            array('file.dat', 'dat'),
            array('files/file.ext', 'ext'),
        );
    }

    /**
     * Provide file paths
     * and appropriate contents.
     *
     * @return array
     */
    public function filePathContentProvider()
    {
        return array(
            array($this->buildFileFixturePath('file'), 'File'),
            array($this->buildFileFixturePath('file.ext'), 'File with extension'),
            array($this->buildFileFixturePath('file.dat'), 'File with another extension'),
            array($this->buildFileFixturePath('directory/file'), 'File in directory'),
        );
    }

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass()
    {
        exec("chmod a-r " . __DIR__ . '/files/unreadable');
    }

    /**
     * This method is called after the last test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function tearDownAfterClass()
    {
        exec("chmod a+r " . __DIR__ . '/files/unreadable');
    }

    /**
     * Returns absolute path to the file fixture.
     *
     * @param string $fileName
     * @return string
     */
    private function buildFileFixturePath($fileName)
    {
        $absoluteFilePath = __DIR__
            . DIRECTORY_SEPARATOR
            . self::FILE_FIXTURES_RELATIVE_PATH
            . DIRECTORY_SEPARATOR
            . $fileName;

        return $absoluteFilePath;
    }
}
