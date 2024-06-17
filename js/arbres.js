'use strict'

helloWorld();
ajaxRequest('GET', 'php/request.php/arbres', helloWorld);

function helloWorld(data)
{
  console.log(data);
}