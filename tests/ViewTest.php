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

    public function testShouldWorkWithMultipleAssignedVariables()
    {
        $view = new View();
        $view->assign('name', 'Variable');
        $view->assign('noun', 'life');
        $s = $view->render("tests/views/hellomulti");
        $this->expectOutputString("<div>Hello Variable, how's life?</div>");
    }


    public function testShouldWorkWithMultipleAssignedVariablesAsArray()
    {
        $view = new View();
        $view->assign([
            'name' => 'Variable',
            'noun' => 'life',
        ]);
        $s = $view->render("tests/views/hellomulti");
        $this->expectOutputString("<div>Hello Variable, how's life?</div>");
    }


    public function testShouldOverrideAssignedVairable()
    {
        $view = new View();
        $view->assign('name', 'Variable');
        $s = $view->render("tests/views/hellovariable", ['name' => 'Override']);
        $this->expectOutputString("<div>Hello Override!</div>");
    }


    public function testShouldRegisterAndUseMinimizeBlock()
    {
        $view = new View();
        $view->render("tests/views/ShouldBlockAndMinimize");
        $this->expectOutputString("<div><span></span></div><div>\n        Should not minimize\n    </div>");
    }

    public function testShouldUseFolders()
    {
        $view = new View(['folders' => ['tests/views']]);
        $s = $view->renderToString("helloworld");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testShouldUsePaths()
    {
        $view = new View(['paths' => ['tests/views']]);
        $s = $view->renderToString("helloworld");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testShouldUsePathsAsString()
    {
        $view = new View(['paths' => 'tests/views']);
        $s = $view->renderToString("helloworld");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testShouldUseDefaultTemplate()
    {
        $view = new View([
            'defaultTemplate' => 'tests/views/helloworld'
        ]);
        $s = $view->renderToString('badfile');
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testShouldUseCallableDefaultTemplate()
    {
        $view = new View(['defaultTemplate' => function() {
            return "tests/views/helloworld";
        }]);
        $s = $view->renderToString('badfile');
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testShouldFailInvalidTemplate()
    {
        $this->expectException(TypeError::class);
        $view = new View();
        $s = $view->render(new stdClass);
    }

    public function testShouldFailInvalidDefaultTemplate()
    {
        $this->expectException(TypeError::class);
        $view = new View(['defaultTemplate' => new stdClass]);
        $s = $view->render(new stdClass);
    }

    public function testShouldErrorOnNullDefaultTemplate()
    {
        $this->expectException(TypeError::class);
        $view = new View(['defaultTemplate' => null]);
        $s = $view->render('badfile');
    }

    public function testShouldErrorOnNullTemplate()
    {
        $this->expectException(TypeError::class);
        $view = new View();
        $s = $view->render();
    }

    public function testDefaultExtensionPhtml()
    {
        $view = new View(['extension' => null]);
        $s = $view->renderToString("tests/views/helloworld");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

    public function testNoExtension()
    {
        $this->expectException(TypeError::class);
        $view = new View(['extension' => false]);
        $s = $view->renderToString("tests/views/helloworld");
    }

    public function testNoExtensionWithExtensionIncludedInFilename()
    {
        $view = new View(['extension' => false]);
        $s = $view->renderToString("tests/views/helloworld.phtml");
        $this->assertEquals("<div>Hello World!</div>", $s);
    }

}
