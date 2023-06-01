<?php

declare(strict_types=1);

namespace App\Presenters;

use JetBrains\PhpStorm\NoReturn;
use Nette;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private readonly Nette\Database\Explorer $database
    ) {
        parent::__construct();
    }

    public function renderIndex(): void // this function is called when the page is loaded
    {
        $this->getTemplate()->title = 'Home';
        $this->getTemplate()->message = 'Hello, it\'s Home page, rendered by HomePresenter'; // set the message

        $this->getTemplate()->users = $this->database
            ->table('users')
            ->order('user_id ASC')
            ->limit(20);

        $userID = $this->getUser()->getIdentity() ? $this->getUser()->getIdentity()->getId(): null;

        $result = $this->database
            ->query('SELECT t1.group_id, t1.group_name
                         FROM `groups` t1
                         LEFT JOIN user_groups t2 ON t1.group_id = t2.group_id
                         LEFT JOIN users u on u.user_id = t2.user_id
                         WHERE u.user_id = ?;', $userID);
        $this->getTemplate()->result = $result;
    }

    /**
     * @return void
     */
    public function renderAbout(): void
    {
        $this
            ->getTemplate()
            ->title = 'About';
        $this
            ->getTemplate()
            ->message = 'Hello, it\'s About page, rendered by HomePresenter';
    }

    public function createComponentUploadForm(): Form
    {
        $form = new Form();

        $form->addUpload('file', 'Выберите файл:')
            ->setRequired('Выберите файл для загрузки.');

        $form->addSubmit('submit', 'Загрузить');

        $form->onSuccess[] = [$this, 'uploadFormSucceeded'];

        return $form;
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] public function uploadFormSucceeded(Form $form, \stdClass $values)
    {
        /** @var FileUpload $file */
        $file = $values->file;

        if ($file->isOk()) {
            $file->move('./uploads/' . $file->name);

            $this->flashMessage('Файл успешно загружен', 'success');
        } else {
            $this->flashMessage('Произошла ошибка при загрузке файла', 'danger');
        }

        $this->redirect('this');
    }

    /**
     * @return void
     */
    public function renderContacts(): void
    {
        $this->getTemplate()->title = 'Contacts';
        $this->getTemplate()->message = 'Hello, it\'s Contacts page, rendered by HomePresenter';
    }
}
