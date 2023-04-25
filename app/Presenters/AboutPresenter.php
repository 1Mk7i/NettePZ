<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class AboutPresenter extends Nette\Application\UI\Presenter
{
    public function renderShow() : void{
        $this->getTemplate()->bobrik = 'BOBER';
    }
}
