<?php /* @var $this Controller */

Yii::app()->clientScript->registerCssFile(CHtml::asset(Yii::app()->basePath.'/../static/css/main.css'));

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=Yii::app()->language?>" lang="<?=Yii::app()->language?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?=Yii::app()->language?>" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container">
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?=Yii::app()->homeUrl?>"><?=Yii::app()->name?></a>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="span3 left-menu">
			<?php
			$items = array(
				'main'           => array('label' => 'Главная', 'url' => Yii::app()->homeUrl),
				'legal.entities' => array('label' => 'Юридические лица', 'url' => $this->createUrl('/legal/entities/'), 'linkOptions' => array('style' => 'background: #DFD')),
				'legal.Counterparties_groups' => array('label' => 'Группы контрагентов', 'url' => $this->createUrl('/legal/counterparties_groups/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.Banks' => array('label' => 'Банки', 'url' => $this->createUrl('/legal/banks/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.Countries' => array('label' => 'Страны юрисдикции', 'url' => $this->createUrl('/legal/countries/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.LEDocument_type' => array('label' => 'Типы документов', 'url' => $this->createUrl('/legal/ledocument_type/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.Users' => array('label' => 'Пользователи', 'url' => $this->createUrl('/legal/users/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.FoundingDocument' => array('label' => 'Учредительные документы', 'url' => $this->createUrl('/legal/founding_document/'), 'linkOptions' => array('style' => 'background: #DFD', 'title' => 'Файлы')),
/* Внимание Метод с ошибкой	*/ 'legal.PowerAttorneyLE' => array('label' => 'Доверенности', 'url' => $this->createUrl('/legal/power_attorney_le/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => 'Файлы, виды договоров.')),
				'legal.FreeDocument' => array('label' => 'Свободные документы', 'url' => $this->createUrl('/legal/free_documents/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => 'Файлы, проблема с редактированием.')),
/* Внимание Метод с ошибкой	*/ 'legal.Pegroup' => array('label' => 'Группы физ.лиц', 'url' => $this->createUrl('/legal/pegroup/'), 'linkOptions' => array('style' => 'background: #F22', 'title' => 'Ошибки 1C при сохранении.')),
				'legal.Individual' => array('label' => 'Физические лица', 'url' => $this->createUrl('/legal/individuals/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => 'Зависит от группы физ.лиц')),
				'legal.InterestedPerson' => array('label' => 'Заинтересованное лицо', 'url' => $this->createUrl('/legal/interested_persons/'), 'linkOptions' => array('style' => 'background: #f22', 'title' => 'Проблемы с идентификаторами лица')),
/* Внимание Метод с ошибкой */ 'legal.Beneficiary' => array('label' => 'Бенефициары', 'url' => $this->createUrl('/legal/beneficiary/'), 'linkOptions' => array('style' => 'background: #F22', 'title' => 'Неправильный формат валюты')),
				'legal.Currencies' => array('label' => 'Валюты', 'url' => $this->createUrl('/legal/currencies/'), 'linkOptions' => array('style' => 'background: #dFd')),
				'legal.DDSArticles' => array('label' => 'Статьи движения денежных стредств', 'url' => $this->createUrl('/legal/ddsarticles/'), 'linkOptions' => array('style' => 'background: #dFd')),
/* Внимание Метод с ошибкой */ 'legal.SettlementAccountManager' => array('label' => 'Персона управляющая расчетным счетом', 'url' => $this->createUrl('/legal/sa_manager/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => 'Зависит от физ.лиц')),
/* Внимание Метод с ошибкой */ 'legal.Events' => array('label' => 'Мероприятия', 'url' => $this->createUrl('/legal/events/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => 'Отсутствует поле Юр.Лицо, файлы')),
/* Внимание Метод с ошибкой */ 'legal.SettlementAccount' => array('label' => 'Расчетные счета', 'url' => $this->createUrl('/legal/settlement_accounts/'), 'linkOptions' => array('style' => 'background: #ddd', 'title' => 'Возвращаемые данные не соответствуют ТЗ')),
/* Внимание Метод с ошибкой */ 'legal.SignatoryLE' => array('label' => 'Подписанты', 'url' => $this->createUrl('/legal/signatories/'), 'linkOptions' => array('style' => 'background: #ddd', 'title' => 'Элементы с пустым id.')),
/* Внимание Метод с ошибкой */ 'legal.ContractNature' => array('label' => 'Характеры договоров', 'url' => $this->createUrl('/legal/contract_natures/'), 'linkOptions' => array('style' => 'background: #f99', 'title' => 'Нет поля вид договора, метод с видами договоров возвращает пустой ответ.')),
/* Внимание Метод с ошибкой */ 'legal.ContractAdditionalParameters' => array('label' => 'Дополнительные параметры договоров', 'url' => $this->createUrl('/legal/contract_parameters/'), 'linkOptions' => array('style' => 'background: #f99', 'title' => 'Нужно объяснение принимаемых методом list параметров.')),
/* Внимание Метод с ошибкой */ 'legal.Contracts' => array('label' => 'Договора', 'url' => $this->createUrl('/legal/contracts/'), 'linkOptions' => array('style' => 'background: #f99', 'title' => 'Нужно объяснение принимаемых методом list параметров.')),
/* Внимание Метод с ошибкой	*/ 'legal.Calc' => array('label' => 'Страховой калькулятор', 'url' => $this->createUrl('/legal/calc/'), 'linkOptions' => array('style' => 'background: #F99', 'title' => '1С доделывается получение сумм страховых взносов и нужно согласование по печати счета.')),
			);
			if (isset($items[$this->menu_elem])) {
				$items[$this->menu_elem]['active'] = true;
			}
			$this->widget('bootstrap.widgets.TbMenu', array(
					'type'      => 'tabs', // '', 'tabs', 'pills' (or 'list')
				'stacked'   => true, // whether this is a stacked menu
				'items'     => array_values($items)
			));
			unset($items); ?>
		</div>
		<div class="span9">
				<?php
				/*$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
					'links' => $this->breadcrumbs
				));*/?>
			<?=$content?>
		</div>
	</div>
</div>

</body>
</html>
