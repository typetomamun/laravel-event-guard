<?php
namespace EventGuard\Middleware;
class RoleMiddleware { public function handle($r,$n){ return $n($r); }}
