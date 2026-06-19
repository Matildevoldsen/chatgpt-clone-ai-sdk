# Laravel Meetup India Chat

Small Laravel AI SDK demo app for a meetup presentation. It uses Vue, Inertia,
shadcn-vue components, Azure OpenAI, streamed responses, and saved chat history.

## Stack

- Laravel 13
- Vue 3 + Inertia
- shadcn-vue
- Laravel AI SDK
- `@laravel/stream-vue`
- Azure OpenAI deployments: `gpt-5.4` and `gpt-5.4-mini`

## Run the Demo

Install dependencies and prepare the database:

```bash
composer install
npm install
php artisan migrate
npm run build
```

Create or update the local demo user:

```bash
php artisan tinker --execute='App\Models\User::query()->updateOrCreate(["email"=>"demo@example.com"], ["name"=>"Demo User", "password"=>Illuminate\Support\Facades\Hash::make("password"), "email_verified_at"=>now()]);'
```

Run the app through Laravel's CLI server:

```bash
php artisan serve --host=127.0.0.1 --port=8010
```

Keep the Herd URL pointed at that server:

```bash
herd proxy laravel-meetup-india http://127.0.0.1:8010
```

Open:

```text
http://laravel-meetup-india.test
```

Demo login:

```text
demo@example.com
password
```

## Streaming Note

The chat endpoint uses the official Laravel AI SDK stream API and Laravel's
`response()->stream()` response. Herd's normal PHP-FPM runtime currently hits a
Laravel AI SDK streaming issue where `Agent::stream()` may emit only `StreamEnd`
under FPM while working under CLI. The Herd proxy keeps the nice `.test` URL but
serves the app through `php artisan serve`, where the SDK stream emits deltas
correctly.

When Laravel AI ships an FPM streaming fix, remove the proxy and use the normal
Herd link again:

```bash
herd unproxy laravel-meetup-india
herd link laravel-meetup-india
```
