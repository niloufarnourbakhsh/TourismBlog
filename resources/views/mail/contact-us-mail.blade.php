<x-mail::message>
# تماس با ما

شما یک پیام از {{ $name }}
با ایمیل {{$email}}
دارید.
    متن پیام:
    {{ $body }}
<x-mail::button :url="'https://google.com'">
تماس با ما
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
