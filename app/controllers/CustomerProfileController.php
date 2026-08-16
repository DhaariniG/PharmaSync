<?php

class CustomerProfileController extends Controller
{
    public function show(): void
    {
        $this->requireAuth();

        $user = $this->currentUser();
        $addresses = (new Address())->forUser($user['id'] ?? 0);

        $this->render('profile.index', [
            'user'      => $user,
            'addresses' => $addresses,
            'members'   => (new FamilyMember())->forUser($user['id'] ?? 0),
        ]);
    }

    public function update(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();

        // Mock: update the session copy only (nothing persists past logout
        // since there's no DB yet).
        $user = $this->currentUser();
        $user['name']  = trim((string) $this->input('name', $user['name']));
        $user['phone'] = trim((string) $this->input('phone', $user['phone']));
        $_SESSION['user'] = $user;

        $this->flash('success', 'Profile updated.');
        $this->redirect('/profile');
    }

    public function addAllergy(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        $user = $this->currentUser();
        $value = trim((string) $this->input('value', ''));
        if ($value !== '') {
            $user['allergies'][] = $value;
            $_SESSION['user'] = $user;
        }
        $this->redirect('/profile');
    }

    public function addCondition(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();
        $user = $this->currentUser();
        $value = trim((string) $this->input('value', ''));
        if ($value !== '') {
            $user['conditions'][] = $value;
            $_SESSION['user'] = $user;
        }
        $this->redirect('/profile');
    }

    // Add someone the customer orders medicine for (father, child, and so on).
    public function addFamilyMember(): void
    {
        $this->verifyCsrf();
        $this->requireAuth();

        $user = $this->currentUser();
        $name = trim((string) $this->input('member_name', ''));

        if ($name === '') {
            $this->flash('error', 'Please enter a name for the family member.');
            $this->redirect('/profile');
            return;
        }

        (new FamilyMember())->create($user['id'], [
            'name'          => mb_substr($name, 0, 80),
            'relationship'  => (string) $this->input('relationship', 'Other'),
            'date_of_birth' => (string) $this->input('date_of_birth', ''),
        ]);

        $this->flash('success', $name . ' added to your family profiles.');
        $this->redirect('/profile');
    }
}
