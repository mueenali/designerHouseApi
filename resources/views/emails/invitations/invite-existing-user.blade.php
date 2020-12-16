@component('mail::message')
    # Hi

    You have been invited to join the team
    **{{$invitation->team->name}}**.
    Because you are already a member of this platform, you just need to accept
    or reject the invitation in your [team management console]({{$url}}).

    @component('mail::button', ['url' => $url])
        Go to dashboard
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
