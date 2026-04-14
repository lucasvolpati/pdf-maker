<?php

namespace PdfMaker\Contracts;

use PdfMaker\Certificates\Data;

interface PdfMaker
{
    public function make(array $crtObjects): array;
}
