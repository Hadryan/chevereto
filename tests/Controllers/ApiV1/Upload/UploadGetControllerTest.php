<?php

/*
 * This file is part of Chevereto.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CheveretoTests\Controllers\ApiV1\Upload;

use Chevere\Components\Controller\ControllerRunner;
use Chevere\Components\Parameter\Arguments;
use Chevere\Interfaces\Controller\ControllerExecutedInterface;
use Chevere\Interfaces\Response\ResponseProvisionalInterface;
use Chevereto\Controllers\ApiV1\Upload\UploadGetController;
use PHPUnit\Framework\TestCase;

final class UploadGetControllerTest extends TestCase
{
    public function testConstruct(): void
    {
        $controller = new UploadGetController;
        $this->assertIsString($controller->getDescription());
        $arguments = new Arguments(
            $controller->getParameters(),
            [
                'source' => 'string source',
                'key' => 'some key',
                'format' => 'json'
            ]
        );
        $workflow = $controller->getWorkflow();
        $controller = $controller->withWorkflow($workflow);
        $response = $controller->run($arguments);
        $this->assertInstanceOf(ResponseProvisionalInterface::class, $response);
        $this->assertArrayHasKey('id', $response->data());
    }
}
