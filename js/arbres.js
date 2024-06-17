'use strict'

helloWorld();
ajaxRequest('GET', 'php/request.php', helloWorld);

function helloWorld()
{
  console.log('Hello World');
}