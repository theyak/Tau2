<?php

use PHPUnit\Framework\TestCase;
use Theyak\Tau\View;

final class ViewTest extends TestCase
{
    private $root;

    public function testShouldRenderView()
    {
        $view = new View();
        $s = $view->renderToString("tests/views/helloworld");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }


    public function testShouldWorkWithVariables()
    {
        $view = new View();
        $s = $view->render("tests/views/hellovariable", ['name' => 'Variable']);
        $this->expectOutputString("<div>Hello Variable!</div>");
    }


    public function testShouldWorkWithAssignedVariables()
    {
        $view = new View();
        $view->assign('name', 'Variable');
        $s = $view->render("tests/views/hellovariable");
        $this->expectOutputString("<div>Hello Variable!</div>");
    }


    public function testShouldOverrideAssignedVairable()
    {
        $view = new View();
        $view->assign('name', 'Variable');
        $s = $view->render("tests/views/hellovariable", ['name' => 'Override']);
        $this->expectOutputString("<div>Hello Override!</div>");
    }


    public function testShouldBlockAndMinimize()
    {
        $view = new View();
        $view->render("tests/views/ShouldBlockAndMinimize");
        $this->expectOutputString("<div><span></span></div><div>\n        Should not minimize\n    </div>");
    }
}
