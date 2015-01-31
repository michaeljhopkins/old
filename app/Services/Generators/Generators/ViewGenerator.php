<?php

namespace Genair\Services\Generators\Generators;

use Illuminate\Support\Pluralizer;

class ViewGenerator extends Generator {

    /**
     * Fetch the compiled template for a view
     *
     * @param  string $template Path to template
     * @param  string $name
     * @return string Compiled template
     */
    protected function getTemplate($template, $name)
    {
        $this->template = $this->file->get($template);

        if ($this->needsScaffolding($template))
        {
            return $this->getScaffoldedTemplate($name);
        }

        // Otherwise, just set the file
        // contents to the file name
        return $name;
    }

    /**
     * Get the scaffolded template for a view
     *
     * @param  string $name
     * @return string Compiled template
     */
    protected function getScaffoldedTemplate($name)
    {
        $model = $this->cache->getModelName();  // post
        $models = Pluralizer::plural($model);   // posts
        $Models = ucwords($models);             // Posts
        $Model = Pluralizer::singular($Models); // Post
        $appName = $this->getAppNamespace();

        // Create and Edit views require form elements
        if ($name === 'create.blade' or $name === 'edit.blade' or $name === 'index.blad')
        {
            $formElements = $this->makeFormElements();

            $this->template = str_replace('{{formElements}}', $formElements, $this->template);
        }

        // Replace template vars in view
        foreach(array('model', 'models', 'Models', 'Model') as $var)
        {
            $this->template = str_replace('{{'.$var.'}}', $$var, $this->template);
        }

        // And finally create the table rows
        list($headings, $fields, $editAndDeleteLinks) = $this->makeTableRows($model);
        $this->template = str_replace('{{headings}}', implode(PHP_EOL."\t\t\t\t", $headings), $this->template);
        $this->template = str_replace('{{fields}}', implode(PHP_EOL."\t\t\t\t\t", $fields) . PHP_EOL . $editAndDeleteLinks, $this->template);

        return $this->template;
    }

    /**
     * Create the table rows
     *
     * @param  string $model
     * @return Array
     */
    protected function makeTableRows($model)
    {
        $models = Pluralizer::plural($model); // posts

        $fields = $this->cache->getFields();

        // First, we build the table headings
        $headings = array_map(function($field) {
            return '<th>' . ucwords($field) . '</th>';
        }, array_keys($fields));

        // And then the rows, themselves
        $fields = array_map(function($field) use ($model) {
            return "<td>{{{ \$$model->$field }}}</td>";
        }, array_keys($fields));

        // Now, we'll add the edit and delete buttons.
        $editAndDelete = <<<EOT
                    <td>
                        {{Form::open(['route'=>['{$models}.destroy',\${$model}->id],'method'=>'delete'])}}
                        <a class = 'btn btn-info' href = "/{$models}/{$model}->id}/edit"><i class = 'fa fa-pencil'></i></a>
                        <button type="submit" class = 'btn btn-danger'><i class = 'fa fa-trash-o text-white'></i></button>
                        {{Form::close()}}
                    </td>
EOT;

        return array($headings, $fields, $editAndDelete);
    }

    /**
     * Add Laravel methods, as string,
     * for the fields
     *
     * @return string
     */
    public function makeFormElements()
    {
        $formMethods = array();

        foreach($this->cache->getFields() as $name => $type)
        {
            $formalName = ucwords($name);

            // TODO: add remaining types
            switch($type)
            {
                case 'integer':
                   $element = "{{ BootstrapForm::text('$name') }}";
                    break;

                case 'text':
                    $element = "{{ BootstrapForm::textarea('$name') }}";
                    break;

                case 'boolean':
                    $element = "{{ BootstrapForm::checkbox('$name') }}";
                    break;

                default:
                    $element = "{{ BootstrapForm::text('$name') }}";
                    break;
            }
            $frag = $element;
            $formMethods[] = $frag;
        }

        return implode(PHP_EOL, $formMethods);
    }

}
