<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class AboutPresenter extends Nette\Application\UI\Presenter
{
    private static ?AboutPresenter $instance = null;

    public static function getInstance(): AboutPresenter
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function renderShow(): void
    {
        $this->getTemplate()->bobrik = 'BOBER';
    }
}


