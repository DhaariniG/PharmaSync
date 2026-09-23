<?php

/**
 * Profile page: personal details, the account holder's health profile,
 * saved addresses, and family profiles.
 *
 * Two entities get the full four CRUD operations here:
 *
 *   Family member   create  addFamilyMember()      POST /customer/profile/add-member
 *                   read    show(), editMember()   GET  /customer/profile[/member/{id}]
 *                   update  updateFamilyMember()   POST /customer/profile/update-member/{id}
 *                   delete  deleteFamilyMember()   POST /customer/profile/delete-member/{id}
 *
 *   Address         create  addAddress()           POST /customer/profile/add-address
 *                   read    show()                 GET  /customer/profile
 *                   update  updateAddress()        POST /customer/profile/update-address/{id}
 *                   delete  deleteAddress()        POST /customer/profile/delete-address/{id}
 *
 * Allergies and chronic conditions are held per family member, so every
 * patient has their own list. addAllergy() and addCondition() are the two
 * shortcuts used by the Health Profile card at the top of the page; they
 * write to the 'Self' member, which is the account holder's own record.
 */
class CustomerProfileController extends Controller
{
    protected string $viewBase = 'customer';

    /* ==================================================================
     * READ
     * ================================================================== */

    public function show(): void
    {
        $this->requireRole('Customer');

        $user    = $this->currentUser();
        $userId  = (int) ($user['id'] ?? 0);
        $members = new FamilyMember();

        $this->render('profile.index', [
            'user'        => $user,
            'addresses'   => (new Address())->forUser($userId),
            'members'     => $members->forUser($userId),
            'selfMember'  => $members->accountHolder($userId),
        ]);
    }

    /** One family member, with their own allergies and conditions. */
    public function editMember($id): void
    {
        $this->requireRole('Customer');

        $userId = (int) ($this->currentUser()['id'] ?? 0);
        $member = (new FamilyMember())->find($userId, (int) $id);

        if (!$member) {
            $this->flash('error', 'That family profile could not be found.');
            $this->redirect('/customer/profile');
            return;
        }

        $this->render('profile.member', [
            'member'     => $member,
            'allergies'  => FamilyMember::allergiesOf($member),
            'conditions' => FamilyMember::conditionsOf($member),
        ]);
    }

    /* ==================================================================
     * Account details
     * ================================================================== */

    public function update(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        // Mock: update the session copy only (nothing persists past logout
        // since there's no DB yet).
        $user = $this->currentUser();
        $user['name']  = trim((string) $this->input('name', $user['name']));
        $user['phone'] = trim((string) $this->input('phone', $user['phone']));
        $_SESSION['user'] = $user;

        $this->flash('success', 'Profile updated.');
        $this->redirect('/customer/profile');
    }

    /* ==================================================================
     * Family members - CREATE, UPDATE, DELETE
     * ================================================================== */

    /** Add someone the customer orders medicine for (father, child, and so on). */
    public function addFamilyMember(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId = (int) ($this->currentUser()['id'] ?? 0);
        $name   = $this->text('member_name');

        if ($name === '') {
            $this->flash('error', 'Please enter a name for the family member.');
            $this->redirect('/customer/profile');
            return;
        }

        $member = (new FamilyMember())->create($userId, [
            'name'          => $name,
            'relationship'  => $this->text('relationship', 'Other'),
            'date_of_birth' => $this->text('date_of_birth'),
            'notes'         => $this->text('notes'),
        ]);

        $this->flash('success', $member['name'] . ' added to your family profiles.');
        $this->redirect('/customer/profile/member/' . (int) $member['id']);
    }

    /** Edit a family member's name, relationship, date of birth or notes. */
    public function updateFamilyMember($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId = (int) ($this->currentUser()['id'] ?? 0);
        $name   = $this->text('member_name');

        if ($name === '') {
            $this->flash('error', 'A family profile needs a name.');
            $this->redirect('/customer/profile/member/' . (int) $id);
            return;
        }

        $updated = (new FamilyMember())->update($userId, (int) $id, [
            'name'          => $name,
            'relationship'  => $this->text('relationship'),
            'date_of_birth' => $this->text('date_of_birth'),
            'notes'         => $this->text('notes'),
        ]);

        if (!$updated) {
            $this->flash('error', 'That family profile could not be updated.');
            $this->redirect('/customer/profile');
            return;
        }

        $this->flash('success', $updated['name'] . '\'s profile was updated.');
        $this->redirect('/customer/profile/member/' . (int) $id);
    }

    /**
     * Remove a family member.
     *
     * Two things block a delete, and both are explained rather than silently
     * ignored: the account holder's own record, and anyone a prescription has
     * already been uploaded for - deleting them would leave a prescription
     * pointing at a patient who no longer exists.
     */
    public function deleteFamilyMember($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId  = (int) ($this->currentUser()['id'] ?? 0);
        $model   = new FamilyMember();
        $memberId = (int) $id;
        $member  = $model->find($userId, $memberId);

        if (!$member) {
            $this->flash('error', 'That family profile could not be found.');
            $this->redirect('/customer/profile');
            return;
        }

        if (!FamilyMember::isDeletable($member)) {
            $this->flash('error', 'Your own profile cannot be removed.');
            $this->redirect('/customer/profile/member/' . $memberId);
            return;
        }

        if ($this->hasPrescriptions($userId, $memberId)) {
            $this->flash('error', $member['name'] . ' has prescriptions on file, so this profile cannot be removed.');
            $this->redirect('/customer/profile/member/' . $memberId);
            return;
        }

        if (!$model->delete($userId, $memberId)) {
            $this->flash('error', 'That family profile could not be removed.');
            $this->redirect('/customer/profile/member/' . $memberId);
            return;
        }

        $this->flash('success', $member['name'] . ' was removed from your family profiles.');
        $this->redirect('/customer/profile');
    }

    /** True when any prescription on this account was uploaded for this patient. */
    private function hasPrescriptions(int $userId, int $memberId): bool
    {
        foreach ((new Prescription())->forUser($userId) as $rx) {
            if ((int) ($rx['patient_id'] ?? 0) === $memberId) {
                return true;
            }
        }
        return false;
    }

    /* ==================================================================
     * Health flags - allergies and conditions, per family member
     * ================================================================== */

    /** Record an allergy or a condition against one member. */
    public function addMemberFlag($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId = (int) ($this->currentUser()['id'] ?? 0);
        $type   = $this->text('type', 'allergy');
        $value  = $this->text('value');

        $this->saveFlag($userId, (int) $id, $type, $value);

        $this->redirect('/customer/profile/member/' . (int) $id);
    }

    /** Remove an allergy or a condition from one member. */
    public function deleteMemberFlag(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId   = (int) ($this->currentUser()['id'] ?? 0);
        $memberId = (int) $this->input('member_id', 0);

        $removed = (new FamilyMember())->deleteFlag($userId, $memberId, (int) $this->input('flag_id', 0));

        $this->flash(
            $removed ? 'success' : 'error',
            $removed ? 'Removed from the health profile.' : 'That entry could not be removed.'
        );

        // Back to whichever page the form was on: the profile page for the
        // account holder, the member page for everyone else.
        header('Location: ' . $this->safeReferer('/customer/profile'));
        exit;
    }

    /**
     * Shortcut used by the Health Profile card on the profile page: the
     * account holder's own allergies are simply the 'Self' member's.
     */
    public function addAllergy(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        $this->addOwnFlag('allergy');
    }

    public function addCondition(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');
        $this->addOwnFlag('condition');
    }

    private function addOwnFlag(string $type): void
    {
        $userId = (int) ($this->currentUser()['id'] ?? 0);
        $self   = (new FamilyMember())->accountHolder($userId);

        if (!$self) {
            $this->flash('error', 'Add your own profile under Family Profiles first.');
            $this->redirect('/customer/profile');
            return;
        }

        $this->saveFlag($userId, (int) $self['id'], $type, $this->text('value'));
        $this->redirect('/customer/profile');
    }

    /** One place for the add-a-flag result message, used by both entry points. */
    private function saveFlag(int $userId, int $memberId, string $type, string $value): void
    {
        $label = $type === 'condition' ? 'condition' : 'allergy';

        if ($value === '') {
            $this->flash('error', 'Enter a ' . $label . ' before adding it.');
            return;
        }

        $flag = (new FamilyMember())->addFlag($userId, $memberId, $type, $value);

        $this->flash(
            $flag ? 'success' : 'error',
            $flag
                ? $flag['value'] . ' added to the health profile.'
                : 'That ' . $label . ' is already recorded.'
        );
    }

    /* ==================================================================
     * Addresses - CREATE, UPDATE, DELETE
     * ================================================================== */

    public function addAddress(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId  = (int) ($this->currentUser()['id'] ?? 0);

        $address = (new Address())->create($userId, [
            'label'      => $this->text('label', 'Other'),
            'line1'      => $this->text('line1'),
            'city'       => $this->text('city'),
            'postcode'   => $this->text('postcode'),
            'phone'      => $this->text('phone'),
            'is_default' => (bool) $this->input('is_default', false),
        ]);

        $this->flash(
            $address ? 'success' : 'error',
            $address
                ? 'Address saved.'
                : 'Please fill in the street address and the city.'
        );

        $this->redirect('/customer/profile');
    }

    public function updateAddress($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId  = (int) ($this->currentUser()['id'] ?? 0);

        $address = (new Address())->update($userId, (int) $id, [
            'label'      => $this->text('label'),
            'line1'      => $this->text('line1'),
            'city'       => $this->text('city'),
            'postcode'   => $this->text('postcode'),
            'phone'      => $this->text('phone'),
            'is_default' => (bool) $this->input('is_default', false),
        ]);

        $this->flash(
            $address ? 'success' : 'error',
            $address
                ? 'Address updated.'
                : 'That address could not be updated. Check the street address and the city.'
        );

        $this->redirect('/customer/profile');
    }

    public function deleteAddress($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId  = (int) ($this->currentUser()['id'] ?? 0);
        $removed = (new Address())->delete($userId, (int) $id);

        $this->flash(
            $removed ? 'success' : 'error',
            $removed ? 'Address removed.' : 'That address could not be removed.'
        );

        $this->redirect('/customer/profile');
    }

    public function setDefaultAddress($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $userId = (int) ($this->currentUser()['id'] ?? 0);
        (new Address())->setDefault($userId, (int) $id);

        $this->flash('success', 'Default delivery address changed.');
        $this->redirect('/customer/profile');
    }
}
