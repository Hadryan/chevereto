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

namespace Chevereto\Actions\Image;

use Chevere\Components\Action\Action;
use Chevere\Components\Parameter\Parameter;
use Chevere\Components\Parameter\Parameters;
use Chevere\Components\Regex\Regex;
use Chevere\Components\Response\ResponseSuccess;
use Chevere\Interfaces\Parameter\ArgumentsInterface;
use Chevere\Interfaces\Parameter\ParametersInterface;
use Chevere\Interfaces\Response\ResponseInterface;
use Intervention\Image\ImageManager;
use JeroenDesloovere\XmpMetadataExtractor\XmpMetadataExtractor;
use Throwable;

class FetchImageMetaAction extends Action
{
    public function getParameters(): ParametersInterface
    {
        return (new Parameters)
            ->withAddedOptional(
                (new Parameter('optional'))
            )
            ->withAddedRequired(
                (new Parameter('filename'))
                    ->withRegex(new Regex('/^.+$/'))
            );
    }

    public function run(ArgumentsInterface $arguments): ResponseInterface
    {
        $filename = $arguments->get('filename');
        $manager = new ImageManager(['driver' => 'Imagick']);
        $image = $manager->make($filename);
        $data = ['exif' => [], 'iptc' => [], 'xmp' => []];
        try {
            $data['exif'] = $image->exif() ?? [];
            $data['iptc'] = $image->iptc() ?? [];
            $xmpDataExtractor = new XmpMetadataExtractor();
            $data['xmp'] = $xmpDataExtractor->extractFromFile($filename);
        }
        // @codeCoverageIgnoreStart
        catch (Throwable $e) {
        }
        // @codeCoverageIgnoreEnd

        return new ResponseSuccess($data);
    }
}
