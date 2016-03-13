<?php

namespace App\Presenters;

class ErrorPresenter extends BasePresenter
{
  public function format()
  {
    return [
      'success' => 'false',
      'error' => [
        'message' => $this->data->message,
      ],
    ];
  }
}
