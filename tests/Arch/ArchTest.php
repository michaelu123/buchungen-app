<?php


arch()
  ->expect(['die', 'dd', 'dump'])
  ->not->toBeUsed();


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
// App\Mail wants Mails to implement ShouldQueue, which we do not want
arch()->preset()->laravel()->ignoring(['App\Providers', 'App\Mail']);
arch()->preset()->security()->ignoring('unserialize');
