# Laravel EventGuard

[![Latest Version on Packagist](https://img.shields.io/packagist/v/typetomamun/laravel-event-guard.svg?style=flat-square)](https://packagist.org/packages/typetomamun/laravel-event-guard)
[![Total Downloads](https://img.shields.io/packagist/dt/typetomamun/laravel-event-guard.svg?style=flat-square)](https://packagist.org/packages/typetomamun/laravel-event-guard)
[![License](https://img.shields.io/packagist/l/typetomamun/laravel-event-guard.svg?style=flat-square)](https://packagist.org/packages/typetomamun/laravel-event-guard)

Multi-tenant permission and role management for Laravel 11 & 12. Perfect for applications where users can create multiple shops, forums, groups, announcements, and more - each with their own isolated roles and permissions.

## Features

- 🏢 **Multi-Tenant Architecture**: Users can own multiple events (shops, forums, groups, etc.)
- 🔐 **Event-Scoped Permissions**: Roles and permissions isolated per event instance
- 👤 **Automatic Owner Detection**: Event owners automatically have all permissions
- 🎯 **Flexible Role System**: Assign roles globally or per event
- 🛡️ **Middleware Support**: Easy route protection with intuitive syntax
- 🎨 **Blade Directives**: Clean permission checks in views
- 🚀 **Zero Conflicts**: All tables and methods prefixed with `egd_`
- ⚡ **Laravel 11 & 12 Compatible**: Built for modern Laravel

## Installation

```bash
composer require typetomamun/laravel-event-guard
```

Publish the config and migrations:

```bash
php artisan vendor:publish --tag=eventguard-config
php artisan vendor:publish --tag=eventguard-migrations
```

Run migrations:

```bash
php artisan migrate
```

## Quick Start

### 1. Add Trait to User Model

```php
use TypeToMamun\LaravelEventGuard\Traits\EGDHasRoles;

class User extends Authenticatable
{
    use EGDHasRoles;
}
```

### 2. Create Event Types

```php
use TypeToMamun\LaravelEventGuard\Models\EGDEventType;

EGDEventType::create([
    'name' => 'Shop',
    'slug' => 'shop',
]);
```

### 3. Create Events

```php
use TypeToMamun\LaravelEventGuard\Models\EGDEvent;

$shop = EGDEvent::create([
    'event_type_id' => $shopType->id,
    'name' => 'My Awesome Shop',
    'slug' => 'my-awesome-shop',
    'owner_id' => auth()->id(),
]);
```

### 4. Assign Roles

```php
// Assign user as manager of a specific shop
$user->egdAssignEventRole($shop, 'manager');

// Give direct permission
$user->egdGiveEventPermission($shop, 'manage products');
```

### 5. Protect Routes

```php
// Only shop managers can access
Route::middleware(['egd.event.role:{shop},manager'])->group(function () {
    Route::get('/shops/{shop}/dashboard', [ShopController::class, 'dashboard']);
});

// Only users with specific permission
Route::middleware(['egd.event.permission:{shop},manage products'])->group(function () {
    Route::post('/shops/{shop}/products', [ProductController::class, 'store']);
});
```

### 6. Use in Blade

```blade
@egdeventowner($shop)
    <a href="/shops/{{ $shop->slug }}/settings">Settings</a>
@endegdeventowner

@egdeventrole($shop, 'manager')
    <button>Manage Staff</button>
@endegdeventrole

@egdeventpermission($shop, 'manage products')
    <button>Add Product</button>
@endegdeventpermission
```

## Documentation

### Available Methods

#### Event-Scoped Methods

```php
// Check if user has role for specific event
$user->egdHasEventRole($shop, 'manager');

// Check if user has permission for specific event
$user->egdHasEventPermission($shop, 'manage products');

// Assign role to user for specific event
$user->egdAssignEventRole($shop, 'manager');

// Give permission to user for specific event
$user->egdGiveEventPermission($shop, 'edit products');

// Remove role from user for specific event
$user->egdRemoveEventRole($shop, 'staff');

// Check if user is event owner
$user->egdIsEventOwner($shop);

// Get all events of a type owned by user
$myShops = $user->egdGetEventsForType('shop');
```

#### Global Methods

```php
// Check global role
$user->egdHasRole('admin');

// Assign global role
$user->egdAssignRole('admin');

// Check global permission
$user->egdHasPermissionTo('manage system');
```

### Middleware

```php
// Event-scoped role middleware
Route::middleware(['egd.event.role:{event},manager'])->group(function () {
    // Routes here
});

// Event-scoped permission middleware
Route::middleware(['egd.event.permission:{event},edit articles'])->group(function () {
    // Routes here
});

// Global role middleware
Route::middleware(['egd.role:admin'])->group(function () {
    // Routes here
});

// Global permission middleware
Route::middleware(['egd.permission:manage system'])->group(function () {
    // Routes here
});
```

### Blade Directives

```blade
{{-- Event-scoped directives --}}
@egdeventowner($event)
    {{-- Content for event owner --}}
@endegdeventowner

@egdeventrole($event, 'manager')
    {{-- Content for managers --}}
@endegdeventrole

@egdeventpermission($event, 'edit products')
    {{-- Content for users with permission --}}
@endegdeventpermission

{{-- Global directives --}}
@egdrole('admin')
    {{-- Content for admins --}}
@endegdrole

@egdpermission('manage system')
    {{-- Content for users with permission --}}
@endegdpermission
```

## Use Cases

### Multi-Shop Platform

```php
// User creates multiple shops
$shop1 = EGDEvent::create([...], 'electronics-store', owner: $user);
$shop2 = EGDEvent::create([...], 'fashion-boutique', owner: $user);

// Assign staff to specific shops
$manager->egdAssignEventRole($shop1, 'manager');
$staff->egdAssignEventRole($shop1, 'staff');

// Permissions are isolated per shop
$manager->egdHasEventPermission($shop1, 'manage products'); // ✅ true
$manager->egdHasEventPermission($shop2, 'manage products'); // ❌ false
```

### Forum Platform

```php
// User creates forum
$forum = EGDEvent::create([...], 'tech-forum', owner: $user);

// Assign moderators
$moderator->egdAssignEventRole($forum, 'moderator');

// Check permissions
if ($moderator->egdHasEventPermission($forum, 'delete posts')) {
    // Can delete posts in this forum
}
```

## Configuration

Publish and customize the config file:

```php
// config/egd_permission.php

return [
    'models' => [
        'event' => \TypeToMamun\LaravelEventGuard\Models\EGDEvent::class,
        'role' => \TypeToMamun\LaravelEventGuard\Models\EGDRole::class,
        // ...
    ],
    
    'event_types' => [
        'shop' => [
            'name' => 'Shop',
            'roles' => ['owner', 'manager', 'staff'],
            'permissions' => ['view', 'edit', 'delete', 'manage products'],
        ],
        // Add your custom event types
    ],
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security-related issues, please email your-email@example.com instead of using the issue tracker.

## Credits

- [TypeToMamun](https://github.com/typetomamun)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Support

- 📧 Email: your-email@example.com
- 🐛 Issues: [GitHub Issues](https://github.com/typetomamun/laravel-event-guard/issues)
- 📖 Documentation: [Full Documentation](https://github.com/typetomamun/laravel-event-guard/wiki)
