<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToLoadLoginPage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Iniciar Sesión'); // Assuming this text exists on the login page
    }
}
