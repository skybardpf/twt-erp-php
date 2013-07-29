<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class DefaultController extends Controller {
    /**
     * Перенаправляем на калькулятор.
     */
    public function actionIndex() {
        $this->redirect($this->createUrl('/legal/my_organizations'));
	}
}