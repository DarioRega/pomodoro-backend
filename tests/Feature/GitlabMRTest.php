<?php

namespace Tests\Feature;

use App\Actions\Pomodoro\gitlabTestMR;
use Tests\TestCase;

class GitlabMRTest extends TestCase
{

    public function testGitlabMrCoverage()
    {
        $result = gitlabTestMR::run(true);
        $this->assertEquals('This code should me marked as tested', $result);
    }
}
