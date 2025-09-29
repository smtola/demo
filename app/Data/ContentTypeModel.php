<?php

namespace App\Data;

use Exception;
use stdClass;

class ContentTypeModel {
    private string $contentType;

    public function __construct(string $contentType) {
        $this->contentType = $contentType;
    }

    public function getContentType(): string {
        return $this->contentType;
    }

    public function to(): stdClass {
        $out = new stdClass();
        $out->{'Content-Type'} = $this->contentType;
        return $out;
    }

    public static function from(stdClass $obj): self {
        return new self($obj->{'Content-Type'});
    }
}
