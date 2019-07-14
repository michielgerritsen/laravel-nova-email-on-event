# E-mail on event [![Build Status](https://travis-ci.org/michielgerritsen/laravel-nova-mail-on-event.svg?branch=master)](https://travis-ci.org/michielgerritsen/laravel-nova-mail-on-event)

This tool allows you to send e-mails from your Laravel Nova dashboard when an event happens in your application. This ensures that you can quickly respond to the needs of your business.

![Creation example](../images/create-example.png?raw=true)

## What is an event?

An event can be just about anything:

- A user just registered.
- The user switched plans.
- You get an API request.
- etc.

Laravel allows you to create event easily. Just run:

```
php artisan make:event MyCustomEvent
```

This will create a new class in `app/events`. Now you can throw the event:

```
event(new MyCustomEvent($user));
```

## What events are included?

Only the events listed under `app_path('Events');`/`app/Events`. Need support for other events too? Create a pull request to add it.

## Installation

Installing is simple. Just use Composer:

```
composer require michielgerritsen/laravel-nova-email-on-event
```

## Can i use variables in my e-mails?

Yes, you can. You can use all variables that are public available on the event. You can use them like this:

```
Hello {$user->name},

Order #{$order->id} just shipped to:

{$order->shipment->address}
```
