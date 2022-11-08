<?php

function DECtoDMS($latitude, $longitude)
{
    $latitudeDirection = $latitude < 0 ? 'S': 'N';
    $longitudeDirection = $longitude < 0 ? 'W': 'E';

    $latitudeNotation = $latitude < 0 ? '-': '';
    $longitudeNotation = $longitude < 0 ? '-': '';

    $latitudeInDegrees = floor(abs($latitude));
    $longitudeInDegrees = floor(abs($longitude));

    $latitudeDecimal = abs($latitude)-$latitudeInDegrees;
    $longitudeDecimal = abs($longitude)-$longitudeInDegrees;

    $_precision = 3;
    $latitudeMinutes = round($latitudeDecimal*60,$_precision);
    $longitudeMinutes = round($longitudeDecimal*60,$_precision);

    return sprintf('%s%s° %s %s %s%s° %s %s',
        $latitudeNotation,
        $latitudeInDegrees,
        $latitudeMinutes,
        $latitudeDirection,
        $longitudeNotation,
        $longitudeInDegrees,
        $longitudeMinutes,
        $longitudeDirection
    );

}

var_dump(DECtoDMS());
