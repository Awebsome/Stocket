<?php namespace AWME\Stocket\Controllers;

use Flash;
use Backend;
use Request;
use Redirect;
use BackendMenu;
use BackendAuth;
use ApplicationException;
use Backend\Classes\Controller;

use AWME\Stocket\Classes\CashRegister;
use AWME\Stocket\Models\Till;

/**
 * Tills Back-end Controller
 */
class Tills extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('AWME.Stocket', 'stocket', 'tills');
    }

    public function index()
    {
        $this->vars['cash_register']['is_open'] = CashRegister::is_open();
        $this->vars['onClosing'] = CashRegister::onClosing();

        // Call the ListController behavior index() method
        $this->asExtension('ListController')->index();
    }

    public function onOpenTill()
    {
        /**
         * Button Validation
         */
        if (Request::input('onOpenTill') != 'openTill')
            throw new ApplicationException('Invalid value');

        $CashRegister = new CashRegister;
        
        if(!$CashRegister->is_open()):
            /**
             * Exec open function
             */
            $CashRegister->open();

            Flash::success(trans('awme.stocket::lang.tills.opening_successfully'));
            return Redirect::to(Backend::url("awme/stocket/tills"));
        else: 
            return  Flash::error(trans('awme.stocket::lang.tills.already_opening'));
        endif;

    }

    public function onCloseTill()
    {
        /**
         * Button Validation
         */
        if (Request::input('onCloseTill') != 'closeTill')
            throw new ApplicationException('Invalid value');

        $CashRegister = new CashRegister;
        
        if($CashRegister->is_open()):
            /**
             * Exec open function
             */
            $CashRegister->close();

            Flash::warning(trans('awme.stocket::lang.tills.closed_successfully'));
            return Redirect::to(Backend::url("awme/stocket/tills"));
        else: 
            return  Flash::error(trans('awme.stocket::lang.tills.already_closed'));
        endif;
    }
}