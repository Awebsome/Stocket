<?php namespace AWME\Stocket\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Expenses Back-end Controller
 */
class Expenses extends Controller
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

        BackendMenu::setContext('AWME.Stocket', 'stocket', 'expenses');
    }
}