<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }
    public function renderShow(int $postId): void
    {
        $post = $this->database
            ->table('posts')
            ->get($postId);
        if (!$post) {
            $this->error('Страница не найдена!');
        }
        $this->template->post = $post;
        $this->template->comments = $post->related('comments')->order('created_at');

        $this->template->post = $post;
    }
    protected function createComponentCommentForm(): Form
    {
        $form = new Form; // означає Nette\Application\UI\Form

        $form->addText('name', "Ваше ім'я:")
            ->setRequired();

        $form->addEmail('email', 'Імейл:');

        $form->addTextArea('content', 'Коментар:')
            ->setRequired();

        $form->addSubmit('send', 'Відправити');

        return $form;
    }
        public function commentFormSucceeded(\stdClass $data): void
    {
        $postId = $this->getParameter('postId');

        $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name' => $data->name,
            'email' => $data->email,
            'content' => $data->content,
        ]);

        $this->flashMessage('Спасибо за комментарий!', 'success');
        $this->redirect('this');
    }


}