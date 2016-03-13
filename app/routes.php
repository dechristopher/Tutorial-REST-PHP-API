<?php

$app->route(
  ['GET', 'PUT', 'POST', 'DELETE'],
  '/articles[/{id}]',
  \App\Controllers\ArticleController::class
);
