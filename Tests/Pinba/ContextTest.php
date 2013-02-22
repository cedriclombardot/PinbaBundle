<?php

namespace Cedriclombardot\PinbaBundle\Tests\Pinba;

use Cedriclombardot\PinbaBundle\Pinba\Context;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ContextTest extends WebTestCase
{
    protected function getRequest($serverVars)
    {
        return new Request(array(), array(), array(), array(), array(), $serverVars);
    }

    public function testExtractScriptName()
    {
        $serverVars = array(
            'HTTP_HOST'            => 'tests.acme.fr',
            'SERVER_SOFTWARE'      => 'Apache',
            'SERVER_NAME'          => 'tests.acme.fr',
            'SERVER_ADDR'          => '192.168.0.1',
            'DOCUMENT_ROOT'        => '/acme/web',
            'SCRIPT_FILENAME'      => '/acme/web/app_dev.php',
            'SERVER_PROTOCOL'      => 'HTTP/1.1',
            'REQUEST_METHOD'       => 'GET',
            'QUERY_STRING'         => 'foo=bar',
            'REQUEST_URI'          => '/app_dev.php/fr/?foo=bar',
            'SCRIPT_NAME'          => '/app_dev.php',
            'PATH_INFO'            => '/fr/',
            'PATH_TRANSLATED'      => 'redirect:/app.php/',
            'PHP_SELF'             => '/app_dev.php/fr/'
        );

        $context = new ContextMock('{PATH_INFO}');
        $this->assertEquals(
            $serverVars['PATH_INFO'],
            $context->extractScriptNameMock($this->getRequest($serverVars)),
            'Works with only one var.'
        );

        $context = new ContextMock('--{PATH_INFO}--');
        $this->assertEquals(
            '--'.$serverVars['PATH_INFO'].'--',
            $context->extractScriptNameMock($this->getRequest($serverVars)),
            'Works with only one var, and some chars.'
        );

        $context = new ContextMock('{HTTP_HOST}{REQUEST_URI}');
        $this->assertEquals(
            $serverVars['PATH_INFO'].$serverVars['REQUEST_URI'],
            $context->extractScriptNameMock($this->getRequest($serverVars)),
            'Works with two vars.'
        );

        $context = new ContextMock('test: {HTTP_HOST}/{REQUEST_URI} !');
        $this->assertEquals(
            'test: '.$serverVars['PATH_INFO'].'/'.$serverVars['REQUEST_URI'].' !',
            $context->extractScriptNameMock($this->getRequest($serverVars)),
            'Works with two vars and some chars.'
        );

        $context = new ContextMock('test: {HTTPHOST}/{REQUEST_URI} !');
        try {
            $context->extractScriptNameMock($this->getRequest($serverVars));
            $this->assertTrue(false, 'Throw InvalidArgumentException if inexisting param is requested.');
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true, 'Throw InvalidArgumentException if inexisting param is requested.');
        }

        $context = new ContextMock('Hello world');
        $this->assertEquals(
            'Hello world',
            $context->extractScriptNameMock($this->getRequest($serverVars)),
            'Works with any vars, returns pattern.'
        );
    }
}

class ContextMock extends Context
{
    public function extractScriptNameMock(Request $request)
    {
        return $this->extractScriptName($request);
    }
}
