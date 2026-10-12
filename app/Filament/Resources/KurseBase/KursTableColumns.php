<?php

namespace App\Filament\Resources\KurseBase;

use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;

class KursTableColumns
{
  public static function getAbgebuchtColumn(): TextColumn
  {
    return TextColumn::make('abgebucht')
      ->formatStateUsing(fn(string $state): string => substr($state, 2))
      // I would like to sort by $state, in the order 0W, 1G, 2Y, 3R (see BaseKurs), but how?
      // ->sortable()
      ->badge()
      ->color(function ($state) {
        switch ($state[1]) {
          case "W":
            return Color::Neutral;
          case "R":
            return Color::Red;
          case "G":
            return Color::Green;
          case "Y":
            return Color::Yellow;
        }
      });
  }
}