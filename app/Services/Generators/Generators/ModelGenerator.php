<?php namespace Genair\Services\Generators\Generators;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

class ModelGenerator extends Generator {

    /**
     * Fetch the compiled template for a model
     *
     * @param  string $template Path to template
     * @param  string $className
     * @return string Compiled template
     */
    protected function getTemplate($template, $className)
    {
        $this->template = $this->file->get($template);

        if ($this->needsScaffolding($template))
        {
            $this->template = $this->getScaffoldedModel($className);
        }
        str_replace('{{className}}', $className, $this->template);
        str_replace('{{table}}',Pluralizer::plural(Str::lower($className)),$this->template);
        str_replace('{{appName}}',$this->getAppNamespace(),$this->template);
        return ;
    }

    /**
     * Get template for a scaffold
     *
     * @param $className
     * @return string
     * @internal param string $template Path to template
     * @internal param string $name
     */
    protected function getScaffoldedModel($className)
    {
        if (! $fields = $this->cache->getFields())
        {
            return str_replace('{{rules}}', '', $this->template);
        }

        $rules = array_map(function($field) {
            return "'$field' => 'required'";
        }, array_keys($fields));

        return str_replace('{{rules}}', PHP_EOL."\t\t".implode(','.PHP_EOL."\t\t", $rules) . PHP_EOL."\t", $this->template);
    }

}
