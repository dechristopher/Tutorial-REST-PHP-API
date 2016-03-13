<?php

namespace App\Presenters;

class BasePresenter
{
  protected $data;

  public function __construct($data = [])
  {
    $this->data = (object) $data;
  }

  public function present()
  {
    return json_encode($this->format());
  }
}
