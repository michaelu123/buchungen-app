<?php


arch()
  ->expect(['die', 'dd', 'dump'])
  ->not->toBeUsed()
  ->ignoring(['App\Models\Codier\Termin']);


arch()
  ->expect('App\Models')
  ->toBeClasses()
  ->toExtend('Illuminate\Database\Eloquent\Model')
  ->toOnlyBeUsedIn(['App', 'Database']);

arch()
  ->expect('App\Http')
  ->toOnlyBeUsedIn('App\Http');

// arch()
//   ->expect('App\*\Traits')
//   ->toBeTraits();

arch()->preset()->php();
arch()->preset()->security()->ignoring('unserialize');
