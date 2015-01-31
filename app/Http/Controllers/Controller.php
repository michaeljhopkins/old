<?php namespace Genair\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Watson\Validating\ValidatingTrait;

abstract class Controller extends BaseController {
	use DispatchesCommands, ValidatesRequests, SoftDeletes;

}
