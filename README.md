# Onebox

### Notes
Tinker
```
Illuminate\Support\Facades\Notification::send(App\Models\User::find(2), new App\Notifications\MinimumStockCount($item))
```

Dev environment
```
npm run build
php artisan queue:listen
php artisan websockets:serve
```