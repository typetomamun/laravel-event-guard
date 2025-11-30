<?php
namespace EventGuard\Middleware;
class PermissionMiddleware { public function handle($r,$n){ return $n($r); }}
