<?php
namespace EventGuard\Middleware;
class EventPermissionMiddleware { public function handle($r,$n){ return $n($r); }}
