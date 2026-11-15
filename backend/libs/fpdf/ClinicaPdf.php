<?php

require_once __DIR__ . '/fpdf.php';

class ClinicaPdf extends FPDF
{
    private string $tituloDocumento = 'HISTORIA CLINICA';

    public function __construct(string $titulo, string $tituloDocumento)
    {
        parent::__construct();
        $this->tituloDocumento = $tituloDocumento;
        $this->SetMargins(16, 37, 16);
        $this->SetAutoPageBreak(true, 21);
        $this->SetTitle($titulo, true);
        $this->SetAuthor('Policlinico Veterinario', true);
        $this->AddPage();
    }

    public function Header(): void
    {
        $this->SetFillColor(19, 76, 82);
        $this->Rect(0, 0, 210, 31, 'F');
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(16, 5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(95, 5, 'POLICLINICO VETERINARIO');
        $this->SetFont('Arial', '', 8);
        $this->Cell(83, 5, 'REGISTRO CLINICO', 0, 1, 'R');
        $this->SetXY(16, 13);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 9, $this->tituloDocumento);
        $this->SetDrawColor(104, 198, 187);
        $this->SetLineWidth(0.8);
        $this->Line(16, 28, 194, 28);
        $this->SetY(37);
    }

    public function Footer(): void
    {
        $this->SetY(-16);
        $this->SetDrawColor(213, 224, 224);
        $this->Line(16, $this->GetY(), 194, $this->GetY());
        $this->SetY(-14);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(100, 113, 116);
        $this->Cell(0, 8, 'Generado el ' . date('d/m/Y H:i') . '  |  Pagina ' . $this->PageNo(), 0, 0, 'R');
    }

    public function agregarSeccion(string $titulo, array $lineas): void
    {
        if ($this->GetY() > $this->GetPageHeight() - 42) {
            $this->AddPage();
        }

        $x = $this->GetX();
        $y = $this->GetY();
        $ancho = $this->GetPageWidth() - 32;
        $this->SetFillColor(231, 242, 242);
        $this->Rect($x, $y, $ancho, 8, 'F');
        $this->SetFillColor(31, 117, 128);
        $this->Rect($x, $y, 1.5, 8, 'F');
        $this->SetXY($x + 4, $y + 1);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(25, 88, 96);
        $this->Cell(0, 6, $this->convertirTexto($titulo), 0, 1, 'L');
        $this->SetY($y + 10);

        foreach ($lineas as $etiqueta => $valor) {
            $valorTexto = $this->formatearValor($valor);
            if ($valorTexto === '') {
                continue;
            }

            if ($this->GetY() > $this->GetPageHeight() - 27) {
                $this->AddPage();
            }
            $this->SetFont('Arial', 'B', 8);
            $this->SetTextColor(96, 111, 114);
            $this->Cell(54, 5, $this->convertirTexto((string) $etiqueta), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->SetTextColor(39, 49, 51);
            $this->MultiCell(0, 5, $this->convertirTexto($valorTexto), 0, 'L');
            $this->Ln(1);
        }
        $this->Ln(3);
    }

    public function agregarRegistros(string $titulo, $registros): void
    {
        if (!is_array($registros)) {
            return;
        }

        $lineas = [];
        foreach ($registros as $indice => $registro) {
            if (!is_array($registro)) {
                continue;
            }
            foreach ($registro as $campo => $valor) {
                if (
                    $campo === 'id'
                    || $valor === null
                    || !is_scalar($valor)
                    || trim((string) $valor) === ''
                ) {
                    continue;
                }
                $nombreCampo = count($registros) > 1
                    ? 'Registro ' . ($indice + 1) . ' - ' . $campo
                    : (string) $campo;
                $lineas[$nombreCampo] = $valor;
            }
        }

        if ($lineas !== []) {
            $this->agregarSeccion($titulo, $lineas);
        }
    }

    public function convertirTexto($valor): string
    {
        $texto = trim(is_scalar($valor) ? (string) $valor : '');
        if ($texto === '') {
            return 'No registrado';
        }

        $convertido = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
        if ($convertido === false) {
            throw new RuntimeException('No se pudo convertir un dato clínico para el PDF.');
        }

        return $convertido;
    }

    private function formatearValor($valor): string
    {
        $texto = is_scalar($valor) ? trim((string) $valor) : '';
        if (preg_match('/^\d{4}-\d{2}-\d{2}(?:[ T]\d{2}:\d{2}(?::\d{2})?)?$/', $texto) === 1) {
            $marcaTiempo = strtotime($texto);
            if ($marcaTiempo !== false) {
                return date(strpos($texto, ':') === false ? 'd/m/Y' : 'd/m/Y H:i', $marcaTiempo);
            }
        }

        return $texto;
    }
}
