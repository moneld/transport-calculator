<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use PhpParser\Node\Scalar\String_;

class XmlToCollection
{


    public function __construct(private String $path, private String $tag)
    {
    }

    public function getData() : Collection
    {
        $xmlObjet = file_get_contents(resource_path('data/'.$this->path));
        $data = json_decode(json_encode(simplexml_load_string($xmlObjet)), true);
        return collect($data['Response']['Object'][$this->tag]);
    }
}
