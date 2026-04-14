<?php

namespace PdfMaker;

use PdfMaker\Certificates\Data;
use PdfMaker\Contracts\PdfMaker;

class Generator 
{
    private array $arrDocuments;

    public function __construct(
        protected PdfMaker $pdfMaker
    )
    {}

    public function make(): array
    {
        return $this->pdfMaker->make($this->arrDocuments);
    }

    public function setDocuments(array $arrDocuments): self
    {
        $this->arrDocuments = $arrDocuments;

        return $this;
    }
}
