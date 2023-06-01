<?php
namespace App\Presenters;

use JetBrains\PhpStorm\NoReturn;
use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }
    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Заголовок:')
            ->setRequired();
        $form->addTextArea('content', 'Содержание:')
            ->setRequired();

        $form->addSubmit('send', 'Сохранить и опубликовать');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }
    public function postFormSucceeded(array $data): void
    {
        $post = $this->database
            ->table('posts')
            ->insert($data);

        $this->flashMessage('Пост опубликован!', 'success');
        $this->redirect('Post:show', $post->id);
    }
    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

    public function renderEdit(int $postId): void
    {
        $post = $this->database
            ->table('posts')
            ->get($postId);

        if (!$post) {
            $this->error('Пост не найден');
        }

        $this->getComponent('postForm')
            ->setDefaults($post->toArray());
    }

}
