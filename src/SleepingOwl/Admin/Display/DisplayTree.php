<?php namespace SleepingOwl\Admin\Display;

use AdminTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Input;
use Route;
use SleepingOwl\Admin\Admin;
use SleepingOwl\Admin\AssetManager\AssetManager;
use SleepingOwl\Admin\Columns\Column;
use SleepingOwl\Admin\Interfaces\DisplayInterface;
use SleepingOwl\Admin\Interfaces\WithRoutesInterface;
use SleepingOwl\Admin\Repository\TreeRepository;

class DisplayTree implements Renderable, DisplayInterface, WithRoutesInterface
{

	protected $class;
	protected $with = [];
	protected $repository;
	protected $reorderable = true;
	protected $parameters = [];
	protected $value = 'title';
	protected $parentField = 'parent_id';
	protected $orderField = 'order';
	protected $maxDepth = 5;
	protected $rootParentId = null;
	protected $apply;
	protected $scopes;
	protected $seperator = '-';

	public function __construct($class=null) {
		if ( !is_null ( $class ) ) {
			$this->setClass($class);
		}
	}

	public function setClass($class)
	{
		if (is_null($this->class))
		{
			$this->class = $class;
		}
	}

	public function with($with = null)
	{
		if (is_null($with))
		{
			return $this->with;
		}
		if ( ! is_array($with))
		{
			$with = func_get_args();
		}
		$this->with = $with;
		return $this;
	}

	public function initialize()
	{
		AssetManager::addScript('admin::default/plugins/jquery-nestable/jquery.nestable.js');
		AssetManager::addScript('admin::default/scripts/jquery-nestable/init.js');
		AssetManager::addStyle('admin::default/plugins/jquery-nestable/jquery.nestable.css');

		$this->repository = new TreeRepository($this->class);
		$this->repository->with($this->with());
		$this->repository->apply($this->apply());
		$this->repository->scope($this->scope());

		Column::treeControl()->initialize();
	}

	public function reorderable($reorderable = null)
	{
		if (is_null($reorderable))
		{
			return $this->reorderable;
		}
		$this->reorderable = $reorderable;
		return $this;
	}

	public function repository()
	{
		$this->repository->parentField($this->parentField());
		$this->repository->orderField($this->orderField());
		$this->repository->rootParentId($this->rootParentId());
		return $this->repository;
	}

	public function parameters($parameters = null)
	{
		if (is_null($parameters))
		{
			return $this->parameters;
		}
		$this->parameters = $parameters;
		return $this;
	}

	public function model()
	{
		return Admin::model($this->class);
	}

	public function render()
	{
		$params = [
			'items'       => $this->repository()->tree(),
			'reorderable' => $this->reorderable(),
			'url'         => Admin::model($this->class)->displayUrl(),
			'value'       => $this->value(),
			'creatable'   => ! is_null($this->model()->create()),
			'createUrl'   => $this->model()->createUrl($this->parameters() + \Request::all()),
			'controls'    => [Column::treeControl()],
			'seperator'   => $this->seperator(),
			'maxdepth'    => $this->maxdepth(),
		];
		return view(AdminTemplate::view('display.tree'), $params);
	}

	function __toString()
	{
		return (string)$this->render();
	}

	public static function registerRoutes()
	{
		Route::post('{adminModel}/reorder', function ($model)
		{
			$data = \Request::input('data');
			$model->display()->repository()->reorder($data);
		});
	}

	public function value($value = null)
	{
		if (is_null($value))
		{
			return $this->value;
		}
		$this->value = $value;
		return $this;
	}

	public function parentField($parentField = null)
	{
		if (is_null($parentField))
		{
			return $this->parentField;
		}
		$this->parentField = $parentField;
		return $this;
	}

	public function orderField($orderField = null)
	{
		if (is_null($orderField))
		{
			return $this->orderField;
		}
		$this->orderField = $orderField;
		return $this;
	}

	public function rootParentId($rootParentId = null)
	{
		if (func_num_args() == 0)
		{
			return $this->rootParentId;
		}
		$this->rootParentId = $rootParentId;
		return $this;
	}

	public function apply($apply = null)
	{
		if (is_null($apply))
		{
			return $this->apply;
		}
		$this->apply = $apply;
		return $this;
	}

	public function scope($scope = null)
	{
		if (is_null($scope))
		{
			return $this->scopes;
		}
		$this->scopes[] = func_get_args();
		return $this;
	}

	public function seperator($seperator = null)
	{
		if (is_null($seperator))
		{
			return $this->seperator;
		}

		$this->seperator = $seperator;
		return $this;
	}

	public function maxdepth($maxDepth = null)
	{
		if (is_null($maxDepth)) {
			return $this->maxDepth;
		}
		$this->maxDepth = $maxDepth;

		return $this;
	}

}