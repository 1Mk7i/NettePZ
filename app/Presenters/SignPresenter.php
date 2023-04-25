<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', "Ім'я користувача:")
            ->setRequired("Будь ласка, введіть ваше І'мя.");

        $form->addPassword('password', 'Пароль:')
            ->setRequired('Будь ласка, введіть ваш пароль.');

        $form->addSubmit('send', 'Увійти');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;

    }

    public function signInFormSucceeded(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Неправильний логін або пароль.');
        }
    }
    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Ви увійшли.');
        $this->redirect('Home:');
    }

    protected function createComponentSignOutForm(): Form
    {
        $form = new Form;
        $form->addSubmit('send', 'Вийти');

        $form->onSuccess[] = [$this, 'signOutFormSucceeded'];
        return $form;
    }

    public function signOutFormSucceeded(Form $form, \stdClass $data): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Ви вийшли.');
        $this->redirect('Home:');
    }

}

