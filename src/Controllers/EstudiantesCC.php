<?php
namespace Controllers;

use Controllers\PublicController;
use Dao\EstudiantesCC as EstudiantesDao;
use Utilities\Context;
use Views\Renderer;

class EstudiantesCC extends PublicController
{
    private $partialNombre="";
    private $especialidad="";
    private $orderBy="";
    private $orderDescending=false;
    private $pageNumber=1;
    private $itemsPerPage=10;
    private $viewData=[];

    public function run(): void
    {
        $this->partialNombre = $_GET["partialNombre"] ?? "";
        $this->especialidad = $_GET["especialidad"] ?? "";
        $this->orderBy = $_GET["orderBy"] ?? "";
        $this->orderDescending = boolval($_GET["orderDescending"] ?? false);
        $this->pageNumber = intval($_GET["pageNum"] ?? 1);

        $result = EstudiantesDao::getEstudiantes(
            $this->partialNombre,
            $this->especialidad,
            $this->orderBy,
            $this->orderDescending,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $this->viewData["estudiantes"] = $result["estudiantes"];
        $this->viewData["total"] = $result["total"];
        $this->viewData["pages"] = ceil($result["total"]/$this->itemsPerPage);
        $this->viewData["pageNum"] = $this->pageNumber;
        $this->viewData["partialNombre"] = $this->partialNombre;

        Renderer::render("estudiantescc", $this->viewData);
    }
}
