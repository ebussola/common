<?php
/**
 * User: Leonardo Shinagawa
 * Date: 24/03/13
 * Time: 10:52
 */

namespace test\ebussola\common\superpower;


use ebussola\common\capacity\Arrayable;
use ebussola\common\datatype\String;
use ebussola\common\superpower\EasyArrayable;
use ebussola\common\superpower\StringFormat;

class StringFormatTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Something
     */
    private $something;

    private $name;
    private $lastname;
    private $level;

    /**
     * @return array
     */
    public function setUp() {
        $something = new Something();
        $this->something = $something;

        $this->name = md5(rand(0, 5000));
        $this->lastname = md5(rand(0, 5000));
        $this->level = rand(0, 5000);
        $something->setName($this->name);
        $something->setLastname($this->lastname);
        $something->setLevel($this->level);
    }

    public function testFormat() {
        $something= $this->something;
        $formatted = $something->formatString('{name} {lastname} - level: {level}');
        $this->assertEquals("{$this->name} {$this->lastname} - level: {$this->level}", $formatted);
    }

    public function testFormatWithZero() {
        $something= $this->something;
        $something->setLevel(0);

        $formatted = $something->formatString('{name} {lastname} - level: {level}');
        $this->assertEquals("{$this->name} {$this->lastname} - level: 0", $formatted);
    }

    public function testJoinFormat() {
        $expected = "{$this->name} - {$this->lastname} - {$this->level}";
        $formatted = $this->something->joinFormats(array('{name}', '{lastname}', '{level}'), ' - ');
        $this->assertEquals($expected, $formatted);

        $expected = "{$this->name}, {$this->lastname}, {$this->level}";
        $formatted = $this->something->joinFormats(array('{name}', '{lastname}', '{level}'), ', ');
        $this->assertEquals($expected, $formatted);
    }

    public function testJoinFormatWithEmptyValues() {
        $this->joinFormatWithEmptyValues(null);
        $this->joinFormatWithEmptyValues('');
        $this->joinFormatWithEmptyValues(false);
        $this->joinFormatWithEmptyValues(new String(''));
    }

    private function joinFormatWithEmptyValues($lastname_value) {
        $this->something->setLastname($lastname_value);
        $expected = "{$this->name} - {$this->level}";
        $formatted = $this->something->joinFormats(array('{name}', '{lastname}', '{level}'), ' - ');
        $this->assertEquals($expected, $formatted);

        $expected = "{$this->name}, {$this->level}";
        $formatted = $this->something->joinFormats(array('{name}', '{lastname}', '{level}'), ', ');
        $this->assertEquals($expected, $formatted);
    }

    public function testJoinFormatWithRandomFormatIndex() {
        $expected = "{$this->name}, {$this->lastname}, {$this->level}";
        $formatted = $this->something->joinFormats(array(10 => '{name}', 13 => '{lastname}', 5 => '{level}'), ', ');
        $this->assertEquals($expected, $formatted);
    }

    public function testSmartFormat() {
        $expected = "{$this->name} {$this->lastname} - level: {$this->level}";
        $formatted = $this->something->smartFormat('{name} {lastname} - level: {level}');
        $this->assertEquals($expected, $formatted);
    }

    public function testSmartFormatWithZero() {
        $this->something->setLevel(0);
        $expected = "{$this->name} {$this->lastname} - level: 0";
        $formatted = $this->something->smartFormat('{name} {lastname} - level: {level}');
        $this->assertEquals($expected, $formatted);
    }

    public function testSmartFormatIncomplete() {
        $this->something->setLevel(null);
        $expected = "{$this->name} {$this->lastname}";
        $formatted = $this->something->smartFormat('{name} {lastname} - level: {level}');
        $this->assertEquals($expected, $formatted);

        $this->something->setLastname(null);
        $this->something->setLevel(null);
        $expected = "{$this->name}";
        $formatted = $this->something->smartFormat('{name} {lastname} - level: {level}');
        $this->assertEquals($expected, $formatted);
    }

}

class Something implements Arrayable {
    use StringFormat, EasyArrayable;

    private $name;
    private $lastname;
    private $level;

    public function getLevel() {
        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

}