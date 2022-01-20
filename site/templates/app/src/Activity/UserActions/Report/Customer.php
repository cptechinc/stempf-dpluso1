<?php namespace App\Activity\UserActions\Report;
use ProcessWire\Wire404Exception;
//
use Dplus\Dpluso\UserActions\CustomerActionsPanel;
// Controllers
use Mvc\Controllers\Controller;

class Customer extends Controller {
	public static function index($data = null) {
		self::sanitizeParametersShort($data, ['custID|text', 'shiptoID|text']);
		if (customerShiptoExists($data->custID) === false) {
			throw new Wire404Exception('Customer Not Found');
		}
		return self::customer($data);
	}

	private static function customer($data) {
		$actionpanel = new CustomerActionsPanel(session_id(), self::pw('page')->fullURL, self::pw('input'));
		$actionpanel->set_customer($data->custID, $data->shiptoID);
		return self::pw('config')->twig->render('activity/user-actions/report/customer/display.twig', ['actionpanel' => $actionpanel]);
	}
}
