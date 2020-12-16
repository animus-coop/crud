### Installation
Todo...
```
php artisan jetstream:install inertia
php artisan migrate
npm install && npm run dev
```

*Creating scaffold from model*
```
php artisan resource-file:create user --fields id,name,email,password
php artisan create:scaffold user
```

*Roles configiguration*
Install the spatie

#Add routes to middleware
```
protected $routeMiddleware = [
    // ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
];
```

#Model
Add the trait HasRoles to user model
```
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    ...
    use HasRoles;
```


```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

#Add the service provider
```
'providers' => [
    // ...
    Spatie\Permission\PermissionServiceProvider::class,
];

#Clear the cache
```
 php artisan optimize:clear
 # or
 php artisan config:clear

*Routes*
Replace the routes on routes/web.php with:
```
Route::get('/', function () {
    return redirect(route('admin.dashboard'));
});

Route::middleware(['auth:sanctum', 'verified', 'role:admin|professional'])->get('/dashboard', function () {
    return view('crud::admin.dashboard.index');
})->name('admin.dashboard');
```


##Commands
Todo...