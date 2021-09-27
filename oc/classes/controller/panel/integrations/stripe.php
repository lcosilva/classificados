<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Stripe extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Stripe');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'stripe_private', Core::post('stripe_private'));
            Model_Config::set_value('payment', 'stripe_public', Core::post('stripe_public'));
            Model_Config::set_value('payment', 'stripe_address', Core::post('stripe_address') ?? 0);
            Model_Config::set_value('payment', 'stripe_alipay', Core::post('stripe_alipay') ?? 0);
            Model_Config::set_value('payment', 'stripe_ideal', Core::post('stripe_ideal') ?? 0);
            Model_Config::set_value('payment', 'stripe_3d_secure', Core::post('stripe_3d_secure') ?? 0);
            Model_Config::set_value('payment', 'stripe_legacy', Core::post('stripe_legacy') ?? 0);
            Model_Config::set_value('payment', 'stripe_connect', Core::post('stripe_connect') ?? 0);
            Model_Config::set_value('payment', 'stripe_connect_legacy', Core::post('stripe_connect_legacy') ?? 0);
            Model_Config::set_value('payment', 'stripe_escrow', Core::post('stripe_escrow') ?? 0);
            Model_Config::set_value('payment', 'stripe_clientid', Core::post('stripe_clientid'));
            Model_Config::set_value('payment', 'stripe_appfee', Core::post('stripe_appfee'));
            Model_Config::set_value('payment', 'stripe_appfee_fixed', Core::post('stripe_appfee_fixed'));
            Model_Config::set_value('payment', 'stripe_cancel_orders_after_n_days', Core::post('stripe_cancel_orders_after_n_days'));
            Model_Config::set_value('payment', 'stripe_connected_account_mandatory', Core::post('stripe_connected_account_mandatory'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'stripe']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/stripe', [
        ]);
    }
}
