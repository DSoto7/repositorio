<?php
namespace Controllers;

use Controllers\PublicController;
use Dao\EstudiantesCC;
use Views\Renderer;
use Utilities\Site;
use Utilities\Validators;

class EstudiantesCCForm extends PublicController
{
    private $mode="DSP";
    private $readonly="";
    private $showCommitBtn=true;
    private $viewData=[];
    private $estudiante=[
        "id_estudiante"=>0,
        "nombre"=>"",
        "apellido"=>"",
        "edad"=>0,
        "especialidad"=>""
    ];

    public function run(): void
    {
        $this->mode = $_GET["mode"] ?? "DSP";
        $this->readonly = $this->mode==="DEL" ? "readonly":"";
        $this->showCommitBtn = $this->mode!=="DSP";

        if ($this->mode!=="INS") {
            $this->estudiante = EstudiantesCC::getById(intval($_GET["id_estudiante"]));
        }

        if ($this->isPostBack()) {
            $this->estudiante = $_POST;
            if ($this->mode==="INS") {
                EstudiantesCC::insert(
                    $_POST["nombre"],
                    $_POST["apellido"],
                    intval($_POST["edad"]),
                    $_POST["especialidad"]
                );
            }
            if ($this->mode==="UPD") {
                EstudiantesCC::update(
                    intval($_POST["id_estudiante"]),
                    $_POST["nombre"],
                    $_POST["apellido"],
                    intval($_POST["edad"]),
                    $_POST["especialidad"]
                );
            }
            if ($this->mode==="DEL") {
                EstudiantesCC::delete(intval($_POST["id_estudiante"]));
            }
            Site::redirectTo("index.php?page=EstudiantesCC");
        }

        $this->viewData["estudiante"] = $this->estudiante;
        $this->viewData["mode"] = $this->mode;
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;

        Renderer::render("estudiantesccform", $this->viewData);
    }
}
