<?php
/**
 * PharmacistSettingsController - the settings screen.
 *
 * Saving is NOT implemented yet: save() only checks the request and says so.
 * Nothing is written anywhere.
 */
class PharmacistSettingsController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** The tabs that exist as files in views/pharmacist/settings/tabs/. */
    private const TABS = ['account', 'security', 'notifications'];

    /** GET /pharmacist/settings?tab=account */
    public function index(): void
    {
        $this->requireRole('Pharmacist');

        $this->render('settings.index', [
            'active_tab'  => $this->tab((string) $this->input('tab', 'account')),
            'page_title'  => 'Settings',
            'active_page' => 'settings',
            'page_css'    => 'settings.css',
        ]);
    }

    /** POST /pharmacist/settings */
    public function save(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Pharmacist');

        $tab = $this->tab($this->text('tab', 'account'));

        $this->flash('error', 'Settings saving is not implemented yet.');
        $this->redirect('/pharmacist/settings?tab=' . $tab);
    }

    /** Only a tab from the fixed list is accepted; anything else is 'account'. */
    private function tab(string $tab): string
    {
        return in_array($tab, self::TABS, true) ? $tab : 'account';
    }
}
