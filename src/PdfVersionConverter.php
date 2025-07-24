<?php

namespace fabianogaldino\PDFVersionConverter;

use fabianogaldino\PDFVersionConverter\Converter\GhostscriptConverter;
use fabianogaldino\PDFVersionConverter\Converter\GhostscriptConverterCommand;
use fabianogaldino\PDFVersionConverter\Guesser\RegexGuesser;
use Symfony\Component\Filesystem\Filesystem;

class PdfVersionConverter
{
    public function convertTo14(string $content)
    {
        return $this->convert($content, '1.4');
    }

    public function convert($content, string $version)
    {
        $command = new GhostscriptConverterCommand();

        $filesystem = new Filesystem();

        $converter = new GhostscriptConverter($command, $filesystem);

        $tmpFile = tmpfile();

        $filename = stream_get_meta_data($tmpFile)['uri'];

        file_put_contents($filename, $content);

        $converter->convert($filename, $version);

        $data = file_get_contents($filename);

        unset($tmpFile);

        unset($filename);

        return $data;
    }

    public function guess(string $content)
    {
        $tmpFile = tmpfile();

        $filename = stream_get_meta_data($tmpFile)['uri'];

        file_put_contents($filename, $content);

        $version = (new RegexGuesser())->guess($filename);

        unset($tmpFile);

        unset($filename);

        return $version;
    }
}
