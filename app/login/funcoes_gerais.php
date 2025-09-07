<?php
function formatarMoeda($valor) 
{
    $formatado = number_format($valor, 2, ",", ".");
    return "R$ " . $formatado;
}

function formatarData($data) 
{
    $dataFormatada = date('d/m/Y', strtotime($data));
    return $dataFormatada;
}