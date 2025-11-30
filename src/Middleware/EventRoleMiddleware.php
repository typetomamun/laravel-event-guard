<?php
namespace EventGuard\Middleware;
class EventRoleMiddleware { public function handle($r,$n){ return $n($r); }}
