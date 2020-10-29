<?php

namespace dtp;

class DailyStatsPoster {

  /** @var bool */
  private $debug_mode;

  /**
   * @param bool $debug_mode
   */
  public function __construct(bool $debug_mode) {
    $this->debug_mode = $debug_mode;
  }

  public function process(): bool {

    $parser = new Parser($this->debug_mode);
    $daily_stats = $parser->parse();

    if (!$daily_stats) {
      return false;
    }

    $base = new Base();
    $base->insert($daily_stats);

    $posting = new Posting($daily_stats);
    return $posting->post();
  }
}
