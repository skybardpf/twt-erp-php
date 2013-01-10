<?php

class EntitiesController extends Controller
{
	public function init()
	{
		$this->menu_elem = 'legal.entities';
		parent::init();
	}


	public function actionAdd()
	{
		$this->render('add');
	}

	public function actionDelete()
	{
		$this->render('delete');
	}

	public function actionIndex()
	{
		$entities =
		$this->render('index');
	}

	public function actionShow()
	{
		$this->render('show');
	}

	public function actionUpdate()
	{
		$this->render('update');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}