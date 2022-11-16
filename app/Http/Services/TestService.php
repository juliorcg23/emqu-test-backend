<?php

namespace App\Http\Services;

use Acamposm\Ping\Ping;
use Acamposm\Ping\PingCommandBuilder;

class TestService {
  protected $command;

  public function setAttempts(int $attempts) {
    $this->command->count($attempts);
    return $this;
  }

  public function ping(string $ip) {
    $this->command = new PingCommandBuilder($ip);
    return $this;
  }

  public function run() {
    return (new Ping($this->command))->run();
  }
}
