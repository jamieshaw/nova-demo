<?php

namespace Jamieshaw\CheckboxField;

use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Field;

class CheckboxField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'checkbox-field';

    public function actions()
    {
        $actions = DB::table('scope_permissions')->get();

        return $this->withMeta(['actions' => $actions, 'mush' => 'else']);
    }
}
