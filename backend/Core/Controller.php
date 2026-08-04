<?php

class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);

        require INCLUDES_PATH . '/header.php';
        require INCLUDES_PATH . '/navbar.php';
        require INCLUDES_PATH . '/sidebar.php';

        require VIEWS_PATH . '/' . $view . '.php';

        require INCLUDES_PATH . '/footer.php';
    }

    protected function model(string $model)
    {
        require MODELS_PATH . "/{$model}.php";

        return new $model();
    }
}