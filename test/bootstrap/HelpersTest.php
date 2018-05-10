<?php

namespace Test;

class HelpersTest extends \UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        putenv('ENV=local');
        parent::tearDown();
    }

    public function testGetResourceFilename_withCacheBuster()
    {
        // Expect to do nothing
        putenv('ENV=local');
        $input = 'style/not-exist.css';
        $expected = 'style/not-exist.css';
        $this->assertSame($expected, getResourceFilename($input, true));

        putenv('ENV=staging');
        // Test on non existent file, cannot produce hash versioning
        // But can produce '.min'
        $input = 'style/not-exist.css';
        $expected = 'style/not-exist.min.css';
        $this->assertSame($expected, getResourceFilename($input, true));

        // Cache buster and minify
        putenv('ENV=prod');
        $input = 'style/test-version.css';
        $expected = 'style/test-version.9749fad13d.min.css';
        $fullPath = base_path('public').DIRECTORY_SEPARATOR.$input;
        file_put_contents($fullPath, 'test-content');
        $this->assertSame($expected, getResourceFilename($input, true));
        unlink($fullPath);

        // Cache buster, but not minify
        putenv('ENV=local');
        $input = 'style/test-version.css';
        $expected = 'style/test-version.9749fad13d.css';
        $fullPath = base_path('public').DIRECTORY_SEPARATOR.$input;
        file_put_contents($fullPath, 'test-content');
        $this->assertSame($expected, getResourceFilename($input, true));
        unlink($fullPath);
    }

    public function testGetResourceFilename_without_cacheBuster()
    {
        // Expect to do nothing
        putenv('ENV=local');
        $input = 'style/bootstrap.css';
        $expected = 'style/bootstrap.css';
        $this->assertSame($expected, getResourceFilename($input, false));

        $input = 'aaa';
        $expected = 'aaa';
        $this->assertSame($expected, getResourceFilename($input));

        // Force to operate
        putenv('ENV=prod');
        // hasMinifiedSuffixAlready
        $input = 'style/bootstrap.min.css';
        $expected = 'style/bootstrap.min.css';
        $this->assertSame($expected, getResourceFilename($input));

        // Not have minified suffix yet
        $input = '/test.min.css.style/bootstrap.css';
        $expected = '/test.min.css.style/bootstrap.min.css';
        $this->assertSame($expected, getResourceFilename($input));

        // Not have minified suffix yet
        $input = '/style/bootstrap.js';
        $expected = '/style/bootstrap.min.js';
        $this->assertSame($expected, getResourceFilename($input));

        $input = 'a';
        $expected = 'a';
        $this->assertSame($expected, getResourceFilename($input));

        $input = '';
        $expected = '';
        $this->assertSame($expected, getResourceFilename($input));

        $input = '.js';
        $expected = '.min.js';
        $this->assertSame($expected, getResourceFilename($input));
    }
}
