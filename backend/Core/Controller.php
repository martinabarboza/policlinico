<?php

class Controller
{
    /**
     * Renderiza una vista utilizando el layout indicado.
     *
     * Layouts:
     * - landing: header/footer de la landing
     * - app: header/navbar/sidebar/footer del dashboard
     * - blank: solamente la vista
     */
    protected function view(
        string $view,
        array $data = [],
        string $layout = 'app'
        //COMO LLAMAR "$this->view('landing/inicio', [], 'landing');"
    ): void {
        
        $viewFile = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("Vista no encontrada: {$viewFile}");
        }

        extract($data);

        switch ($layout) {

            case 'landing':

                require INCLUDES_PATH . '/header_Landing.php';

                require $viewFile;

                require INCLUDES_PATH . '/footer_Landing.php';


                break;


            case 'app':

                require INCLUDES_PATH . '/header_Dashboard.php';
                require INCLUDES_PATH . '/navbar_Dashboard.php';
                require INCLUDES_PATH . '/sidebar_Dashboard.php';

                require $viewFile;

                require INCLUDES_PATH . '/footer_Dashboard.php';

                break;


            case 'blank':

                require $viewFile;

                break;


            default:

                throw new Exception(
                    "Layout '{$layout}' no reconocido."
                );
        }
    }


    /**
     * Instancia un modelo.
     */
    protected function model(string $model)
    {
        $modelFile = MODELS_PATH . "/{$model}.php";

        if (!file_exists($modelFile)) {
            throw new Exception("Modelo no encontrado: {$modelFile}");
        }

        require_once $modelFile;

        return new $model();
    }

}

