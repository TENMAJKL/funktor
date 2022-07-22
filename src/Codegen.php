<?php

namespace Majkel\Fragment;

class Codegen
{
    public function __construct(
        private array $template,
        private array $functions = [],
        private array $structures = [],
    ) {
    }

    public function generate(): string
    {
        $indent = '';
        $result = '';

        foreach ($this->functions as $name => $function) {
            $result .= 'function __'.$name.'('.implode(', ', $function[0]).") {\n".implode("\n", $function[1])."\n}\n";
        }

        foreach ($this->structures as $name => $struct) {
            $result .= 'var __'.$name.' = '.json_encode($struct, JSON_PRETTY_PRINT)."\n";
        }

        foreach ($this->template as $item) {
            foreach ($item->content as $line) {
                if (str_starts_with($line, '}')) {
                    $indent = substr($indent, 1, -4);
                }
                $result .= $indent.$line;
                if (str_ends_with($line, '{')) {
                    $indent .= '    ';
                }
                $result .= "\n";
            }
        }

        return $result;
    }
}
